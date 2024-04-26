<?php
include 'vendor/autoload.php';

use App\Repositories\ImportRepository;
use App\Repositories\LogAppelImportRepository;
use App\Utils\Components;
use App\Utils\Security;
use App\Utils\Utils;

include 'layout/header.php'; ?>

    <!-- Begin Page Content -->
    <div class="container">

    <?php
    // Get id from query string and avoid SQL injection
    $importId = explode("/",($_SERVER["REQUEST_URI"]))[2]; // $_GET['id']
    $importId = filter_var($importId, FILTER_SANITIZE_NUMBER_INT);

    // Get import details
    $importRepository = new ImportRepository();
    $import = $importRepository->getById($importId);

    $logAppelImportRepository = new LogAppelImportRepository();
    $logAppelsImport = $logAppelImportRepository->getRecordsByImportId($importId);
    $stats = $logAppelImportRepository->getStats($logAppelsImport);

    if ($import ):
    ?>

    <!-- Page Heading -->
    <h1 class="h1 mb-2 text-gray-800">View import #<?php echo $importId; ?></h1>
    <div class="mb-4 row">
        <div class="col-sm-8">
            <div><b>Name</b>: <?php echo $import['name']; ?></div>
            <div><b>File size</b>: <?php echo Utils::convertBytesToHumanReadable($import['fileSize']); ?></div>
            <div><b>Import date</b>: <?php echo $import['created_at']; ?></div>
        </div>
        <div class="col-sm-4 text-right">
            <a href="/" class="btn btn-primary"><i class="fa fa-chevron-left pr-2"></i> Back to CSV Importer</a>
        </div>
    </div>
    <?php else: ?>
    <div class="mb-4 row">
        <div class="col-12 text-right">
            <a href="/" class="btn btn-primary"><i class="fa fa-chevron-left pr-2"></i> Back to CSV Importer</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div>
            <!--Display imports-->
            <div class="card-body">
                <div id="stats" class="row mb-4">
                    <?php
                    $statList = [
                        'totalCalls' => [
                            'title' => 'Calls',
                            'icon' => 'fa-phone',
                        ],
                        'totalDuration' => [
                            'title' => 'Duration',
                            'icon' => 'fa-clock',
                        ],
                        'totalAnswered' => [
                            'title' => 'Answered',
                            'icon' => 'fa-check',
                        ],
                        'totalNotAnswered' => [
                            'title' => 'Not Answered',
                            'icon' => 'fa-times',
                        ],
                        'answeredRate' => [
                            'title' => 'Answered Rate',
                            'icon' => 'fa-percent',
                        ]
                    ];
                    foreach ($statList as $key => $value) :
                        $title = $value['title'];
                    ?>
                    <div class="col">
                        <?php echo Components::statsCard($title, $stats[$key], $value['icon']); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php
                if ( count($logAppelsImport) > 0 ):
                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Call date</th>
                        <th>Src</th>
                        <th>Dest</th>
                        <th>Duration</th>
                        <th>Disposition</th>
                        <th>Dstchannel</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($logAppelsImport as $item) : ?>
                        <tr>
                            <td><?= $item['calldate'] ?></td>
                            <td><?= $item['src'] ?></td>
                            <td><?= $item['dest'] ?></td>
                            <td><?= $item['duration'] ?></td>
                            <td><?= Security::escapeHtml($item['disposition']) ?></td>
                            <td><?= Security::escapeHtml($item['dstchannel']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div class="alert alert-warning">No data found</div>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->


<?php include 'layout/footer.php'; ?>