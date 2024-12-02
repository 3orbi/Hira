<?php
session_start();

if (!isset($_SESSION['registration_success'])) {
    header('Location: /inscription');
    exit;
}

// Clear the session variable
unset($_SESSION['registration_success']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification en attente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="card-title mb-4">Vérification en attente</h2>
                        <div class="alert alert-info">
                            <p>Un email de vérification a été envoyé à votre adresse email.</p>
                            <p>Veuillez vérifier votre boîte de réception et cliquer sur le lien de vérification pour activer votre compte.</p>
                        </div>
                        <p class="text-muted">Si vous n'avez pas reçu l'email, vérifiez votre dossier spam.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>