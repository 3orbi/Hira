<?php

$localhost = 'localhost';
$dbname = 'hira_bdd';
$user = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$localhost;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}


