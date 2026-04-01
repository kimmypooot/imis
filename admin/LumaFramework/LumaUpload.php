<?php
// Make sure all expected POST and FILE inputs exist
if (isset($_FILES['fileToUpload']) && isset($_POST['location']) && isset($_POST['fileName'])) {
    $file = $_FILES['fileToUpload'];
    $location = rtrim($_POST['location'], '/'); // remove trailing slash if any
    $name = $_POST['fileName'];

    // Sanitize location & file name (important!)
    $location = preg_replace('/[^a-zA-Z0-9_\/\-]/', '', $location);
    $name = preg_replace('/[^a-zA-Z0-9_\-]/', '', $name);

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $targetPath = $location . '/' . $name . '.' . $extension;

    try {
        $relativePath = '../' . $location;
        if (!file_exists($relativePath)) {
            mkdir($relativePath, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $targetPath = $relativePath . '/' . $name . '.' . $extension;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo json_encode(["success" => true, "path" => $targetPath, "fileName" => $name . '.' . $extension]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to move uploaded file."]);
        }
    } catch (Exception $e) { // 🛠️ FIXED: added correct Exception class
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Missing required data/No file selected."]);
}
?>