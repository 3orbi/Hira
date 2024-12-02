<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecteur de musique</title>
    <link rel="stylesheet" href="/public/css/player.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>

        .player-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background-color: #222;
            height: 60px;
            width: calc(100% - 245px) /* Ajustement de la largeur */
            position: fixed;
            bottom: 0;
            left: 245px; /* Ajustement de la position */
        }
    </style>
</head>
<body>
    <?php include 'player.php'; ?>
</body>
</html>