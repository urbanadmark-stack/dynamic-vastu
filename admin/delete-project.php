<?php
require_once '../config.php';
checkAuth();
require_once '../includes/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: projects.php');
    exit();
}

$project_id = intval($_GET['id']);

if (deleteProject($project_id)) {
    header('Location: projects.php?deleted=1');
} else {
    header('Location: projects.php?error=delete_failed');
}
exit();
?>

