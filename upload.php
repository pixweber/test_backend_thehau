<?php
include 'vendor/autoload.php';

use App\Models\Import;
use App\Models\LogAppel;
use App\Models\LogAppelImport;
use App\Repositories\ImportRepository;
use App\Repositories\LogAppelImportRepository;
use App\Repositories\LogAppelRepository;
use App\Utils\Security;
use App\Utils\UploadValidation;
use App\Utils\Utils;

// Slow down the process to simulate a real-world scenario
sleep(1);

// Security check
Security::setSecurityHttpHeaders();
Security::setCrossOriginResourcePolicy();
Security::check(); // Check CSRF

$file = $_FILES['csvFile'];

// Do some validation
$validationErrors  = UploadValidation::validateFile($file);
if ( count($validationErrors) > 0 ) {
    Utils::returnJsonResponse($validationErrors, 400, 'Error while uploading file');
}

// After validation and security check. Process the file
process($file);

// Process the file
function process($file) {
    // Create a import entry
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $importRepository = new ImportRepository();
    $import = new Import();
    $importName = 'import_' . date('YmdHis') . '_' . $fileName;
    $import->setName($importName);
    $import->setFileName($fileName);
    $import->setFileSize($fileSize);
    $importId = $importRepository->create($import);

    $file = $_FILES['csvFile']['tmp_name'];
    $handle = fopen($file, "r");

    // Skip the header
    $headerSkipped = false;

    while( ($row = fgetcsv($handle, 0, ";") ) !== false) {
        if (!$headerSkipped) {
            $headerSkipped = true;
            continue;
        }

        // Insert to log_appels table
        $logAppel = new LogAppel();
        $logAppel->setCallDate($row[0]);
        $logAppel->setSrc(Utils::normalizePhoneNumber($row[1]));
        $logAppel->setDst(Utils::normalizePhoneNumber($row[2]));
        $logAppel->setDuration($row[3]);
        $logAppel->setDisposition($row[4]);
        $logAppel->setDstChannel($row[5]);

        $logAppelRepository = new LogAppelRepository();
        $logAppelRepository->create($logAppel);

        // Insert to log_appels_imprt table
        $logAppelImport = new LogAppelImport();
        $logAppelImport->setImportId($importId);
        $logAppelImport->setCallDate($row[0]);
        $logAppelImport->setSrc(Utils::normalizePhoneNumber($row[1]));
        $logAppelImport->setDst(Utils::normalizePhoneNumber($row[2]));
        $logAppelImport->setDuration($row[3]);
        $logAppelImport->setDisposition($row[4]);
        $logAppelImport->setDstChannel($row[5]);

        $logAppelImportRepository = new LogAppelImportRepository();
        $logAppelImportRepository->create($logAppelImport);
    }

    fclose($handle);

    // return json response
    $responseData = [
        'importId' => $importId,
        'importName' => $importName,
        'importFileName' => $fileName,
        'importFileSize' => $fileSize,
    ];

    Utils::returnJsonResponse($responseData, 200, 'File uploaded successfully');
}