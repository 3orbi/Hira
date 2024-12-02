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

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer les playlists de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM playlists WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer l'historique des lectures récentes
$stmt = $pdo->prepare("
    SELECT c.titre, a.nom AS artiste, hl.date_lecture
    FROM historique_lectures hl
    JOIN chansons c ON hl.chanson_id = c.id
    JOIN artistes a ON c.artiste_id = a.id
    WHERE hl.utilisateur_id = ?
    ORDER BY hl.date_lecture DESC
    LIMIT 5
");
$stmt->execute([$user_id]);
$recent_plays = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les statistiques
$stmt = $pdo->prepare("SELECT COUNT(*) as total_listens FROM historique_lectures WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$total_listens = $stmt->fetch(PDO::FETCH_ASSOC)['total_listens'];

$stmt = $pdo->prepare("SELECT COUNT(DISTINCT chanson_id) as unique_songs FROM historique_lectures WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$unique_songs = $stmt->fetch(PDO::FETCH_ASSOC)['unique_songs'];

ob_start();
?>

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">Tableau de bord de <?php echo htmlspecialchars($user['nom']); ?></h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
            <?php if (empty($playlists)): ?>
                <p>Vous n'avez pas encore créé de playlist.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($playlists as $playlist): ?>
                        <li><?php echo htmlspecialchars($playlist['titre']); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="bg-white p-4 rounded shadow md:col-span-2 lg:col-span-3">
            <h2 class="text-xl font-bold mb-4">Écoutés récemment</h2>
            <?php if (empty($recent_plays)): ?>
                <p>Vous n'avez pas encore écouté de chansons.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($recent_plays as $play): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($play['titre']); ?></strong> 
                            par <?php echo htmlspecialchars($play['artiste']); ?> 
                            (<?php echo date('d/m/Y H:i', strtotime($play['date_lecture'])); ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'components/layout.php';
?>