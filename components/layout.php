<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Hira'; ?></title>
    <link rel="stylesheet" href="/public/css/global.css">
    <link rel="stylesheet" href="/public/css/player.css">
    <link rel="stylesheet" href="/public/css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .main {
            overflow-y: auto;
            height: calc(100vh - 70px);
        }
        #player-iframe {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 70px;
            border: none;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="content">
        <?php require 'components/database.php'; ?>
        <?php include 'components/sidebar.php'; ?>

        <main class="main flex-grow-1">
            <?php echo $content ?? ''; ?>
        </main>
    </div>
    
    <iframe id="player-iframe" src="/components/player_iframe.php" title="Lecteur de musique"></iframe>

    <script src="/public/js/navigation.js"></script>
    <script>
    // Fonction pour envoyer les chansons à l'iframe du lecteur
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

    // Fonction pour jouer une chanson spécifique
    function playSong(index) {
        const iframe = document.getElementById('player-iframe');
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.postMessage({ type: 'PLAY_SONG', songIndex: index }, '*');
        }
    }

    // Écouter les messages de l'iframe du lecteur
    window.addEventListener('message', function(event) {
        if (event.data.type === 'PLAYER_STATE') {
            console.log('État du lecteur:', event.data);
            // Vous pouvez ajouter ici du code pour mettre à jour l'interface utilisateur
            // en fonction de l'état du lecteur si nécessaire
        }
    });

    // Envoyer la liste des chansons à l'iframe du lecteur au chargement de la page
    window.addEventListener('load', () => {
        if (typeof songs !== 'undefined') {
            sendSongsToPlayer(songs);
        }
    });
</script>
</body>
</html>