<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

include 'connexion.php'; // your PDO connection

try {
    $spreadsheet = IOFactory::load('Base plate Modules.csv');
    $worksheet = $spreadsheet->getActiveSheet();

    foreach ($worksheet->getRowIterator(2) as $row) {
        $cells = $row->getCellIterator();
        $cells->setIterateOnlyExistingCells(false);

        $data = [];
        foreach ($cells as $cell) {
            $data[] = trim($cell->getValue() ?? '');
        }

        $codeFiliere   = $data[6] ?? '';
        $filiere       = $data[7] ?? '';
        $secteur       = $data[5] ?? '';
        $niveau        = $data[3] ?? 'TS'; // default to 'TS' if empty
        if (empty($niveau)) {
            $niveau = 'TS';
        }
        $typeFormation = $data[4] ?? '';

        if (!$codeFiliere || !$filiere) {
            // Skip invalid rows with no code or name
            continue;
        }

        // Check if filiere exists
        $stmt = $pdo->prepare("SELECT id FROM filieres WHERE code_filiere = ?");
        $stmt->execute([$codeFiliere]);
        $filiereId = $stmt->fetchColumn();

        if (!$filiereId) {
            $insertFiliere = $pdo->prepare(
                "INSERT INTO filieres (code_filiere, intitule, secteur, niveau, type_formation) VALUES (?, ?, ?, ?, ?)"
            );
            $insertFiliere->execute([$codeFiliere, $filiere, $secteur, $niveau, $typeFormation]);
            echo "Inserted filiere: $codeFiliere - $filiere\n";
        } else {
            echo "Filiere already exists: $codeFiliere\n";
        }
    }

    echo "Filiere import done.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
