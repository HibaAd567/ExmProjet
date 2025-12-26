<?php 
    include "../connexion.php";

    session_start();

    if(!isset($_SESSION['logged'])) {
        header("Location: ../login.php");
        exit;
    }

    $stmt = $pdo -> prepare("select nom from utilisateurs where id = 1");
    $stmt -> execute();
    $nom = $stmt -> fetchColumn();



    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"><link href="loginn.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Affectation</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        h3 {
            color: rgb(72, 9, 130);
        }

        div div ul li i {
            color: rgb(72, 9, 130);
            cursor: pointer;
        }

        div div ul li i:hover{
            color: lightblue;
        }

        ul li a i {
            color: rgba(114, 168, 60, 1);
        }

    </style>
</head>
<body>
    <!--NAV 1st -->
    <div class="navbar navbar-expand-lg p-3">
        <div class="container-fluid">
            <!-- Left side -->
            <div class="d-flex align-items-center gap-3">
                <img class="mt-1" src="../logo.jpg" alt="Mon logo" height="90px">
                <h3 class="mt-2">Academia Flow</h3>
            </div>

            <!-- right side -->
            <ul class="navbar-nav ms-auto d-flex align-items-center gap-3">
                <li class="nav-item"> 
                    <!-- notification bell -->
                    <i id="bell" class="fa-solid fa-bell fa-xl"></i> 
                </li>
                <li class="nav-item"> 
                    <div class="name">
                        <span class="border border-primary rounded-4 p-2 m-2"> 
                            <?php echo "Mr.". htmlspecialchars($nom); ?>  
                        </span> 
                    </div>
                </li>
            </ul>
        </div>  
    </div>

    <!--NAV 2nd -->
    <ul class="nav nav-underline nav-pills nav-justified bg-primary-subtle p-3">
        <li class="nav-item">
            <a class="nav-link " href="dashboard.php"> 
                <i class="fa-solid fa-chart-pie fa-lg me-1 "></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="fil_grp_mod.php"> 
                <i class="fa-solid fa-layer-group fa-lg me-1 "></i>Filiers & Groupes & Modules
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="affectation.php"> 
                <i class="fa-solid fa-paperclip fa-lg me-1 "></i> Affectation
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="formateurs.php">
                <i class="fa-solid fa-user-group fa-lg me-1 "></i> Formateurs
            </a>
        </li>
    </ul>








</body>
</html>