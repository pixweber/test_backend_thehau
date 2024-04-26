$(document).ready(function() {
    $('#upload_form').on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
    });

    $('#upload_form').on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
    });

    $('#upload_form').on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });

    $('#file_input').on('change', function(e) {
        const file = e.target.files[0];
        handleFile(file);
    });

    $('#upload_form').on('click', function(e) {
        if (e.target !== $('#file_input')[0]) {
            $('#file_input').click();
        }
    });
});

function handleFile(file) {
    if (file.type === 'text/csv') {
        const fileSizeKB = Math.round(file.size / 1024);
        $('#dropped-file').text(`Processing file: ${file.name} - ${fileSizeKB} KB`);

        // Upload the file using ajax request
        uploadFile();

    } else {
        alert('Please upload a CSV file.');
    }
}

function uploadFile(file) {
    const formData = new FormData();
    formData.append('csvFile', $('#file_input')[0].files[0]);
    formData.append('csrf_token', $('#csrf_token').val());

    // Hide description
    $('#description').hide();

    // Disable file upload
    handleOnFileUpload();

    // Show loading spinner
    $('#loading').show();

    $.ajax({
        url: '/upload.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            handleSuccess(response);
        },
        error: function(error) {
            console.log('error', error);
        },
        complete: function() {
            // Hide loading spinner
            $('#loading').hide();

            // Enable file upload
            handleAfterFileUpload();
        }
    });
}

function handleSuccess(response) {
    const importId = response.data.importId;
    const importName = response.data.importName;
    const importFileName = response.data.importFileName;
    const importFileSize = response.data.importFileSize;

    // Enable view data link and set href to the view import page
    $('#view-data').removeClass('disabled');
    $('#view-data').attr('href', '/view-import/' + importId);
    $('#view-data').text(`View data: import #${importId} - ${importName}`);

    // Initialize DataTable
    const table = $('#dataTable').DataTable();

    const idLink = `<a href="/view-import/${importId}">${importId}</a>`;
    const nameLink = `<a href="/view-import/${importId}">${importName}</a>`;

    // Add new row at the beginning
    const newRow = table.row.add([
        idLink,
        nameLink,
        importFileName,
        displayBytesReadable(importFileSize),
        'Now'
    ]).draw(false).node(); // Draw the table without refreshing and get the newly added row

    // Prepend the new row to the table
    $(newRow).prependTo('#dataTable tbody');

    // Redirect to view import page in seconds with a countdown
    $('#redirect').show();

    let count = 7;
    const timer = setInterval(function () {
        count--;
        $('#countdown').text(count);
        if (count === 0) {
            clearInterval(timer);
            window.location.href = '/view-import/' + importId;
        }
    }, 1000);
}

function handleOnFileUpload() {
    $('#description').hide(); // Show description
    $('#file_input').prop('disabled', false); // Enable file input
    $('.drop-zone').on('dragover drop'); // Reattach drag events
}

function handleAfterFileUpload() {
    $('#description').show(); // Hide description
    $('#file_input').prop('disabled', true); // Disable file input
    $('.drop-zone').off('dragover drop'); // Remove drag events
}

function displayBytesReadable(bytes) {
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];

    if (bytes === 0) return '0 Byte';

    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return parseFloat((bytes / Math.pow(1024, i)).toFixed(2)) + ' ' + sizes[i];
}