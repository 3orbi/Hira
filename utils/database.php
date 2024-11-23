<?php

/* Ici, nous définissons les informations de connexion */
$host = "localhost";
$dbname = "hira_bdd";
$username = "root";
$password = "root";


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (Exception $e) {
    echo $e->getMessage();
}
