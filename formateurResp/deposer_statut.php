<?php
include '../connexion.php';
session_start();

if ($_SESSION['role'] !== 'FORMATEUR_RESPONSABLE') {
    exit;
}

$id = $_POST['verification_id'];

$req = $pdo->prepare("
    UPDATE attributions_verification
    SET statut_verification = 'DEPOSE'
    WHERE id = ?
");
$req->execute([$id]);

header("Location: dashboard.php");
exit;
