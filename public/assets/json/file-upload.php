<?php
$ds = DIRECTORY_SEPARATOR;  // Directory separator
$storeFolder = 'uploads';   // Folder to store uploaded files
$targetPath = dirname(__FILE__) . $ds . $storeFolder . $ds;  // Target folder path

// Check if the uploads folder exists, if not, create it
if (!file_exists($targetPath)) {
    mkdir($targetPath, 0777, true);
}

// Check if the file has been uploaded
if (!empty($_FILES)) {
    // Get the uploaded file name
    $fileName = $_FILES['file']['name'];

    // Check if a file with the same name already exists in the target folder
    if (file_exists($targetPath . $fileName)) {
        // Remove the old file before uploading the new one
        unlink($targetPath . $fileName);  // Delete the old file
    }

    // Get the temporary file
    $tempFile = $_FILES['file']['tmp_name'];

    // Move the uploaded file to the target folder
    move_uploaded_file($tempFile, $targetPath . $fileName);
}
