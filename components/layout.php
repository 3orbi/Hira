<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Hira'; ?></title>
    <link rel="stylesheet" href="/css/global.css">
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
    </style>
</head>
<body>


    <div class="d-flex" id="content">
        <?php include 'components/sidebar.php'; ?>
        <main class="main flex-grow-1">
            <?php echo $content ?? ''; ?>
        </main>
    </div>
    <?php include 'components/player.php'; ?>
    <script src="/public/js/player.js"></script>
    <script src="/public/js/navigation.js"></script>

</body>
</html>
