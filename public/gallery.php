<?php
header('Access-Control-Allow-Origin: *'); // Erlaubt Anfragen von allen Quellen
header('Content-Type: application/json');

// Rest deines Codes

$directory = __DIR__ . '/gallery'; // Pfad zum Galerie-Ordner

if (!is_dir($directory)) {
    echo json_encode(['error' => 'Das Verzeichnis existiert nicht.']);
    exit;
}

$files = array_diff(scandir($directory), ['.', '..']);
$images = array_filter($files, function($file) use ($directory) {
    return preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file) && is_file($directory . '/' . $file);
});

echo json_encode(array_values($images));
