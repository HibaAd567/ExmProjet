<?php
include 'connexion.php';
session_start();

if(isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if($email === "AliAhmed@academia.com" && $password === "ali123/") {
        $_SESSION['logged'] = true;
        $_SESSION['role'] = 'DIRECTEUR';
        $_SESSION['email'] = $email;
        header("Location: directeur/dashboard.php");
        exit;
    } elseif($email === "formateur1@academia.com" && $password === "form123/") {
        $_SESSION['logged'] = true;
        $_SESSION['role'] = 'FORMATEUR_RESPONSABLE';
        $_SESSION['email'] = $email;
        header("Location: formateurResp/dashboard.php");
        exit;
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia Flow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"><link href="loginn.css" rel="stylesheet">
    <style>
        .nav {
            display: flex;             /* make ul a flex container */
            align-items: center;      /* vertically center children */
            gap: 10px;                /* optional space between items */
        }

        h3 {
            color: rgb(72, 9, 130);
        }

        button {
            background-color: #4da6ac;
            color: white;
            border: none;
            height: 38px;
        }

        input.form-control {
            border: 1px solid rgb(89, 89, 205);
        }


    </style>
</head>
<body>
    <!-- Nav -->
    <div class="navbar">
        <div class="container-fluid">
            <ul class="nav">
                <li class="nav-item mt-1">
                    <img src="logo.jpg" alt="Mon logo" height="90px">
                </li>
                <li class="nav-item ">
                    <h3>Academia Flow</h3>
                </li>
            </ul>
        </div>
    </div>

    <!-- Form -->
    <div class="form-container d-flex justify-content-center align-items-center mt-4">
        <form class="form-box shadow-lg rounded-4 p-5" method="POST">
            <p class="mt-3 text-center">Start your journey</p>
            <h2 class=" text-center" >Sign Up to Academia Flow</h2>

            <div class="mb-3 ">
                <label for="email" class="form-label mt-3">Email address</label>
                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="example@gmail.com" required >
            </div>
            <div class="mb-4">
                <label for="password" class="form-label mt-3">Password</label>
                <input type="password" class="form-control form-control-lg" id="password" name="password" required>
            </div>
            
            <button type="submit" class="mt-4 rounded w-100 ">Se Connecter</button>
            
        </form>
    </div>


</body>
</html>