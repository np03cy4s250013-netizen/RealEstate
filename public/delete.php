<?php
require_once __DIR__ . '/../includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT image_path FROM properties WHERE id = :id");
$stmt->execute([':id' => $id]);
$record = $stmt->fetch();

$deleteStmt = $pdo->prepare("DELETE FROM properties WHERE id = :id");
$deleteStmt->execute([':id' => $id]);

if ($record && $record['image_path']) {
    $filePath = __DIR__ . '/../' . $record['image_path'];
    if (is_file($filePath)) {
        unlink($filePath);
    }
}

header('Location: index.php');
exit;
