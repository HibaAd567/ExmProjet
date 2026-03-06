<?php
include '../connexion.php';
session_start();

if (!isset($_SESSION['logged']) || !in_array($_SESSION['role'], ['FORMATEUR_RESPONSABLE', 'FORMATEUR_VERIFICATEUR'])) {
    header("Location: ../login.php");
    exit;
}

$email = $_SESSION['email'];
$user_id = null;

if ($email) {
    $stmt = $pdo->prepare("SELECT id, nom, prenom FROM utilisateurs WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $nom = $user['nom'];
        $prenom = $user['prenom'];
        $user_id = $user['id'];
    }
}



if ($_SESSION['role'] === 'FORMATEUR_RESPONSABLE') {
    $sql = "
SELECT 
    attributions_verification.id AS verification_id,
    modules.intitule AS module,
    groupes.code_groupe AS groupe,
    attributions_verification.statut_verification,
    utilisateurs.nom AS formateur_responsable

    FROM attributions_verification
    JOIN attributions_module 
        ON attributions_verification.attribution_module_id = attributions_module.id
    JOIN modules 
        ON attributions_module.module_id = modules.id
    JOIN groupes 
        ON attributions_module.groupe_id = groupes.id
    LEFT JOIN utilisateurs
        ON attributions_module.formateur_responsable_id = utilisateurs.id
    WHERE attributions_module.formateur_responsable_id = :formateur_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['formateur_id' => $user_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sql = "
    SELECT 
        modules.intitule AS module,
        groupes.code_groupe AS groupe,
        attributions_verification.statut_verification,
        utilisateurs.nom AS formateur_responsable
    FROM attributions_verification
    JOIN attributions_module 
        ON attributions_verification.attribution_module_id = attributions_module.id
    JOIN modules 
        ON attributions_module.module_id = modules.id
    JOIN groupes 
        ON attributions_module.groupe_id = groupes.id
    LEFT JOIN utilisateurs
        ON attributions_module.formateur_responsable_id = utilisateurs.id
    ";

    $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Formateurs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
h1 { color:#0c0c0d; text-align:center; margin-bottom:30px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
h3 { color: rgba(71,10,128,1); font-size:25px; }
</style>
</head>
<body>

<div class="d-flex align-items-center gap-3">
    <img class="mt-1" src="../logo.jpg" alt="Mon logo" height="85px">
    <h3>Academia Flow</h3>
</div>

<h1>Gestion des modules</h1>
<div class="row p-4 mt-4">

<!--form responsable -->
<?php if($_SESSION['role'] === 'FORMATEUR_RESPONSABLE') : ?>

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
        <?php if($rows) { foreach($rows as $row) { ?>
            <tr>
                <td><?= $row['module'] ?></td>
                <td><?= $row['groupe'] ?></td>
                <td>
                    <?php
                    $statut = $row['statut_verification'] ?? 'CONFORME';
                    
                    if ($statut === 'NON_DEPOSE') echo '<span class="badge bg-danger">Non déposé</span>';
                    elseif ($statut === 'NON_CONFORME') echo '<span class="badge bg-warning text-dark">Anomalie</span>';
                    elseif ($statut === 'DEPOSE') echo '<span class="badge bg-primary">Déposé</span>';
                    else echo '<span class="badge bg-success">Validé</span>';
                    ?>
                </td>
                <td>
                    <?php 
                    $statut = $row['statut_verification'] ?? 'CONFORME';
                    
                    if ($statut === 'NON_DEPOSE') { ?>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#deposerModal" 
                        onclick="setDeposerInfo(<?= $row['verification_id'] ?>">Déposer</button>
                    <?php } elseif ($statut === 'NON_CONFORME') { ?>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#corrigerModal" 
                            onclick="setCorrigerInfo('<?= $row['module'] ?>','<?= $row['groupe'] ?>')">Voir / Corriger</button>
                    <?php } elseif ($statut === 'DEPOSE') { ?>
                        <button class="btn btn-info btn-sm" disabled>En attente vérification</button>
                    <?php } else { ?>
                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#voirModal" 
                            onclick="setVoirInfo('<?= $row['module'] ?>','<?= $row['groupe'] ?>')">Voir</button>
                    <?php } ?>
                </td>
            </tr>
        <?php }} ?>
        </tbody>
    </table>


    <!-- MODALES POUR FORMATEUR RESPONSABLE -->
    <div class="modal fade" id="deposerModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Déposer les fichiers</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
<form id="formDeposer"
      action="deposer_statut.php"
      method="POST"
      enctype="multipart/form-data">
                <div class="mb-3"><label>PV de notes</label><input type="file" name="pv_notes" class="form-control"></div>
                <div class="mb-3"><label>PV de présence</label><input type="file" name="pv_presence" class="form-control"></div>
                <div class="mb-3"><label>Feuilles de copies signées</label><input type="file" name="feuilles_copies" class="form-control"></div>
                <div class="mb-3"><label>Sujet/Épreuve signée et cachetée</label><input type="file" name="sujet_epreuve" class="form-control"></div>
                <input type="hidden" name="verification_id" id="verification_id">

            
          </div>
          <div class="modal-footer"><button type="submit" class="btn btn-primary" form="formDeposer">Enregistrer</button></div>
        </form>
      </div>
      </div>
    </div>

    <div class="modal fade" id="voirModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title">Voir les fichiers</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="mb-3"><label>PV de notes</label> <button class="btn btn-link">Télécharger</button></div>
            <div class="mb-3"><label>PV de présence</label> <button class="btn btn-link">Télécharger</button></div>
            <div class="mb-3"><label>Feuilles de copies signées</label> <button class="btn btn-link">Télécharger</button></div>
            <div class="mb-3"><label>Sujet/Épreuve signée et cachetée</label> <button class="btn btn-link">Télécharger</button></div>
          </div>
          <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button></div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="corrigerModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title">Correction / Anomalie détectée</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <h6>Anomalies détectées :</h6>
            <ul>
                <li>Anomalie 1</li>
                <li>Anomalie 2</li>
            </ul>
            <form id="formCorriger" enctype="multipart/form-data">
                <div class="mb-3"><label>Ajouter fichiers de correction</label><input type="file" name="correction[]" class="form-control" multiple></div>
            </form>
          </div>
          <div class="modal-footer"><button type="submit" class="btn btn-primary" form="formCorriger">Enregistrer les corrections</button></div>
        </div>
      </div>
    </div>



<!--form verificateur -->
<?php else: ?>

<table class="table table-hover table-bordered table-striped align-middle shadow-sm rounded-4 overflow-hidden">
    <thead class="table-primary text-center">
        <tr>
            <th>Module</th>
            <th>Groupe</th>
            <th>Formateur Responsable</th>
            <th>Statut du paquet</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody class="text-center">
    <?php if ($rows) { foreach ($rows as $row) { ?>
        <tr>
            <td><?= $row['module'] ?></td>
            <td><?= $row['groupe'] ?></td>
            <td><?= $row['formateur_responsable'] ?></td>



            <!-- Statut du paquet -->
            <td>
                <?php
                $statut = $row['statut_verification'] ?? 'CONFORME';
                if ($statut === 'NON_DEPOSE') {
                    echo '<span class="badge bg-danger">Non-déposé</span>';
                } elseif ($statut === 'DEPOSE') {
                    echo '<span class="badge bg-primary">Déposé</span>';
                } else {
                    echo '<span class="badge bg-success">Validé</span>';
                }
                ?>
            </td>


            
            <!-- Actions -->
            <td>
                <?php
                if ($statut === 'NON_DEPOSE') {
                    echo '<button class="btn btn-secondary btn-sm" disabled>Vérifier</button>';
                } elseif ($statut === 'DEPOSE') { ?>
                    <button class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#verifierModal"
                            onclick="setVerifierInfo('<?= $row['module'] ?>','<?= $row['groupe'] ?>','<?= $row['formateur_responsable'] ?>')">
                        Vérifier
                    </button>
                <?php } else { ?>
                    <button class="btn btn-secondary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#voirVerifierModal"
                            onclick="setVoirInfo('<?= $row['module'] ?>','<?= $row['groupe'] ?>','<?= $row['formateur_responsable'] ?>')">
                        Voir
                    </button>
                <?php } ?>
            </td>
        </tr>
    <?php }} ?>
    </tbody>
</table>

<!-- MODALES POUR FORMATEUR VERIFICATEUR -->

<!-- Modal Vérifier -->
<div class="modal fade" id="verifierModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Vérification du paquet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form>
          <table class="table table-bordered text-center">
            <thead>
              <tr>
                <th>Éléments à vérifier</th>
                <th>Choix</th>
                <th>Commentaire</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $questions = [
                "La somme des notes est correcte ?",
                "Le report des notes est correct ?",
                "Le PV de notes est présent ?",
                "Le PV de présence est présent ?",
                "Les copies sont signées ?",
                "Le sujet est signé et cacheté ?"
              ];
              foreach($questions as $i => $q): ?>
              <tr>
                <td><?= $q ?></td>
                <td>
                  <input type="radio" name="q<?= $i ?>" value="oui"> Oui
                  <input type="radio" name="q<?= $i ?>" value="non"> Non
                </td>
                <td><textarea class="form-control"></textarea></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <button class="btn btn-success">Valider</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Voir -->
<div class="modal fade" id="voirVerifierModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Paquet validé</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered mb-3">
          <tr><th>Module</th><td id="voirModule"></td></tr>
          <tr><th>Groupe</th><td id="voirGroupe"></td></tr>
          <tr><th>Formateur Responsable</th><td id="voirFormateur"></td></tr>
          <tr><th>Statut</th><td><span class="badge bg-success">Validé</span></td></tr>
        </table>

        <table class="table table-bordered text-center">
          <thead>
            <tr>
              <th>Éléments à vérifier</th>
              <th>Résultat</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($questions as $q): ?>
            <tr>
              <td><?= $q ?></td>
              <td><span class="badge bg-success">Oui</span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
function setDeposerInfo(id){
    document.getElementById('verification_id').value = id;
}
function setVoirInfo(module,groupe,formateur){ document.getElementById('voirModule').innerText = module; document.getElementById('voirGroupe').innerText = groupe; document.getElementById('voirFormateur').innerText = formateur;}
function setCorrigerInfo(module,groupe){}
function setVerifierInfo(module,groupe,formateur){}
</script>

</body>
</html>