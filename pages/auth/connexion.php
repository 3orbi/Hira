<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Connexion à la base de données
    require 'components/database.php';

    // Vérifier l'utilisateur dans la base
    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Déterminer l'adresse IP de l'utilisateur
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        // Connexion réussie
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'nom' => $user['nom'],
            'role' => $user['role']
        ];

        // Enregistrer dans l'historique des connexions
        $stmt = $pdo->prepare('
            INSERT INTO historique_connexions (utilisateur_id, adresse_ip, statut)
            VALUES (:utilisateur_id, :adresse_ip, "reussi")
        ');
        $stmt->execute([
            'utilisateur_id' => $user['id'],
            'adresse_ip' => $ipAddress
        ]);

        header('Location: /');
        exit;
    } else {
        // Connexion échouée
        $stmt = $pdo->prepare('
            INSERT INTO historique_connexions (utilisateur_id, adresse_ip, statut)
            VALUES (NULL, :adresse_ip, "echec")
        ');
        $stmt->execute(['adresse_ip' => $ipAddress]);

        $error = 'Email ou mot de passe incorrect.';
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <form method="POST" class="p-4 bg-white shadow-sm rounded">
        <h2 class="text-center mb-4">Connexion</h2>
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
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        <p class="mt-3 text-center">Pas encore inscrit ? <a href="/inscription">Créer un compte</a></p>
    </form>
</body>
</html>
