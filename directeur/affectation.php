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

        .dashboard-w {
            display: flex;
            width: 100%;
            transition: all 0.4s ease;
        }

        .main-content {
            width: 100%;
            transition: width 0.4s ease;
        }

        .notifications-panel {
            width: 0;
            overflow: hidden;
            background: rgba(253, 81, 81, 1);
            padding: 0px;
            margin: 15px;
            transition: width 0.4s ease, padding 0.4 ease;
            border-radius: 60px;
            color: rgba(243, 214, 214, 1);
            height: 700px;
        }

        .dashboard-w.show-notifications .main-content {
            width: 70%;
        }

        .dashboard-w.show-notifications .notifications-panel {
            width: 30%;
            padding: 20px;
        }


        
    </style>
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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



    <div id="dashboard" class="dashboard-w">
        <div class="main-content">

            <!--TABLE -->
            <div class="row p-4 mt-4">
                <div class="col-12 border shadow-sm rounded-4  p-3"> 
                    <h3 class="pb-3">Affectation </h3>
                    <table class="table table-hover table-bordered table-striped align-middle shadow-sm rounded-4 overflow-hidden">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Filiere</th>
                                <th>Module</th>
                                <th>Groupe</th>
                                <th>Formateur Responsable</th>
                                <th>Formateur Verificateur</th>
                                <th>Annee</th>
                                <th>Semestre</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr>
                                <td>DEV</td>
                                <td>HTML</td>
                                <td>101</td>
                                <td>Ahmed</td>
                                <td>Bob</td>
                                <td>2025</td>
                                <td>1er</td>
                            </tr>
                            <tr>
                                <td>DEV</td>
                                <td>HTML</td>
                                <td>101</td>
                                <td>Ahmed</td>
                                <td>Bob</td>
                                <td>2025</td>
                                <td>1er</td>
                            </tr>
                            <tr>
                                <td>DEV</td>
                                <td>HTML</td>
                                <td>101</td>
                                <td>Ahmed</td>
                                <td>Bob</td>
                                <td>2025</td>
                                <td>2eme</td>
                            </tr>
                            <tr>
                                <td>DEV</td>
                                <td>HTML</td>
                                <td>101</td>
                                <td>Ahmed</td>
                                <td>Bob</td>
                                <td>2025</td>
                                <td>2eme</td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="float-end btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#affecter_form" >Affecter </button>
                </div>
            </div>    
        </div>    

        <!--NOTIFICATION -->
        <div class="notifications-panel">
            <h4 class="mb-4 text-center">Notifications </h4>
            <div class="aLert alert-primary">New module</div>
            <div class="aLert alert-warning">new .......</div>
            <div class="aLert alert-danger">....... .......</div>
        </div>
    </div>    


     <!-- MODAL  -->
    <div class="modal fade" id="affecter_form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Affectation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulaire  -->
                    <form method="POST">
                        <div class="mb-3">
                            <label for="filiere" class="form-label mt-3">Filiere :</label>
                            <select name="filiere" class="form-select w-100" required>
                                <option value="DD">DD </option>
                                <option value="INFO">INFO  </option>
                                <option value="AA">AA  </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="groupe" class="form-label mt-3">Groupe :</label>
                            <select name="groupe" class="form-select w-100" required>
                                <option value="DD101">DD101 </option>
                                <option value="DD102">DD1023  </option>
                                <option value="INFO101">INFO101  </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="module" class="form-label mt-3">Module :</label>
                            <select name="module" class="form-select w-100" required>
                                <option value="DEV Front End">DEV Front End</option>
                                <option value="DEV Back End">DEV Back End</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="formateur_resp" class="form-label mt-3">Formateur Responsable :</label>
                            <select name="formateur_resp" class="form-select w-100" required>
                                <option value="Sarah Addem">Sarah Addem</option>
                                <option value="Ahmed Saidi">Ahmed Saidi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formateur_verificateur" class="form-label mt-3">Formateur Verificateur :</label>
                            <select  name="formateur_verificateur" class="form-select w-100" required>
                                <option value="Sarah Addem">Sarah Addem</option>
                                <option value="Ahmed Saidi">Ahmed Saidi</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="annee" class="form-label mt-3">Annee :</label>
                            <select name="annee" class="form-select w-100" required>
                                <option value="2025">2025 </option>
                                <option value="2024">2024  </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="semestre" class="form-label mt-3">Semestre :</label>
                            <select name="semestre" class="form-select w-100" required>
                                <option value="1er">1er </option>
                                <option value="2eme">2eme  </option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Affecter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        




    <script>
        const bell = document.getElementById("bell");
        const dashboard  = document.getElementById("dashboard");

        bell.addEventListener("click", () => {
            dashboard.classList.toggle("show-notifications");
        });
    </script>
</body>
</html>