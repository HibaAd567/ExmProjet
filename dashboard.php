<?php 
    include 'connexion.php';

    session_start();

    if (!isset($_SESSION['logged'])) {
        header("Location: login.php");
        exit;
    }

    echo "Welcome Directeur";
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia Flow</title>
    <link href="loginn.css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="logo.jpg" alt="Mon logo" height="90px">
            <h3>Academia Flow</h3>
        </div>
    </div>

    
</body>

</html>