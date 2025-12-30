<?php
require_once '../config.php';
checkAuth();
require_once '../includes/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$property = getProperty(intval($_GET['id']));
if (!$property) {
    header('Location: index.php');
    exit();
}

// Delete images
$images = getPropertyImages($property['images']);
foreach ($images as $image) {
    $image_path = UPLOAD_DIR . $image;
    if (file_exists($image_path)) {
        unlink($image_path);
    }
}

// Delete property
if (deleteProperty($property['id'])) {
    header('Location: index.php?deleted=1');
} else {
    header('Location: index.php?error=delete_failed');
}
exit();
?>

