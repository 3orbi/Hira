<?php
$title = 'Recherche';


ob_start();
session_start();

require 'components/database.php';

try {
    $pdo = new PDO("mysql:host=$localhost;dbname=$dbname", $user, $passwordDb);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}

$results = [];
$query = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['q'])) {
    $query = trim($_GET['q']);

    // Rechercher dans les artistes
    $stmt = $pdo->prepare('SELECT id, nom AS title, "artiste" AS type FROM artistes WHERE nom LIKE :query');
    $stmt->execute(['query' => "%$query%"]);
    $artists = $stmt->fetchAll();

    // Rechercher dans les albums
    $stmt = $pdo->prepare('SELECT id, titre AS title, "album" AS type FROM albums WHERE titre LIKE :query');
    $stmt->execute(['query' => "%$query%"]);
    $albums = $stmt->fetchAll();

    // Rechercher dans les chansons
    $stmt = $pdo->prepare('SELECT id, titre AS title, "chanson" AS type FROM chansons WHERE titre LIKE :query');
    $stmt->execute(['query' => "%$query%"]);
    $songs = $stmt->fetchAll();

    // Combiner tous les résultats
    $results = array_merge($artists, $albums, $songs);
}
?>

<div class="container mt-5">
    <h1>Recherche</h1>
    <form method="GET" action="search.php" class="mb-4">
        <input type="text" name="q" class="form-control" placeholder="Rechercher un artiste, un album ou une chanson..." value="<?php echo htmlspecialchars($query); ?>" required>
        <button type="submit" class="btn btn-primary mt-2">Rechercher</button>
    </form>

    <?php if (!empty($query) && empty($results)): ?>
        <p>Aucun résultat trouvé pour "<?php echo htmlspecialchars($query); ?>".</p>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <h3>Résultats de recherche pour "<?php echo htmlspecialchars($query); ?>" :</h3>
        <ul class="list-group">
            <?php foreach ($results as $result): ?>
                <li class="list-group-item">
                    <a href="<?php echo getResultLink($result['type'], $result['id']); ?>">
                        <?php echo htmlspecialchars($result['title']); ?>
                    </a>
                    <span class="badge bg-secondary"><?php echo ucfirst($result['type']); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php
function getResultLink($type, $id) {
    switch ($type) {
        case 'artiste':
            return "/artist?id=$id";
        case 'album':
            return "/album?id=$id";
        case 'chanson':
            return "/song?id=$id"; // Page de chanson (à créer)
        default:
            return '#';
    }
}

$content = ob_get_clean();
include 'components/layout.php';
?>
