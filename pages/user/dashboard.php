<?php
$title = "Tableau de bord";
require_once 'components/database.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user']['id'];

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'] ?? '';
    $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'] ?? '';

    // Validation des données
    if (empty($nom) || empty($email)) {
        $error = "Le nom et l'email sont requis.";
    } elseif (!empty($nouveau_mot_de_passe) && $nouveau_mot_de_passe !== $confirmer_mot_de_passe) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Mise à jour des informations de l'utilisateur
        $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ? WHERE id = ?");
        $stmt->execute([$nom, $email, $user_id]);

        // Mise à jour du mot de passe si fourni
        if (!empty($nouveau_mot_de_passe)) {
            $hashed_password = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);
        }

        $success = "Vos informations ont été mises à jour avec succès.";
        
        // Mettre à jour la session avec les nouvelles informations
        $_SESSION['user']['nom'] = $nom;
        $_SESSION['user']['email'] = $email;
    }
}

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Le reste du code pour récupérer les playlists, l'historique et les statistiques reste inchangé

ob_start();
?>

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">Tableau de bord de <?php echo htmlspecialchars($user['nom']); ?></h1>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Erreur!</strong>
            <span class="block sm:inline"><?php echo $error; ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Succès!</strong>
            <span class="block sm:inline"><?php echo $success; ?></span>
        </div>
    <?php endif; ?>
    
    <!-- Le reste du contenu du tableau de bord reste inchangé -->

    <div class="bg-white p-4 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Profil</h2>
            <p><strong>Nom:</strong> <?php echo htmlspecialchars($user['nom']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Rôle:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Statistiques</h2>
            <p><strong>Total d'écoutes:</strong> <?php echo $total_listens; ?></p>
            <p><strong>Chansons uniques écoutées:</strong> <?php echo $unique_songs; ?></p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Mes Playlists</h2>
            <ul>
                <?php foreach ($playlists as $playlist): ?>
                    <li><?php echo htmlspecialchars($playlist['titre']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="bg-white p-4 rounded shadow md:col-span-2 lg:col-span-3">
            <h2 class="text-xl font-bold mb-4">Écoutés récemment</h2>
            <ul>
                <?php foreach ($recent_plays as $play): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($play['titre']); ?></strong> 
                        par <?php echo htmlspecialchars($play['artiste']); ?> 
                        (<?php echo date('d/m/Y H:i', strtotime($play['date_lecture'])); ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <div class="bg-white p-4 rounded shadow md:col-span-2 lg:col-span-3 mt-6">
        <h2 class="text-xl font-bold mb-4">Modifier mes informations</h2>
        <form action="" method="POST" class="space-y-4">
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="nouveau_mot_de_passe" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                <input type="password" name="nouveau_mot_de_passe" id="nouveau_mot_de_passe" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="confirmer_mot_de_passe" class="block text-sm font-medium text-gray-700">Confirmer le nouveau mot de passe</label>
                <input type="password" name="confirmer_mot_de_passe" id="confirmer_mot_de_passe" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Mettre à jour mes informations
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'components/layout.php';
?>