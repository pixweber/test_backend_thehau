<?php
namespace App\Models;
use App\Core\Database;

class Import extends Database {
    private int $id;
    private string $name;
    private string $fileName;
    private int $fileSize;
    private \DateTime|string $created_at;

    public function __construct() {
        parent::__construct();

        $this->created_at = date('Y-m-d H:i:s');
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getFileName(): string {
        return $this->fileName;
    }

    public function setFileName(string $fileName): void {
        $this->fileName = $fileName;
    }

    public function getFileSize(): int {
        return $this->fileSize;
    }

    public function setFileSize(int $fileSize): void {
        $this->fileSize = $fileSize;
    }

    public function getCreatedAt(): \DateTime|string {
        return $this->created_at;
    }
}