<?php
$title = 'Accueil';
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
        <div class="text-center">
            <h1 class="fw-bold">Bienvenue  
                <span>
                    <?php 
                        if (isset($_SESSION['user'])) {
                            echo htmlspecialchars($_SESSION['user']['nom']);
                        } else {
                            echo 'sur Hira';
                        }
                    ?>
                </span>
            </h1>
            <h4 class="text-muted fw-bold">Votre musique, où que vous soyez.</h4>
        </div>

        <div class="container-fluid px-0">
            <div class="banner-container position-relative overflow-hidden">
                <div class="banner">
                    <img src="public/img/bdlmzeninth.jpg" alt="Promotion" class="img-fluid banner-image w-100" style="border-radius: 10px;">
                    <div class="banner-content position-absolute text-white">
                        <h2 class="artist-name">La musique</h2>
                        <h3 class="album-title fw-bold">BDLM vol1 </h3>
                        <p><button class="btn bouton-lecture position-absolute"> <i class="bi bi-play-fill"></i> </button></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap justify-content-start mt-4">
        <?php 
        $stmt = $pdo->query("
            SELECT c.id, c.titre, c.url_fichier, c.cree_le, a.nom AS artiste
            FROM chansons c
            JOIN artistes a ON c.artiste_id = a.id
            ORDER BY c.cree_le DESC
            LIMIT 12
        ");
        $latestSongs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $songs = [];
        foreach ($latestSongs as $song) {
            $songs[] = [
                'src' => htmlspecialchars($song['url_fichier']),
                'title' => htmlspecialchars($song['titre']),
                'artist' => htmlspecialchars($song['artiste']),
                'cover' => 'public/img/default-song.jpg'
            ];
        }
        ?>

        <!-- Génération des cartes -->
        <?php foreach ($latestSongs as $index => $song): ?>
            <div class="card bg-dark text-white m-2 position-relative" style="width: 15rem; border-radius: 10px; overflow: hidden;">
                <img src="public/img/default-song.jpg" class="card-img-top" alt="<?= htmlspecialchars($song['titre']); ?>" style="height: 12rem; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($song['titre']); ?></h5>
                    <p class="card-text"><?= htmlspecialchars($song['artiste']); ?></p>
                </div>
                <button 
                    class="btn bouton-lecture position-absolute play-song" 
                    data-index="<?= $index ?>"
                    onclick="playSong(<?= $index ?>)"
                    aria-label="Jouer <?= htmlspecialchars($song['titre']); ?> par <?= htmlspecialchars($song['artiste']); ?>">
                    <i class="bi bi-play-fill"></i>
                </button>
            </div>
        <?php endforeach; ?>
        </div>
    </div>

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
</body>
</html>
<?php
$content = ob_get_clean();
include 'components/layout.php';
?>