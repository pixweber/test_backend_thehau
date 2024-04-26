<?php
namespace App\Utils;

use App\Config;

class UploadValidation {
    public static function validateFile(array $file): array {
        $errors = [];

        if (empty($file['name'])) {
            $errors[] = 'Please select a file to upload';
        }

        if ($file['size'] > Config::MAX_UPLOAD_SIZE) {
            $errors[] = 'File size must be less than 1MB';
        }

        $allowedExtensions = ['csv'];
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

        if (!in_array($fileExtension, $allowedExtensions)) {
            $errors[] = 'Only CSV files are allowed';
        }

        return $errors;
    }
}