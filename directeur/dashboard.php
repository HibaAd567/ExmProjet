<?php 
    include '../connexion.php';

    session_start();

    if (!isset($_SESSION['logged'])) {
        header("Location: ../login.php");
        exit;
    }

    $stmt = $pdo->prepare("Select nom from utilisateurs where id = 1");
    $stmt -> execute();
    $nom = $stmt -> fetchColumn();

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia Flow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"><link href="loginn.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        h3 {
            color: rgb(72, 9, 130);
        }

        div div ul li a i {
            color: rgb(72, 9, 130);
        }

        div div ul li a i:hover{
            color: lightblue;
        }

        ul li a i {
            color: rgba(114, 168, 60, 1);
        }

        button {
            width: 160px;
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
                    <a class="nav-link position-relative" href="#" > 
                        <i class="fa-solid fa-bell fa-xl"></i> 
                    </a> 
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

    <!--CARDS -->
    <div class="container-fluid p-4 mt-4">
        <div class="row text-center">
            <!--CARD 1-->
            <div class="col-3">
                <div class="card shadow-sm rounded-5 border-opacity-50 border-success p-3 ">
                    <div class="card-body text-center">
                        <h4 class="text-center mt-3">Modules Valides</h4>
                        <p class="text-center fs-3 mb-3 text-success">9</p>
                    </div>
                </div>
            </div>
            <!--CARD 2-->
            <div class="col-3">
                <div class="card shadow-sm rounded-5 border-opacity-50 border-success p-3 ">
                    <div class="card-body text-center">
                        <h4 class="text-center mt-3">Modules non deposes</h4>
                        <p class="text-center fs-3 mb-3 text-success">15</p>
                    </div>
                </div>
            </div>
            <!--CARD 3-->
            <div class="col-3">
                <div class="card shadow-sm  rounded-5 border-opacity-50 border-success pt-1">
                    <div class="card-body text-center">
                        <h4 class="text-center mt-3">Modules deposes en attente de Verification</h4>
                        <p class="text-center fs-3 mb-3 text-success">11</p>
                    </div>
                </div>
            </div>
            <!--CARD 4-->
            <div class="col-3">
                <div class="card shadow-sm rounded-5 border-opacity-50 border-success p-3 ">
                    <div class="card-body text-center">
                        <h4 class="text-center mt-3">Modules non Conformes</h4>
                        <p class="text-center fs-3 mb-3 text-success">5</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!--TABLE -->
    <div class="row p-4 mt-4">
        <div class="col-12 border shadow-sm rounded-4  p-3"> 
        <h3 class="pb-3">Affectation recentes</h3>
        <table class="table table-hover table-bordered table-striped align-middle shadow-sm rounded-4 overflow-hidden">
            <thead class="table-primary text-center">
                <tr>
                    <th>Filiere</th>
                    <th>Module</th>
                    <th>Groupe</th>
                    <th>Formateur Responsable</th>
                    <th>Formateur Verificateur</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <tr>
                    <td>DEV</td>
                    <td>HTML</td>
                    <td>101</td>
                    <td>Ahmed</td>
                    <td>Bob</td>
                    <td><span class="badge bg-success text-dark">Depose</span></td>
                </tr>
                <tr>
                    <td>DEV</td>
                    <td>HTML</td>
                    <td>101</td>
                    <td>Ahmed</td>
                    <td>Bob</td>
                    <td><span class="badge bg-primary text-dark">En attente</span></td>
                </tr>
                <tr>
                    <td>DEV</td>
                    <td>HTML</td>
                    <td>101</td>
                    <td>Ahmed</td>
                    <td>Bob</td>
                    <td><span class="badge bg-warning text-dark">En cours</span></td>
                </tr>
                <tr>
                    <td>DEV</td>
                    <td>HTML</td>
                    <td>101</td>
                    <td>Ahmed</td>
                    <td>Bob</td>
                    <td><span class="badge bg-danger text-dark">non deposes</span></td>
                </tr>
            </tbody>
        </table>
        <button class="btn btn-primary ms-2">Export</button>
        </div>
    </div>        

    
</body>

</html>