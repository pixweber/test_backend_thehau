<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\LogAppelImport;
use App\Utils\Utils;
use PDOException;

class LogAppelImportRepository {
    private Database $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function getRecordsByImportId(int $importId) {
        $this->database->query('SELECT * FROM log_appels_import WHERE import_id = :import_id');
        $this->database->bind(':import_id', $importId);
        $this->database->execute();
        return $this->database->get_records();
    }

    public function getStats(array $importRecords): array {
        $stats = [
            'totalCalls' => 0,
            'totalDuration' => 0,
            'totalAnswered' => 0,
            'totalNotAnswered' => 0,
            'answeredRate' => 0,
        ];

        foreach ($importRecords as $record) {
            $stats['totalCalls']++;
            $stats['totalDuration'] += $record['duration'];

            $stats['totalAnswered'] += $record['disposition'] === 'ANSWERED' ? 1 : 0;
            $stats['totalNotAnswered'] += $record['disposition'] === 'NO ANSWER' ? 1 : 0;
        }

        $stats['answeredRate'] = $stats['totalCalls'] > 0 ? round(($stats['totalAnswered'] / $stats['totalCalls']) * 100, 2) : 0;

        // Convert duration to human readable format
        $stats['totalDuration'] = Utils::secondsToHoursMinutes($stats['totalDuration']);

        return $stats;
    }

    public function create(LogAppelImport $logAppelImport) {
        $this->database->query('INSERT INTO log_appels_import (import_id, calldate, src, dest, duration, disposition, dstchannel) VALUES (:import_id, :calldate, :src, :dest, :duration, :disposition, :dstchannel)');
        $this->database->bind(':import_id', $logAppelImport->getImportId());
        $this->database->bind(':calldate', $logAppelImport->getCallDate());
        $this->database->bind(':src', $logAppelImport->getSrc());
        $this->database->bind(':dest', $logAppelImport->getDst());
        $this->database->bind(':duration', $logAppelImport->getDuration());
        $this->database->bind(':disposition', $logAppelImport->getDisposition());
        $this->database->bind(':dstchannel', $logAppelImport->getDstChannel());
        return $this->database->execute();
    }

    public function delete($id) {
        $this->database->query('DELETE FROM log_appels_import WHERE id = :id');
        $this->database->bind(':id', $id);
        return $this->database->execute();
    }
}