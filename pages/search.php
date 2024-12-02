<?php
$title = 'Recherche de Chansons';

ob_start();
session_start();

// Connexion à la base de données
require 'components/database.php';

// Récupérer le terme de recherche depuis la requête GET
$searchTerm = $_GET['search'] ?? '';

// Initialisation de la variable pour les résultats
$chansons = [];

if ($searchTerm) {
    // Préparer la requête SQL uniquement si un terme de recherche est fourni
    $sql = '
        SELECT c.*, ar.nom AS artiste, al.titre AS album
        FROM chansons c
        JOIN artistes ar ON c.artiste_id = ar.id
        LEFT JOIN albums al ON c.album_id = al.id
        WHERE c.titre LIKE :term OR ar.nom LIKE :term
        ORDER BY c.titre ASC
    ';

    $params = ['term' => '%' . $searchTerm . '%'];

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $chansons = $stmt->fetchAll();
}
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<div class="container mt-5">
    <h1>Recherche de Musique</h1>

    <!-- Formulaire de recherche -->
    <form method="get" class="mt-4 mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher une chanson ou un artiste" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button class="btn btn-primary" type="submit">Rechercher</button>
        </div>
    </form>

    <!-- Résultats de recherche -->
    <h3 class="mt-5">Résultats</h3>
    <ul class="list-group">
        <?php if ($searchTerm && !empty($chansons)): ?>
            <?php foreach ($chansons as $chanson): ?>
                <li class="list-group-item">
                    <strong><?php echo htmlspecialchars($chanson['titre']); ?></strong> 
                    par <?php echo htmlspecialchars($chanson['artiste']); ?>
                    (Album : <?php echo htmlspecialchars($chanson['album'] ?? 'Aucun'); ?>)
                    <button class="btn btn-sm btn-primary float-end">Lire</button>
                </li>
            <?php endforeach; ?>
        <?php elseif ($searchTerm): ?>
            <li class="list-group-item">Aucune chanson trouvée.</li>
        <?php else: ?>
            
        <?php endif; ?>
    </ul>
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

<?php
$content = ob_get_clean();
include $_SERVER['DOCUMENT_ROOT'] . '/components/layout.php';
?>