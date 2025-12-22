<?php
include '../connexion.php';
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['role'] !== 'FORMATEUR_RESPONSABLE') {
    header("Location: ../login.php");
    exit;
}


$email = $_SESSION['email'];

$stmt = $pdo->prepare("SELECT nom, prenom FROM utilisateurs WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $nom = $user['nom'];
    $prenom = $user['prenom'];
} else {
    $nom = 'Utilisateur';
    $prenom = '';
}


$sql = "
SELECT 
    modules.intitule AS module,
    groupes.code_groupe AS groupe,
    attributions_verification.statut_verification
FROM attributions_verification
JOIN attributions_module 
    ON attributions_verification.attribution_module_id = attributions_module.id
JOIN modules 
    ON attributions_module.module_id = modules.id
JOIN groupes 
    ON attributions_module.groupe_id = groupes.id
";

$result = $pdo->query($sql);


?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"><link href="loginn.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        h1 {
        color: #0c0c0dff; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 30px; 
        text-transform: capitalize; 
}

        h3{
            font-size:25px;
            color: rgba(71, 10, 128, 1);

        }

   
    </style>
</head>
<body>

    <div class="d-flex align-items-center gap-3">
        <img class="mt-1" src="../logo.jpg" alt="Mon logo" height="85px">
        <h3>Academia Flow</h3>
    </div>
    <br>

    <h1>Gestion des modules</h1><br><br>
    <div class="row p-4 mt-4">

        <table class="table table-hover table-bordered table-striped align-middle shadow-sm rounded-4 overflow-hidden">
            <thead class="table-primary text-center">
                <tr>
                    <th>Module</th>
                    <th>Groupe</th>
                    <th>Statut du paquet</th>
                    <th>Actions</th>
                </tr>
            </thead>
             <tbody class="text-center">
        <?php
       $rows = $result->fetchAll(PDO::FETCH_ASSOC); 

    if ($rows) { 
        foreach ($rows as $row) {
        ?>
            <tr>
                <td><?= $row['module'] ?></td>
                <td><?= $row['groupe'] ?></td>
                <td>
                    <?php
                    if ($row['statut_verification'] === 'NON_DEPOSE') {
                        echo '<span class="badge bg-danger">Non déposé</span>';
                    } elseif ($row['statut_verification'] === 'NON_CONFORME') {
                        echo '<span class="badge bg-warning text-dark">Anomalie</span>';
                    } else {
                        echo '<span class="badge bg-success">Validé</span>';
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($row['statut_verification'] === 'NON_DEPOSE') {
                        echo '<button class="btn btn-success btn-sm">Déposer</button>';
                    } elseif ($row['statut_verification'] === 'NON_CONFORME') {
                        echo '<button class="btn btn-warning btn-sm">Voir / Corriger</button>';
                    } else {
                        echo '<button class="btn btn-secondary btn-sm">Voir</button>';
                    }
                    ?>
                </td>
            </tr>
        <?php }
        }  ?>
    </tbody>
        </table>

        </div>
    </div>        

    

    

    
</body>
</html>