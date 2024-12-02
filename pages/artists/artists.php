<?php
$title = 'Artistes';

ob_start();
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: /connexion');
    exit;
}

require 'components/database.php';

// Récupérer le terme de recherche depuis la requête GET
$searchTerm = $_GET['search'] ?? '';

// Préparer la requête SQL avec un filtre de recherche
$sql = 'SELECT id, nom, biographie FROM artistes';
$params = [];

if ($searchTerm) {
    $sql .= ' WHERE nom LIKE :term ORDER BY nom ASC';
    $params['term'] = '%' . $searchTerm . '%';
} else {
    $sql .= ' ORDER BY nom ASC';
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$artistes = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h1 class="mb-4">Tous les Artistes</h1>

    <!-- Formulaire de recherche -->
    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher un artiste" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button class="btn btn-primary" type="submit">Rechercher</button>
        </div>
    </form>
    
    <?php if (!empty($artistes)): ?>
        <div class="row">
            <?php foreach ($artistes as $artiste): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($artiste['nom']); ?></h5>
                            <p class="card-text">
                                <?php echo nl2br(htmlspecialchars(substr($artiste['biographie'], 0, 250))); ?>...
                            </p>
                            <a href="/artist?id=<?php echo $artiste['id']; ?>" class="btn btn-primary">Voir plus</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucun artiste trouvé.</p>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'components/layout.php';
?>