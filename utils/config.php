<?php
// Détecte automatiquement la base URL selon l'environnement (localhost, MAMP, etc.)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$projectRoot = '/'; // Changez si votre projet est dans un sous-dossier, ex : '/hira/'

define('BASE_URL', $protocol . '://' . $host . $projectRoot);
?>
