<?php
$title = 'Ajouts récents';
ob_start();
require 'components/database.php';
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Hira</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Ajouts récents</h1>
        <div class="row">
            <?php 
            $stmt = $pdo->query("
                SELECT c.id, c.titre, c.url_fichier, c.cree_le, c.img_url, a.nom AS artiste
                FROM chansons c
                JOIN artistes a ON c.artiste_id = a.id
                ORDER BY c.cree_le DESC
                LIMIT 20
            ");
            $recentSongs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $songs = [];
            foreach ($recentSongs as $index => $song): 
                $songs[] = [
                    'src' => htmlspecialchars($song['url_fichier']),
                    'title' => htmlspecialchars($song['titre']),
                    'artist' => htmlspecialchars($song['artiste']),
                    'cover' => htmlspecialchars($song['img_url']),
                ];
            ?>
                <div class="col-md-3 mb-4">
                    <div class="card bg-dark text-white position-relative" style="border-radius: 10px; overflow: hidden;">
                        <img src="<?= htmlspecialchars($song['img_url']); ?>" class="card-img-top" alt="<?= htmlspecialchars($song['titre']); ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($song['titre']); ?></h5>
                            <p class="card-text"><?= htmlspecialchars($song['artiste']); ?></p>
                            <p class="card-text"><small class="text-muted">Ajouté le <?= date('d/m/Y', strtotime($song['cree_le'])); ?></small></p>
                        </div>
                        <button 
                            class="btn bouton-lecture position-absolute play-song" 
                            data-index="<?= $index ?>"
                            onclick="playSong(<?= $index ?>)"
                            aria-label="Jouer <?= htmlspecialchars($song['titre']); ?> par <?= htmlspecialchars($song['artiste']); ?>">
                            <i class="bi bi-play-fill"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        const songs = <?= json_encode($songs, JSON_HEX_TAG); ?>;

        function sendSongsToPlayer(songs) {
            const iframe = document.getElementById('player-iframe');
            if (iframe) {
                iframe.onload = () => {
                    iframe.contentWindow.postMessage({ type: 'LOAD_SONGS', songs: songs }, '*');
                };
                if (iframe.contentWindow) {
                    iframe.contentWindow.postMessage({ type: 'LOAD_SONGS', songs: songs }, '*');
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
            }
        });

        window.addEventListener('load', () => {
            sendSongsToPlayer(songs);
        });
    </script>
</body>
</html>

<?php
$content = ob_get_clean();
include 'components/layout.php';
?>