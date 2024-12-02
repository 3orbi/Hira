<?php
$title = 'Bibliothèque';

ob_start();

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: /connexion');
    exit;
}

// Connexion à la base de données
require 'components/database.php';

// Récupérer les artistes
$artistes = $pdo->query('SELECT * FROM artistes ORDER BY nom ASC')->fetchAll();

// Récupérer les albums
$albums = $pdo->query('
    SELECT al.*, ar.nom AS artiste
    FROM albums al
    JOIN artistes ar ON al.artiste_id = ar.id
    ORDER BY al.titre ASC
')->fetchAll();

// Récupérer les chansons
$chansons = $pdo->query('
    SELECT c.*, ar.nom AS artiste, al.titre AS album
    FROM chansons c
    JOIN artistes ar ON c.artiste_id = ar.id
    LEFT JOIN albums al ON c.album_id = al.id
    ORDER BY c.titre ASC
')->fetchAll();
?>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<div class="container mt-5">
    <h1>Bibliothèque</h1>

    <!-- Liste des artistes -->
    <h3 class="mt-5">Artistes</h3>
    <ul class="list-group">
        <?php foreach ($artistes as $artiste): ?>
            <li class="list-group-item">
                <a href="/artist?id=<?php echo $artiste['id']; ?>">
                    <?php echo htmlspecialchars($artiste['nom']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Liste des albums -->
    <h3 class="mt-5">Albums</h3>
    <ul class="list-group">
        <?php foreach ($albums as $album): ?>
            <li class="list-group-item">
                <strong><?php echo htmlspecialchars($album['titre']); ?></strong> 
                par <?php echo htmlspecialchars($album['artiste']); ?>
                <a href="/album.php?id=<?php echo $album['id']; ?>" class="float-end">Voir les chansons</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Liste des chansons -->
    <h3 class="mt-5">Chansons</h3>
    <ul class="list-group">
        <?php foreach ($chansons as $chanson): ?>
            <li class="list-group-item">
                <strong><?php echo htmlspecialchars($chanson['titre']); ?></strong> 
                par <?php echo htmlspecialchars($chanson['artiste']); ?>
                (Album : <?php echo htmlspecialchars($chanson['album'] ?? 'Aucun'); ?>)
                <button class="btn btn-sm btn-primary float-end">Lire</button>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php
$content = ob_get_clean();
include $_SERVER['DOCUMENT_ROOT'] . '/components/layout.php';
?>
