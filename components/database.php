<?php

$localhost = 'hira.ovh:3306';
    $dbname = 'hira_bdd';
    $user = 'hira_user';
    $passwordDb = 'JeSuisDesignerUIUX';

    try {
        $pdo = new PDO("mysql:host=$localhost;dbname=$dbname", $user, $passwordDb);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Erreur de connexion : ' . $e->getMessage();
        exit;
    }