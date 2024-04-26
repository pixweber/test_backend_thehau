<?php
include('./vendor/autoload.php');

use App\Repositories\ImportRepository;
use App\Utils\Security;
use App\Utils\Utils;

include 'layout/header.php';

Security::generateCSRFToken();
?>

<!-- Begin Page Content -->
<div class="container">

    <!-- Page Heading -->
    <h1 class="h1 mb-2 text-gray-800">CSV Importer</h1>
    <p class="mb-4">Upload your phone logs file in CSV format</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="upload_form" class="drop-zone">
                <p id="description">Click or drag & drop phone logs CSV file here</p>
                <input type="file" id="file_input" accept=".csv">
                <span id="dropped-file"></span>
                <input id="csrf_token" type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            </form>
            <div class="mt-3">
                <a id="view-data" class="btn btn-block btn-primary disabled">View data</a>
            </div>
            <div id="loading" style="display: none;">
                <p class="text-center"><img src="/assets/img/loading.gif" height="70" /></p>
            </div>
            <div id="redirect" class="mt-2" style="display: none">
                <p class="text-center text-danger">You will be redirected to the results page in <span id="countdown"></span> seconds<br /> Or click on the "View data" button to see the import results</p>
            </div>
        </div>

        <div>
            <!--Display imports-->
            <div class="card-body">
                <h1 class="h1 mb-2 text-gray-800">Imports</h1>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Import ID</th>
                            <th>Name</th>
                            <th>File Name</th>
                            <th>File Size</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $importRepository = new ImportRepository();
                        $imports = $importRepository->getAll();
                        foreach ($imports as $import) :
                        ?>
                            <tr>
                                <td><a href="/view-import/<?= $import['id'] ?>"><?= $import['id'] ?></a></td>
                                <td><a href="/view-import/<?= $import['id'] ?>"><?= $import['name'] ?></a></td>
                                <td><?= $import['fileName'] ?></td>
                                <td><?= Utils::convertBytesToHumanReadable($import['fileSize']); ?></td>
                                <td><?= $import['created_at'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
    </div>

</div>
<!-- /.container-fluid -->


<?php include 'layout/footer.php'; ?>