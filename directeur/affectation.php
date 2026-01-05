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


    $stmt = $pdo -> prepare("
        select g.code_groupe as groupe, m.intitule as module, ur.nom as formateur_responsable, uv.nom as formateur_verificateur, am.annee as annee, am.semestre as semestre
        from attributions_module am
        join groupes g on g.id = am.groupe_id
        join modules m on m.id = am.module_id
        join utilisateurs ur on ur.id = am.formateur_responsable_id
        left join attributions_verification av on av.attribution_module_id = am.id
        left join utilisateurs uv on uv.id = av.formateur_verificateur_id;
    ");
    $stmt -> execute();
    $tabAff = $stmt -> fetchAll(PDO::FETCH_ASSOC);


    // code filiere
    $stmt = $pdo -> prepare("select code_filiere from filieres");
    $stmt -> execute();
    $code_filieres = $stmt -> fetchAll(PDO::FETCH_ASSOC);


    //groupes by filiere
    if (isset($_GET['ajax']) && $_GET['ajax'] === 'groupes') {

        $filiere = trim($_GET['filiere'] ?? '');

        if($filiere === '') {
            echo json_encode([]);
            exit;
        }

        $stmt = $pdo -> prepare("
            select g.code_groupe from groupes g join filieres f on f.id = g.filiere_id
            where trim(f.code_filiere) = ?"
        );
        $stmt -> execute([$filiere]);

        echo json_encode($stmt -> fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    // modules by groupe
    if (isset($_GET['ajax']) && $_GET['ajax'] === 'modules') {

        $groupe = trim($_GET['groupe'] ?? '');

        if ($groupe === '') {
            echo json_encode([]);
            exit;
        }

        $stmt = $pdo->prepare("
            select m.intitule from modules m
            join attributions_module am on am.module_id = m.id
            join groupes g on g.id = am.groupe_id
            where g.code_groupe = ?"
        );
        $stmt->execute([$groupe]);

        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    // fromateur resposable
    $stmt = $pdo -> prepare("select nom, prenom from utilisateurs where role = 'FORMATEUR_RESPONSABLE' ");
    $stmt -> execute();
    $formateurResp = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    // fromateur verificateur
    $stmt = $pdo -> prepare("select nom, prenom from utilisateurs where role = 'FORMATEUR_VERIFICATEUR' ");
    $stmt -> execute();
    $formateurVerf = $stmt -> fetchAll(PDO::FETCH_ASSOC);


    // insert form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['filiere']) && !empty($_POST['groupe']) && !empty($_POST['module']) && !empty($_POST['formateur_resp']) && !empty($_POST['formateur_verf']) &&  !empty($_POST['annee']) && !empty($_POST['semestre']) ) {
            $groupe = trim($_POST['groupe']);
            $module = trim($_POST['module']);
            $formateur_resp = trim($_POST['formateur_resp']);
            $formateur_verf = trim($_POST['formateur_verf']);
            $annee = trim($_POST['annee']);
            $semestre = trim($_POST['semestre']);

            // groupe id
            $stmt = $pdo->prepare("select id from groupes where code_groupe  = ?");
            $stmt->execute([$groupe]);
            $groupe_id = $stmt->fetchColumn();

            // module id
            $stmt = $pdo->prepare("select id from modules where intitule = ?");
            $stmt->execute([$module]);
            $module_id = $stmt->fetchColumn();

            // formateur Resp id
            $stmt = $pdo->prepare("select id from utilisateurs where concat(nom, ' ', prenom) = ?");
            $stmt->execute([$formateur_resp]);
            $formateurResp_id = $stmt->fetchColumn();

            // insert formateur responsable
            $stmt = $pdo -> prepare("insert into attributions_module (module_id, groupe_id, formateur_responsable_id, annee, semestre) values (?, ?, ?, ?, ?)");
            $stmt -> execute([$module_id, $groupe_id, $formateurResp_id, $annee, $semestre]);

            $attribution_id  = $pdo -> lastInsertId();

            $stmt = $pdo->prepare("select id from utilisateurs where concat(nom, ' ', prenom) = ?");
            $stmt->execute([$formateur_verf]);
            $formateurVerf_id = $stmt->fetchColumn();

            // insert formateur verificateur
            $stmt = $pdo -> prepare("insert into attributions_verification (attribution_module_id, formateur_verificateur_id, date_affectation, statut_verification) values (?, ?, now(), 'NON_DEPOSE')");
            $stmt -> execute([$attribution_id, $formateurVerf_id]);
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
                    <div class="table-container">
                        <table class="table table-hover table-bordered table-striped align-middle shadow-sm rounded-4 overflow-hidden">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Groupe</th>
                                    <th>Module</th>
                                    <th>Formateur Responsable</th>
                                    <th>Formateur Verificateur</th>
                                    <th>Annee</th>
                                    <th>Semestre</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php if(!empty($tabAff)) : ?>
                                    <?php foreach ($tabAff as $t) : ?>
                                        <tr>
                                            <td> <?= htmlspecialchars($t['groupe']) ?></td>
                                            <td> <?= htmlspecialchars($t['module']) ?></td>
                                            <td> <?= htmlspecialchars($t['formateur_responsable']) ?></td>
                                            <td> <?= htmlspecialchars($t['formateur_verificateur']) ?></td>
                                            <td> <?= htmlspecialchars($t['annee']) ?></td>
                                            <td> <?= htmlspecialchars($t['semestre']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan='6'>Aucun data Trouve</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
                    <form method="POST" >
                        <div class="mb-3">
                            <label for="filiere" class="form-label mt-3">Filiere :</label>
                            <select name="filiere" id="filiereSelect" class="form-select w-100" required>
                                 <?php if(!empty($code_filieres)) : ?> 
                                    <?php foreach($code_filieres as $c)  : ?>  
                                        <option value="<?= htmlspecialchars($c['code_filiere']) ?>">
                                            <?= htmlspecialchars($c['code_filiere']) ?>
                                        </option>
                                    <?php endforeach; ?>  
                                <?php else : ?>
                                        <option disabled>Aucun Filiere trouve</option>
                                <?php endif; ?> 
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="groupe"  class="form-label mt-3">Groupe :</label>
                            <select name="groupe" id="groupeSelect" class="form-select w-100" required >
                                <option value="">-- Choisir groupe --</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="module"  class="form-label mt-3">Module :</label>
                            <select name="module" id="moduleSelect" class="form-select w-100" required>
                                <option value="">-- Choisir module --</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="formateur_resp" class="form-label mt-3">Formateur Responsable :</label>
                            <select name="formateur_resp" class="form-select w-100" required>
                                <?php if(!empty($formateurResp)) : ?>
                                    <?php foreach($formateurResp as $fr) : ?>
                                        <option value="<?= htmlspecialchars($fr['nom'] . ' ' . $fr['prenom']) ?>">
                                            <?= htmlspecialchars($fr['nom'] . ' ' . $fr['prenom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option disabled>Aucun Formateur Responsable trouve</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formateur_verf" class="form-label mt-3">Formateur Verificateur :</label>
                            <select  name="formateur_verf" class="form-select w-100" required>
                                <?php if(!empty($formateurVerf)) : ?>
                                    <?php foreach($formateurVerf as $fv) : ?>
                                        <option value="<?= htmlspecialchars($fv['nom'] . ' ' . $fv['prenom']) ?>">
                                            <?= htmlspecialchars($fv['nom'] . ' ' . $fv['prenom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option disabled>Aucun Formateur Verificateur trouve</option>
                                <?php endif; ?>
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
                                <option value="1">1er </option>
                                <option value="2">2eme  </option>
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

        
        document.getElementById("filiereSelect").addEventListener("change", function () {
            
            const filiere = this.value.trim();
            const groupeSelect = document.getElementById("groupeSelect");

            if(!filiere) {
                groupeSelect.innerHTML = '<option value="">-- Choisir groupe --</option>';
                return;
            }

            fetch(
                window.location.pathname + "?ajax=groupes&filiere=" + encodeURIComponent(filiere)
            )
            .then(res => res.json())
            .then(data => {
                groupeSelect.innerHTML = '<option value="">-- Choisir groupe --</option>';

                if(data.length === 0) {
                    groupeSelect.innerHTML += '<option disabled>Aucun groupe</option>';
                    return;
                }

                data.forEach(g => {
                    const opt = document.createElement("option");
                    opt.value = g.code_groupe;
                    opt.textContent = g.code_groupe;
                    groupeSelect.appendChild(opt);
                });

            });

        });


        document.getElementById("groupeSelect").addEventListener("change", function () {

            const groupe = this.value.trim();
            const moduleSelect = document.getElementById("moduleSelect");

            if (!groupe) {
                moduleSelect.innerHTML = '<option value="">-- Choisir module --</option>';
                return;
            }

            fetch(
                window.location.pathname + "?ajax=modules&groupe=" + encodeURIComponent(groupe)
            )
            .then(res => res.json())
            .then(data => {

                moduleSelect.innerHTML = '<option value="">-- Choisir module --</option>';

                if (data.length === 0) {
                    moduleSelect.innerHTML += '<option disabled>Aucun module</option>';
                    return;
                }

                data.forEach(m => {
                    const opt = document.createElement("option");
                    opt.value = m.intitule;
                    opt.textContent = m.intitule;
                    moduleSelect.appendChild(opt);
                });

            });
        });

    </script>
    
</body>
</html>