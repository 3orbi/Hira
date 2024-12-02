<?php
$title = 'Playlists';
include $_SERVER['DOCUMENT_ROOT'] . '/lib/playlists.php';
ob_start();
?>
<div class="container mt-5">
    <h1>Mes Playlists</h1>
    <p>Gérez vos playlists et ajoutez vos chansons préférées.</p>

    <!-- Liste des Playlists -->
    <div class="row">
        <?php foreach ($playlists as $playlist): ?>
            <div class="col-md-6">
                <div class="card mb-3 bg-dark text-white">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($playlist['title']); ?></h5>
                        <p class="card-text"><?php echo count($playlist['songs']); ?> chansons</p>
                        <a href="/pages/playlist.php?id=<?php echo $playlist['id']; ?>" class="btn btn-light">Voir Playlist</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Formulaire pour Ajouter une Playlist -->
    <div class="mt-5">
        <h3>Créer une Nouvelle Playlist</h3>
        <form method="POST" action="/pages/playlist_add.php">
            <div class="mb-3">
                <label for="title" class="form-label">Nom de la Playlist</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
include $_SERVER['DOCUMENT_ROOT'] . '/components/layout.php';
?>
