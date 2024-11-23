<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chargement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: black;
            color: white;
        }
        .loading-text {
            transition: opacity 1s, transform 1s;
        }
    </style>
</head>
<body>
<div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
    <h1 id="loadingText" class="loading-text">Chargement...</h1>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    setTimeout(() => {
        const loadingText = document.getElementById('loadingText');
        loadingText.style.opacity = '0';
        loadingText.style.transform = 'translateX(-100px)';

        setTimeout(() => {
            window.location.href = './pages/index.php';
        }, 1000);
    }, 2000);
</script>
</body>
</html>