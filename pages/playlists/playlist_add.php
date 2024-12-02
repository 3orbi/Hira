<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    if (!empty($title)) {
        // Simuler l'ajout dans les données existantes
        $_SESSION['playlists'][] = [
            'id' => count($_SESSION['playlists'] ?? []) + 1,
            'title' => $title,
            'songs' => [],
        ];
    }
}
header('Location: /pages/playlists.php');
exit;
?>
