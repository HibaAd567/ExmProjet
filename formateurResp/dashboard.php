<?php
include '../connexion.php';
session_start();

if(!isset($_SESSION['logged']) || $_SESSION['role'] !== 'FORMATEUR_RESPONSABLE') {
    header("Location: ../login.php");
    exit;
}


$stmt = $pdo->prepare("SELECT nom, prenom FROM utilisateurs WHERE email = ?");
$stmt->execute([$_SESSION['email']]);
$user = $stmt->fetch();
$nom = $user['nom'];
$prenom = $user['prenom'];
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
        h1{
            text-align:center;
            color:red;

        }
    </style>
</head>
<body>

    <div class="navbar navbar-expand-lg p-3">
        <div class="container-fluid">

            <div class="d-flex align-items-center gap-3">
                <img class="mt-1" src="../logo.jpg" alt="Mon logo" height="90px">
                <h3 class="mt-2">Academia Flow</h3>
            </div>

    <h1>Tableau des modules</h1>

    
</body>
</html>