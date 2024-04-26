<?php
namespace App\Models;

class LogAppelImport extends LogAppel {
    private int $import_id;

    public function __construct() {
        parent::__construct();
    }

    public function getImportId(): int {
        return $this->import_id;
    }

    public function setImportId(int $import_id): void {
        $this->import_id = $import_id;
    }
}
