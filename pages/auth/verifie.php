<?php
session_start();
require 'components/database.php';

$error = '';
$success = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Vérifier le token dans la base de données
    $stmt = $pdo->prepare('SELECT id, email_verifie FROM utilisateurs WHERE token_verification = :token');
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();
    
    if ($user) {
        if ($user['email_verifie'] == 1) {
            $error = 'Ce compte a déjà été vérifié.';
        } else {
            // Mettre à jour le statut de vérification et supprimer le token
            $stmt = $pdo->prepare('UPDATE utilisateurs SET email_verifie = 1, token_verification = NULL WHERE id = :id');
            $stmt->execute(['id' => $user['id']]);
            
            $success = 'Votre compte a été vérifié avec succès. Vous pouvez maintenant vous connecter.';
        }
    } else {
        $error = 'Token de vérification invalide ou expiré.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification du compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="card-title mb-4">Vérification du compte</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <a href="/connexion" class="btn btn-primary">Se connecter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>