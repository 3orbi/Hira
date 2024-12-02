<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/lib/playlists.php';

// Récupérer l'ID de la Playlist
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$playlist = array_filter($playlists, fn($p) => $p['id'] === $id);
$playlist = reset($playlist); // Récupère le premier élément

if (!$playlist) {
    header('Location: /pages/playlists.php');
    exit;
}

$title = $playlist['title'];
ob_start();
?>
<div class="c<ntainer mt-5">
    <h1><?php echo htmlspecialchars($playlist['title']); ?></h1>
    <p><?php echo count($playlist['songs']); ?> chansons</p>

    <!-- Liste des Chansons -->
    <ul class="list-group">
        <?php foreach ($playlist['songs'] as $song): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo htmlspecialchars($song['title']) . ' - ' . htmlspecialchars($song['artist']); ?>
                <a href="#" class="btn btn-danger btn-sm">Supprimer</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Formulaire pour Ajouter une Chanson -->
    <div class="mt-4">
        <h3>Ajouter une Chanson</h3>
        <form method="POST" action="/pages/song_add.php">
            <input type="hidden" name="playlist_id" value="<?php echo $playlist['id']; ?>">
            <div class="mb-3">
                <label for="song-title" class="form-label">Titre</label>
                <input type="text" id="song-title" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="artist" class="form-label">Artiste</label>
                <input type="text" id="artist" name="artist" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
include $_SERVER['DOCUMENT_ROOT'] . '/components/layout.php';
?>
