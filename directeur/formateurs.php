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


    $stmt =$pdo -> prepare("select nom, prenom, email, role from utilisateurs where role like 'FORMATEUR%' ");
    $stmt -> execute();
    $formateurs = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    // form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(!empty($_POST['nom_form']) && !empty($_POST['prenom_form']) && !empty($_POST['email_form']) && !empty($_POST['password_form']) && !empty($_POST['role_formateur'])) {
            $nom_form = $_POST['nom_form'];
            $prenom_form = $_POST['prenom_form'];
            $email_form = $_POST['email_form'];
            $password_form = password_hash($_POST['password_form'], PASSWORD_DEFAULT);
            $role_formateur = $_POST['role_formateur'];

            $stmt = $pdo -> prepare("insert into utilisateurs (nom, prenom, email, mot_de_passe_hash, role) values (?, ?, ?, ?, ?)");
            $stmt -> execute([$nom_form, $prenom_form, $email_form, $password_form, $role_formateur]);
        }
    }
    





?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"><link href="loginn.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Formateurs</title>
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
                    <h3 class="pb-3">Formateurs </h3>
                    <div class="table-container">
                        <table class="table table-hover table-bordered table-striped align-middle shadow-sm rounded-4 overflow-hidden">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Nom Formateur</th>
                                    <th>Prenom Formateur</th>
                                    <th>email</th>
                                    <th>role </th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php if (!empty($formateurs)) : ?>
                                    <?php foreach($formateurs as $f) : ?>
                                        <tr>
                                            <td> <?= htmlspecialchars($f['nom']) ?> </td>
                                            <td> <?= htmlspecialchars($f['prenom']) ?> </td>
                                            <td> <?= htmlspecialchars($f['email']) ?> </td>
                                            <td> <?= htmlspecialchars($f['role']) ?> </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan='4'>Aucun Formateur trouve</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="float-end btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#ajouter_formateur" >Ajouter </button>
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
    <div class="modal fade" id="ajouter_formateur" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulaire  -->
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nom_form" class="form-label mt-3">Nom Formateur :</label>
                            <input type="text" class="form-control form-control-lg" id="nom_form" name="nom_form" required >
                        </div>
                        <div class="mb-4">
                            <label for="prenom_form" class="form-label mt-3">Prenom Formateur :</label>
                            <input type="text" class="form-control form-control-lg" id="prenom_form" name="prenom_form" required >
                        </div>
                        <div class="mb-4">
                            <label for="email_form" class="form-label mt-3">Email :</label>
                            <input type="email" class="form-control form-control-lg" id="email_form" name="email_form" required >
                        </div>
                        <div class="mb-4">
                            <label for="password_form" class="form-label mt-3">Password :</label>
                            <input type="password" class="form-control form-control-lg" id="password_form" name="password_form" required >
                        </div>
                        <div class="mb-4">
                            <label for="role_formateur" class="form-label mt-3">Role :</label>
                            <select name="role_formateur" class="form-select w-100" required>
                                <option value="FORMATEUR_RESPONSABLE ">Responsable </option>
                                <option value="FORMATEUR_VERIFICATEUR ">Verificateur </option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
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