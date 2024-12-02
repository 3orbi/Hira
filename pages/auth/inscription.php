<?php
session_start();

// Connexion à la base de données
$localhost = 'localhost';
$dbname = 'hira_bdd';
$user = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$localhost;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if ($password !== $confirmPassword) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email invalide.';
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM utilisateurs WHERE email = :email');
        $stmt->execute(['email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Cet email est déjà utilisé.';
        } else {
            // Hacher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insérer l'utilisateur dans la base de données
            $stmt = $pdo->prepare('INSERT INTO utilisateurs (email, mot_de_passe) VALUES (:email, :mot_de_passe)');
            $stmt->execute([
                'email' => $email,
                'mot_de_passe' => $hashedPassword
            ]);

            // Démarrer une session utilisateur
            $_SESSION['user'] = $email;

            // Rediriger vers la page d'accueil
            header('Location: /');
            exit;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <form method="POST" class="p-4 bg-white shadow-sm rounded">
        <h2 class="text-center mb-4">Inscription</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Créer un compte</button>
        <p class="mt-3 text-center">Déjà inscrit ? <a href="/connexion">Se connecter</a></p>
    </form>
</body>
</html>
