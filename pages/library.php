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

// Préparer les données des chansons pour le lecteur
$songs = [];
$defaultImageUrl = 'public/img/default-song.jpg';
foreach ($chansons as $index => $chanson) {
    $songs[] = [
        'src' => htmlspecialchars($chanson['url_fichier']),
        'title' => htmlspecialchars($chanson['titre']),
        'artist' => htmlspecialchars($chanson['artiste']),
        'cover' => htmlspecialchars($chanson['img_url'] ?? $defaultImageUrl),
    ];
}
?>

<style>
    .bouton-lecture {
        bottom: 10px;
        right: 10px;
        width: 30px;
        height: 30px;
        background-color: black; 
        color: white;
        border: none;
        border-radius: 50%; 
        display: flex;
        justify-content: center;
        align-items: center;
        transition: background-color 0.3s ease; 
        position: absolute;
    }

    .bouton-lecture:hover {
        background-color: red; 
        color: white;
        transform: scale(1.1); 
        box-shadow: 0 4px 10px rgba(255, 0, 0, 0.5); 
    }
</style>

<div class="container mt-5">
    <h1 class="mb-4">Bibliothèque</h1>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="artistes-tab" data-bs-toggle="tab" data-bs-target="#artistes" type="button" role="tab" aria-controls="artistes" aria-selected="true">Artistes</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="albums-tab" data-bs-toggle="tab" data-bs-target="#albums" type="button" role="tab" aria-controls="albums" aria-selected="false">Albums</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="chansons-tab" data-bs-toggle="tab" data-bs-target="#chansons" type="button" role="tab" aria-controls="chansons" aria-selected="false">Chansons</button>
        </li>
    </ul>

    <div class="tab-content mt-3" id="myTabContent">
        <!-- Onglet Artistes -->
        <div class="tab-pane fade show active" id="artistes" role="tabpanel" aria-labelledby="artistes-tab">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($artistes as $artiste): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($artiste['nom']); ?></h5>
                                <a href="/artist?id=<?php echo $artiste['id']; ?>" class="btn btn-primary">Voir les détails</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Onglet Albums -->
        <div class="tab-pane fade" id="albums" role="tabpanel" aria-labelledby="albums-tab">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($albums as $album): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($album['titre']); ?></h5>
                                <p class="card-text">par <?php echo htmlspecialchars($album['artiste']); ?></p>
                                <a href="/album?id=<?php echo $album['id']; ?>" class="btn btn-primary">Voir les chansons</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Onglet Chansons -->
        <div class="tab-pane fade" id="chansons" role="tabpanel" aria-labelledby="chansons-tab">
            <div class="list-group">
                <?php foreach ($chansons as $index => $chanson): ?>
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center position-relative">
                        <div>
                            <h5 class="mb-1"><?php echo htmlspecialchars($chanson['titre']); ?></h5>
                            <p class="mb-1">par <?php echo htmlspecialchars($chanson['artiste']); ?></p>
                            <small>Album : <?php echo htmlspecialchars($chanson['album'] ?? 'Aucun'); ?></small>
                        </div>
                        <button 
                            class="btn bouton-lecture play-song" 
                            data-index="<?= $index ?>"
                            onclick="playSong(<?= $index ?>)"
                            aria-label="Jouer <?= htmlspecialchars($chanson['titre']); ?> par <?= htmlspecialchars($chanson['artiste']); ?>">
                            <i class="bi bi-play-fill"></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const songs = <?= json_encode($songs, JSON_HEX_TAG); ?>;

    function sendSongsToPlayer(songs) {
        const iframe = document.getElementById('player-iframe');
        if (iframe) {
            const sendMessage = () => {
                iframe.contentWindow.postMessage({ type: 'LOAD_SONGS', songs: songs }, '*');
            };

            if (iframe.contentDocument.readyState === 'complete') {
                sendMessage();
            } else {
                iframe.onload = sendMessage;
            }
        }
    }

    function playSong(index) {
        const iframe = document.getElementById('player-iframe');
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.postMessage({ type: 'PLAY_SONG', songIndex: index }, '*');
        }
    }

    document.querySelectorAll('.play-song').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const index = button.getAttribute('data-index');
            playSong(parseInt(index, 10));
        });
    });

    window.addEventListener('message', function(event) {
        if (event.data.type === 'PLAYER_STATE') {
            console.log('État du lecteur:', event.data);
            // Vous pouvez mettre à jour l'interface utilisateur ici si nécessaire
        }
    });

    // Envoyer la liste des chansons à l'iframe du lecteur au chargement de la page
    window.addEventListener('DOMContentLoaded', () => {
        sendSongsToPlayer(songs);
    });
</script>

<?php
$content = ob_get_clean();
include $_SERVER['DOCUMENT_ROOT'] . '/components/layout.php';
?>