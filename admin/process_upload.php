<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $target_dir = "../assets/images/uploads/";
    $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    $image_info = getimagesize($_FILES["image"]["tmp_name"]);
    if ($image_info === false) {
        echo json_encode(['success' => false, 'message' => 'File is not a valid image.']);
        exit;
    }
    
    $width = $image_info[0];
    $height = $image_info[1];
    
    if ($width < 500 || $height < 500) {
        echo json_encode(['success' => false, 'message' => "Image must be at least 500px by 500px. Your image is {$width}px x {$height}px."]);
        exit;
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo json_encode(['success' => true, 'path' => 'assets/images/uploads/' . $new_filename]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Upload failed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
