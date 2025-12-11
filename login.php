<?php 
    include 'connexion.php';
    
    session_start();

    if(isset($_POST['email']) && isset($_POST['password'])) {
        if($_POST['email'] === "AliAhmed@academia.com" && $_POST['password'] === "ali123/") {
            $_SESSION['logged'] = true;
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Invalid email or password";
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
</head>
<body>
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

    <div class="form-container ">
        <form class="form shadow-lg rounded-4 p-5" method="POST">
            <p class="mt-3 ">Start your journey</p>
            <h1 class="mt-3 " >Sign Up to Academia Flow</h1>
            <label class="form-label " for="mail">Email:</label>
            <input class="form-control w-100 " type="text" id="email" name="email" placeholder="example@gmail.com">

            <label class="form-label" for="password">Password:</label>
            <input class="form-control w-100" type="password" id="password" name="password">

           <button type="submit" value="envoyer">Se connecter</button>

        </form>
    </div>
</body>

</html>