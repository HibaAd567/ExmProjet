<?php
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\IOFactory;

    include '../connexion.php';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $spreadsheet = IOFactory::load('your_excel_file.xlsx');
        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($worksheet->getRowIterator(2) as $row) {
            $cells = $row->getCellIterator();
            $cells->setIterateOnlyExistingCells(false);

            $data = [];
            foreach ($cells as $cell) {
                $data[] = trim($cell->getValue());
            }

            $codeFiliere = $data[6];          
            $filiere = $data[7];             
            $secteur = $data[5];             
            $niveau = $data[3];              
            $typeFormation = $data[4];        
            $annee = $data[2];                
            $codeGroupe = $data[9];           
            $codeModule = $data[10];          
            $module = $data[11];             
            $stmt = $pdo->prepare("SELECT id FROM filieres WHERE code_filiere = ?");
            $stmt->execute([$codeFiliere]);
            if ($stmt->rowCount() == 0) {
                $insertFiliere = $pdo->prepare("INSERT INTO filieres (code_filiere, intitule, secteur, niveau, type_formation) VALUES (?, ?, ?, ?, ?)");
                $insertFiliere->execute([$codeFiliere, $filiere, $secteur, $niveau, $typeFormation]);
            }

            $stmtGroupe = $pdo->prepare("CALL insert_groupe(?, ?, ?)");
            $stmtGroupe->execute([$codeFiliere, $codeGroupe, $annee]);

            $stmtModule = $pdo->prepare("CALL insert_module(?, ?, ?, ?)");
            $stmtModule->execute([$codeFiliere, $codeModule, $module, 0]);

            while ($pdo->nextRowset()) {}
        }

        echo "Import completed successfully.";

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

?>
