<?php
// Définir le titre de la page et la page active pour la sidebar
$title = "Détails de l'album";
$currentPage = "album";

// Inclure les données dynamiques (exemple simulé)
include '../utils/database.php'; // Exemple de connexion à une base de données
$albumId = $_GET['id'] ?? 1; // Récupère l'ID de l'album (par défaut : 1)

// Simulation des données d'un album (remplacez par une requête SQL)
$album = [
    "title" => "Greatest Hits",
    "artist" => "John Doe",
    "cover" => "../public/placeholder.svg",
    "description" => "Une collection des meilleures chansons de John Doe.",
    "tracks" => [
        ["title" => "Track 1", "duration" => "3:45"],
        ["title" => "Track 2", "duration" => "4:12"],
        ["title" => "Track 3", "duration" => "5:01"],
        ["title" => "Track 4", "duration" => "2:58"],
    ],
];

// Mise en mémoire tampon pour capturer le contenu
ob_start();
?>

<div class="container">
    <!-- Section Album -->
    <div class="row align-items-center mb-4">
        <div class="col-md-4">
            <img src="<?php echo $album['cover']; ?>" class="img-fluid rounded" alt="Cover de l'album">
        </div>
        <div class="col-md-8">
            <h1><?php echo $album['title']; ?></h1>
            <p class="text-muted">Artiste : <?php echo $album['artist']; ?></p>
            <p><?php echo $album['description']; ?></p>
        </div>
    </div>

    <!-- Section Tracks -->
    <h2 class="mb-3">Liste des morceaux</h2>
    <ul class="list-group">
        <?php foreach ($album['tracks'] as $track): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center bg-dark text-white">
                <span><?php echo $track['title']; ?></span>
                <span class="badge bg-secondary"><?php echo $track['duration']; ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php
// Capturer le contenu
$content = ob_get_clean();

// Inclure le layout global
include '../components/layout.php';
