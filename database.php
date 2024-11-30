<?php

/* Ici, nous définissons les informations de connexion */
$host = "localhost";
$dbname = "hira";
$username = "root";
$password = "root";



if(empty($host)){
    throw new Exception('Hostname non Valide')
}
if(empty($dbname)){
    throw new Exception('dbanme non Valide')
}
if(empty($username)){
    throw new Exception('username non Valide')
}
if(empty($password)){
    throw new Exception('password non Valide')
}



/* Création d'une instance PDO */
/* https://www.php.net/manual/fr/pdo.connections.php */
try {
    /* Connexion à la base de données */
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    /* Si tout se passe bien, je n'affiche rien */
} catch (Exception $e) {
    /* Si la connexion échoue j'affiche un message d'erreur */
    throw new Exception('Error 102');
}