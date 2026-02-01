<?php
require_once __DIR__ . '/../config/db.php';

function h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function getHouseTypes(): array {
    return ['Apartment', 'House', 'Land'];
}

function handleImageUpload(string $fieldName = 'image'): ?string {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/jpg'  => 'jpg'
    ];

    $tmpPath = $_FILES[$fieldName]['tmp_name'];
    $mime    = mime_content_type($tmpPath);

    if (!array_key_exists($mime, $allowedTypes)) {
        return null;
    }

    if ($_FILES[$fieldName]['size'] > 2 * 1024 * 1024) {
        return null;
    }

    $extension = $allowedTypes[$mime];
    $fileName  = uniqid('property_', true) . '.' . $extension;

    $uploadDir = __DIR__ . '/../assets/images/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $destination = $uploadDir . $fileName;

    if (!move_uploaded_file($tmpPath, $destination)) {
        return null;
    }

    return 'assets/images/' . $fileName;
}

function validatePropertyData(array $data): array {
    $errors = [];

    $title       = trim($data['title']       ?? '');
    $description = trim($data['description'] ?? '');
    $location    = trim($data['location']    ?? '');
    $price       = trim($data['price']       ?? '');
    $house_type  = trim($data['house_type']  ?? '');

    if ($title === '') {
        $errors[] = 'Title is required.';
    }
    if ($description === '') {
        $errors[] = 'Description is required.';
    }
    if ($location === '') {
        $errors[] = 'Location is required.';
    }
    if ($price === '' || !is_numeric($price) || $price <= 0) {
        $errors[] = 'Price must be a positive number.';
    }
    if ($house_type === '' || !in_array($house_type, getHouseTypes(), true)) {
        $errors[] = 'Invalid house type selected.';
    }

    return $errors;
}
