<?php 
    include '../connexion.php';

    session_start();

    if (!isset($_SESSION['logged'])) {
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
    <title>Filiers & Groupes & Modules</title>
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

        .form-select {
            width: 100px;
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

    <!--TABLE FILIERE-->
    <div class="row p-4 mt-4">
        <div class="col-12 border shadow-sm rounded-4  p-3"> 
            <h3 class="pb-3 ">Filieres </h3>
            <table class="table table-responsive table-hover table-bordered table-striped align-middle shadow-sm rounded-4 overflow-hidden">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Code Filiere</th>
                        <th>Nom Filiere</th>
                        <th>Niveau</th>
                        <th>Secteur </th>
                        <th>Type Formation</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr>
                        <td>DEV</td>
                        <td>Developpement Digitale</td>
                        <td>TS</td>
                        <td>sdfsdf</td>
                        <td>sdfsdf</td>
                    </tr>
                    <tr>
                        <td>AA</td>
                        <td>Asisstant Administratif</td>
                        <td>TS</td>
                        <td>sdfsdf</td>
                        <td>sdfsdf</td>
                    </tr>
                    <tr>
                        <td>INFO</td>
                        <td>Inforgraphie </td>
                        <td>TS</td>
                        <td>sdfsdf</td>
                        <td>sdfsdf</td>
                    </tr>
                    <tr>
                        <td>DEV</td>
                        <td>Developpement Digitale</td>
                        <td>TS</td>
                        <td>sdfsdf</td>
                        <td>sdfsdf</td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="float-end btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#ajouter_filiere_modal" >Ajouter Filiere</button>
        </div>
    </div>    

     <!-- MODAL for Filiere-->
    <div class="modal fade" id="ajouter_filiere_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter Filiere</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulaire  -->
                    <form method="POST">
                        <div class="mb-3 ">
                            <label for="titre_filiere" class="form-label mt-3">Titre Filiere:</label>
                            <input type="text" class="form-control form-control-lg" id="titre_filiere" name="titre_filiere" required >
                        </div>
                        <div class="mb-4">
                            <label for="code_filiere" class="form-label mt-3">Code Filiere :</label>
                            <input type="text" class="form-control form-control-lg" id="code_filiere" name="code_filiere" required>
                        </div>
                        <div class="mb-4">
                            <label for="niveau" class="form-label mt-3">Niveau :</label>
                            <select class="form-select w-100" required>
                                <option value="TS">TS</option>
                                <option value="T">T</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="secteur" class="form-label mt-3">Secteur :</label>
                            <input type="text" class="form-control form-control-lg" id="secteur" name="secteur" required>
                        </div>
                        <div class="mb-4">
                            <label for="type_formation" class="form-label mt-3">Type de Formation :</label>
                            <input type="text" class="form-control form-control-lg" id="type_formation" name="type_formation" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>



    <!--TABLE GROUPES-->
    <div class="row p-4 mt-4">
        <div class="col-12 border shadow-sm rounded-4  p-3"> 
            <h3 class="pb-3 float-start">Groupes </h3>
            <select class="form-select float-end ">
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
                <option value="2020">2020</option>
            </select>
            <table class="table table-hover table-bordered table-striped align-middle shadow-sm rounded-4 overflow-hidden">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Code Groupe</th>
                        <th>Filiere</th>
                        <th>Annee</th>
                        <th>Etudiants </th>
                        <th>Filiere Assoc</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr>
                        <td>101</td>
                        <td>DD</td>
                        <td>2025</td>
                        <td>25</td>
                        <td>DD</td>
                    </tr>
                    <tr>
                        <td>102</td>
                        <td>DD</td>
                        <td>2025</td>
                        <td>24</td>
                        <td>DD</td>
                    </tr>
                    <tr>
                        <td>201</td>
                        <td>DDOWFS</td>
                        <td>2025</td>
                        <td>25</td>
                        <td>DDOWFS</td>
                    </tr>
                    <tr>
                        <td>101</td>
                        <td>INFO</td>
                        <td>2025</td>
                        <td>25</td>
                        <td>INFO</td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="float-end btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#ajouter_groupe_modal" >Ajouter Groupe</button>
        </div>
    </div>  
    
    <!-- MODAL for Groupes-->
    <div class="modal fade" id="ajouter_groupe_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter Groupe</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulaire  -->
                    <form method="POST">
                        <div class="mb-3 ">
                            <label for="code_groupe" class="form-label mt-3">Code Groupe:</label>
                            <input type="text" class="form-control form-control-lg" id="code_groupe" name="code_groupe" required >
                        </div>
                        <div class="mb-4">
                            <label for="code_filiere" class="form-label mt-3">Code Filiere :</label>
                            <select class="form-select w-100" required>
                                <option value="DD">DD</option>
                                <option value="INFO">INFO</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="annee" class="form-label mt-3">Annee :</label>
                            <select class="form-select w-100" required>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>



     <!--TABLE MODULES-->
    <div class="row p-4 mt-4">
        <div class="col-12 border shadow-sm rounded-4  p-3"> 
            <h3 class="pb-3">Modules </h3>
            <table class="table table-hover table-bordered table-striped align-middle shadow-sm rounded-4 overflow-hidden">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Nom Module</th>
                        <th>Numero</th>
                        <th>Filiere</th>
                        <th>Masse Horraire </th>
                        <th>Filiere Assoc</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr>
                        <td>Dev Front-End</td>
                        <td>fsdf</td>
                        <td>DDOWFS</td>
                        <td>100H</td>
                        <td>DDOWFS</td>
                    </tr>
                    <tr>
                        <td>Dev Back-End</td>
                        <td>fsdf</td>
                        <td>DDOWFS</td>
                        <td>80H</td>
                        <td>DDOWFS</td>
                    </tr>
                    <tr>
                        <td>Soft Skills</td>
                        <td>fsdf</td>
                        <td>DDOWFS</td>
                        <td>30H</td>
                        <td>DDOWFS</td>
                    </tr>
                    <tr>
                        <td>Dev Front-End</td>
                        <td>fsdf</td>
                        <td>DDOWFS</td>
                        <td>55H</td>
                        <td>DDOWFS</td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="float-end btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#ajouter_module_modal" >Ajouter Module</button>
        </div>
    </div>

    <!-- MODAL for Modules-->
    <div class="modal fade" id="ajouter_module_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter Module</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulaire -->
                    <form method="POST">
                        <div class="mb-3 ">
                            <label for="nom_module" class="form-label mt-3">Nom Module :</label>
                            <input type="text" class="form-control form-control-lg" id="nom_module" name="nom_module" required >
                        </div>
                        <div class="mb-4">
                            <label for="module_numero" class="form-label mt-3">Numero :</label>
                            <input type="text" class="form-control form-control-lg" id="module_numero" name="module_numero" required >
                        </div>
                        <div class="mb-4">
                            <label for="filiere" class="form-label mt-3">Filiere :</label>
                            <select class="form-select w-100" required>
                                <option value="DD">DD</option>
                                <option value="DDOWSF">DDOWSF</option>
                                <option value="INFO">INFO</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="masse_horraire" class="form-label mt-3">Masse Horraire : (par heure)</label>
                            <input type="number" min="20" max="200" step="5" class="form-control form-control-lg" id="masse_horraire" name="masse_horraire" required >
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>



        
   


</body>
</html>