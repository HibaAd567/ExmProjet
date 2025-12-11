<?php 
    include 'connexion.php';

    session_start();

    if (!isset($_SESSION['logged'])) {
        header("Location: login.php");
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
        i {
            color: black;
        }

        i:hover{
            color: lightblue;
        }

    </style>
</head>
<body>
    <div class="navbar navbar-expand-lg p-3">
        <div class="container-fluid">
            <!-- Left side -->
            <div class="d-flex align-items-center gap-3">
                <img class="mt-1" src="logo.jpg" alt="Mon logo" height="90px">
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
                        <span class="border border-primary rounded-4 p-2"> 
                            <?php echo "Mr.". htmlspecialchars($nom); ?>  
                        </span> 
                    </div>
                </li>
            </ul>

        </div>
        
        
        <div >
            
            
            
        </div>
            
  
        
    </div>

    
</body>

</html>