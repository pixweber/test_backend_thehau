<?php
namespace App\Repositories;

use App\Core\Database;
use PDOException;

class LogAppelRepository {
    private Database $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function getAll() {
        $this->database->query('SELECT * FROM log_appels');
        $this->database->execute();
        return $this->database->get_records();
    }

    public function getById($id) {
        $this->database->query('SELECT * FROM log_appels WHERE id = :id');
        $this->database->bind(':id', $id);
        $this->database->execute();
        return $this->database->get_single();
    }

    public function create($logAppel) {
        try {
            $this->database->query('INSERT INTO log_appels (calldate, src, dst, duration, disposition, dstchannel) VALUES (:calldate, :src, :dst, :duration, :disposition, :dstchannel)');
            $this->database->bind(':calldate', $logAppel->getCallDate());
            $this->database->bind(':src', $logAppel->getSrc());
            $this->database->bind(':dst', $logAppel->getDst());
            $this->database->bind(':duration', $logAppel->getDuration());
            $this->database->bind(':disposition', $logAppel->getDisposition());
            $this->database->bind(':dstchannel', $logAppel->getDstChannel());
            return $this->database->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // Check if the error code indicates a duplicate entry violation
                // Handle the constraint violation error here (you may choose to ignore it, log it, or handle it in some other way)
                // Logging "Ignoring constraint violation error." . PHP_EOL;
            } else {
                // Handle other types of errors
                // Logging "An error occurred: " . $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function update($logAppel) {
        $this->database->query('UPDATE log_appels SET calldate = :calldate, src = :src, dst = :dst, duration = :duration, disposition = :disposition, dstchannel = :dstchannel WHERE id = :id');
        $this->database->bind(':id', $logAppel->getId());
        $this->database->bind(':calldate', $logAppel->getCallDate());
        $this->database->bind(':src', $logAppel->getSrc());
        $this->database->bind(':dst', $logAppel->getDst());
        $this->database->bind(':duration', $logAppel->getDuration());
        $this->database->bind(':disposition', $logAppel->getDisposition());
        $this->database->bind(':dstchannel', $logAppel->getDstChannel());
        return $this->database->execute();
    }

    public function delete($id) {
        $this->database->query('DELETE FROM log_appels WHERE id = :id');
        $this->database->bind(':id', $id);
        return $this->database->execute();
    }
}