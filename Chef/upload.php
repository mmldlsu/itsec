<?php 
    // Check if a file was uploaded
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];

        // Check for any upload errors
        if ($file['error'] === UPLOAD_ERR_OK) {
            $fileName = $file['name'];
            $tmpFilePath = $file['tmp_name'];

            // Specify the directory to store the uploaded files
            $uploadDirectory = 'imgs/';

            // Generate a unique file name to avoid conflicts
            $uniqueFileName = uniqid() . '_' . $fileName;

            // Move the uploaded file to the desired directory
            $destinationPath = $uploadDirectory . $uniqueFileName;

            if (move_uploaded_file($tmpFilePath, $destinationPath)) {
                // File uploaded successfully

                // Store the file path in the database
                $filePathInDatabase = mysqli_real_escape_string($DBConnect, $destinationPath);
            } else {
                echo "Error moving the uploaded file to the destination directory.";
            }
        } else {
            echo "Upload failed with error code: " . $file['error'];
        }
}
?>