<?php
session_start();
require_once 'components/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user']['id']) || !isset($_POST['song_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté ou ID de chanson manquant']);
    exit;
}

$user_id = $_SESSION['user']['id'];
$song_id = $_POST['song_id'];

try {
    // Insérer dans l'historique
    $stmt = $pdo->prepare("INSERT INTO historique_lectures (utilisateur_id, chanson_id, date_lecture) VALUES (?, ?, NOW())");
    $stmt->execute([$user_id, $song_id]);

    // Récupérer les informations de la chanson
    $stmt = $pdo->prepare("
        SELECT c.id, c.titre, a.nom AS artiste
        FROM chansons c
        JOIN artistes a ON c.artiste_id = a.id
        WHERE c.id = ?
    ");
    $stmt->execute([$song_id]);
    $song = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'song' => $song]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'historique']);
}