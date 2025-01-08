<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Pfad zum Galerie-Ordner
$directory = __DIR__ . '/gallery';
if (!is_dir($directory)) {
    echo json_encode(['error' => 'Das Verzeichnis existiert nicht.']);
    exit;
}

// Galerie-Ordner einlesen (ohne Punkteinträge wie '.' und '..')
$files = array_diff(scandir($directory), ['.', '..']);
$images = [];

// Schleife über alle Einträge
foreach ($files as $file) {
    $fullPath = $directory . '/' . $file;

    // Nur echte Dateien prüfen, keine Unterordner
    if (!is_file($fullPath)) {
        continue;
    }

    // Nur Bilddateien (jpg|jpeg|png|gif|webp) weiter berücksichtigen
    if (!preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
        continue;
    }

    // Dateinamen dekodieren (z.B. bei Sonderzeichen)
    $decodedFilename = urldecode($file);

    // Split anhand von '_' in 4 Teile:
    $parts = explode('_', $decodedFilename);

    // Wenn weniger als 4 Teile, ignorieren wir die Datei (nicht ins JSON)
    if (count($parts) < 4) {
        continue;
    }

    list($titlePart, $material, $datePart, $dimensionsPart) = $parts;

    // Prüfen, ob datePart Monat-Jahr oder nur Jahr enthält
    if (strpos($datePart, '-') !== false) {
        // Format: Monat-Jahr, z.B. "Januar-2023"
        list($maybeMonth, $maybeYear) = explode('-', $datePart, 2);
        // Jahr 4-stellig?
        if (!preg_match('/^\d{4}$/', $maybeYear)) {
            continue;  // nicht ins JSON
        }
    } else {
        // Nur Jahr
        // Endung entfernen, z.B. "2023.jpg" -> "2023"
        $maybeYear = preg_replace('/\.\w+$/', '', $datePart);
        if (!preg_match('/^\d{4}$/', $maybeYear)) {
            continue;
        }
    }

    // Kommt die Datei hier an, gilt sie als "korrekt" -> in $images aufnehmen
    $images[] = $file;
}

// JSON-Ausgabe aller "korrekten" Dateien
echo json_encode(array_values($images));
