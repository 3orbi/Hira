<?php
$title = 'Artiste';


ob_start();
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: /connexion');
    exit;
}

// Connexion à la base de données
$localhost = 'localhost';
$dbname = 'hira_bdd';
$user = 'root';
$passwordDb = 'root';

try {
    $pdo = new PDO("mysql:host=$localhost;dbname=$dbname", $user, $passwordDb);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}

// Récupérer l'ID de l'artiste depuis l'URL
$artisteId = $_GET['id'] ?? null;

if (!$artisteId) {
    echo 'Artiste introuvable.';
    exit;
}

// Récupérer les informations de l'artiste
$stmt = $pdo->prepare('SELECT * FROM artistes WHERE id = :id');
$stmt->execute(['id' => $artisteId]);
$artiste = $stmt->fetch();

if (!$artiste) {
    echo 'Artiste introuvable.';
    exit;
}

// Récupérer les albums de l'artiste
$stmt = $pdo->prepare('
    SELECT al.id, al.titre, al.date_sortie
    FROM albums al
    WHERE al.artiste_id = :id
    ORDER BY al.date_sortie DESC
');
$stmt->execute(['id' => $artisteId]);
$albums = $stmt->fetchAll();

// Récupérer les chansons de l'artiste avec leurs URLs
$stmt = $pdo->prepare('
    SELECT c.id, c.titre, c.duree, c.url_fichier
    FROM chansons c
    WHERE c.artiste_id = :id
    ORDER BY c.titre ASC
');
$stmt->execute(['id' => $artisteId]);
$chansons = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h1>Artiste : <?php echo htmlspecialchars($artiste['nom']); ?></h1>
    <p><?php echo nl2br(htmlspecialchars($artiste['biographie'] ?? 'Aucune biographie disponible.')); ?></p>

    <!-- Liste des albums -->
    <h3 class="mt-5">Albums</h3>
    <?php if (!empty($albums)): ?>
        <ul class="list-group">
            <?php foreach ($albums as $album): ?>
                <li class="list-group-item">
                    <strong><?php echo htmlspecialchars($album['titre']); ?></strong>
                    <span class="text-muted">(<?php echo htmlspecialchars($album['date_sortie'] ?? 'Date inconnue'); ?>)</span>
                    <a href="/pages/album.php?id=<?php echo $album['id']; ?>" class="float-end">Voir l'album</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun album trouvé pour cet artiste.</p>
    <?php endif; ?>

    <!-- Liste des chansons -->
    <h3 class="mt-5">Chansons</h3>
    <?php if (!empty($chansons)): ?>
        <ul class="list-group">
            <?php foreach ($chansons as $chanson): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo htmlspecialchars($chanson['titre']); ?>
                    <small class="text-muted"><?php echo htmlspecialchars($chanson['duree']); ?></small>
                    <button 
                        class="btn btn-sm btn-primary play-btn" 
                        data-src="<?php echo htmlspecialchars($chanson['url_fichier']); ?>">
                        Lire
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune chanson trouvée pour cet artiste.</p>
    <?php endif; ?>
</div>

<script>
    // Lecture dynamique avec le player
    document.querySelectorAll('.play-btn').forEach(button => {
        button.addEventListener('click', function () {
            const audioPlayer = document.getElementById('audio-player');
            const trackTitle = document.getElementById('track-title');
            const artistName = document.getElementById('artist-name');
            
            audioPlayer.src = this.getAttribute('data-src');
            trackTitle.textContent = this.closest('li').textContent.trim();
            artistName.textContent = '<?php echo htmlspecialchars($artiste['nom']); ?>';
            audioPlayer.play();
        });
    });
</script>

<?php
$content = ob_get_clean();
include 'components/layout.php';
?>
