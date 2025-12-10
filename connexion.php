<?php
$host = "mysql-hebaad.alwaysdata.net";
$user = "hebaad";
$pass = "root123/";
$dbname = "hebaad_db";

$dsn ="mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected !";
} catch (PDOException $e ) {
    die ("Connection failed : ". $e -> getMessage());
}


?>
