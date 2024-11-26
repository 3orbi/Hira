<?php
session_start();

// Redirection si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: /');
    exit;
}

// Gestion de la soumission du formulaire
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        // Exemple de vérification d'utilisateur (remplacer par votre logique avec une base de données)
        $fakeUser = [
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ];

        if ($email === $fakeUser['email'] && password_verify($password, $fakeUser['password'])) {
            $_SESSION['user_id'] = 1;
            $_SESSION['user_email'] = $email;
            header('Location: /');
            exit;
        } else {
            $error = 'Email ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <!-- Lien vers Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lien vers le CSS personnalisé -->
    <link rel="stylesheet" href="/css/auth.css">
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Connexion</h2>
        <!-- Afficher un message d'erreur si nécessaire -->
        <?php if ($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-success mb-3">Se connecter</button>
            <button class="btn btn-success mb-3">
                <a href="../index.php">
                    Bypass
                </a>
            </button>
            <div class="text-center">
                <span>Pas encore inscrit ? </span>
                <a href="/pages/auth/register.php" class="create-account">Créer un compte</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
