<?php
$title = 'Albums';

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
$sql = 'SELECT al.id, al.titre, al.date_sortie, ar.nom AS artiste FROM albums al JOIN artistes ar ON al.artiste_id = ar.id';
$params = [];

if ($searchTerm) {
    $sql .= ' WHERE al.titre LIKE :term OR ar.nom LIKE :term ORDER BY al.titre ASC';
    $params['term'] = '%' . $searchTerm . '%';
} else {
    $sql .= ' ORDER BY al.titre ASC';
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$albums = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h1 class="mb-4">Tous les Albums</h1>

    <!-- Formulaire de recherche -->
    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher un album ou un artiste" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button class="btn btn-primary" type="submit">Rechercher</button>
        </div>
    </form>

    <?php if (!empty($albums)): ?>
        <div class="row">
            <?php foreach ($albums as $album): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($album['titre']); ?></h5>
                            <p class="card-text"><strong>Artiste:</strong> <?php echo htmlspecialchars($album['artiste']); ?></p>
                            <p class="card-text"><strong>Date de Sortie:</strong> <?php echo htmlspecialchars($album['date_sortie']); ?></p>
                            <a href="/album?id=<?php echo $album['id']; ?>" class="btn btn-primary">Voir plus</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucun album trouvé.</p>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'components/layout.php';
?>