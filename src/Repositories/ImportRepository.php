<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\Import;
use PDOException;

class ImportRepository {
    private Database $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function getAll(string $order = 'DESC') {
        $query = 'SELECT * FROM imports ORDER BY created_at';

        if ($order === 'ASC') {
            $query .= ' ASC';
        } else {
            $query .= " $order";
        }

        $this->database->query($query);
        $this->database->execute();
        return $this->database->get_records();
    }

    public function create(Import $import): int {
        try {
            $this->database->query('INSERT INTO imports(name, fileName, fileSize, created_at) VALUES(:name, :fileName, :fileSize, :created_at)');
            $this->database->bind(':name', $import->getName());
            $this->database->bind(':fileName', $import->getFileName());
            $this->database->bind(':fileSize', $import->getFileSize());
            $this->database->bind(':created_at', date('Y-m-d H:i:s'));
            $this->database->execute();

            return $this->database->get_last_insert_id();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getById(int $id) {
        $this->database->query('SELECT * FROM imports WHERE id = :id');
        $this->database->bind(':id', $id);
        $this->database->execute();
        return $this->database->get_single();
    }
}