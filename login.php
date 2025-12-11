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
    <link href="loginn.css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="logo.jpg" alt="Mon logo" height="90px">
            <h3>Academia Flow</h3>
        </div>
    </div>

    <div class="form-container">
        <form class="form" method="POST">
            <p>Start your journey</p>
            <h1>Sign Up to Academia Flow</h1>
            <label for="mail">Email:</label>
            <input type="text" id="email" name="email" placeholder="example@gmail.com">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password">

           <button type="submit" value="envoyer">Se connecter</button>

        </form>
    </div>
</body>

</html>