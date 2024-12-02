<?php
$title = 'Album';
ob_start();

?>

<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: /connexion.php');
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

// Récupérer l'album
$albumId = $_GET['id'];
$stmt = $pdo->prepare('
    SELECT al.*, ar.nom AS artiste
    FROM albums al
    JOIN artistes ar ON al.artiste_id = ar.id
    WHERE al.id = :id
');
$stmt->execute(['id' => $albumId]);
$album = $stmt->fetch();

if (!$album) {
    echo 'Album introuvable.';
    exit;
}

// Récupérer les chansons de l'album
$stmt = $pdo->prepare('
    SELECT c.*
    FROM chansons c
    WHERE c.album_id = :album_id
    ORDER BY c.titre ASC
');
$stmt->execute(['album_id' => $albumId]);
$chansons = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h1>Album : <?php echo htmlspecialchars($album['titre']); ?></h1>
    <p><strong>Artiste :</strong> <?php echo htmlspecialchars($album['artiste']); ?></p>
    <p><strong>Date de sortie :</strong> <?php echo htmlspecialchars($album['date_sortie'] ?? 'Inconnue'); ?></p>

    <!-- Liste des chansons -->
    <h3 class="mt-5">Chansons</h3>
    <ul class="list-group">
        <?php foreach ($chansons as $chanson): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo htmlspecialchars($chanson['titre']); ?>
                <span>
                    <button class="btn btn-sm btn-primary play-btn" data-src="/public/music/<?php echo htmlspecialchars($chanson['id']); ?>.mp3">
                        Lire
                    </button>
                    <small class="text-muted ms-2"><?php echo htmlspecialchars($chanson['duree']); ?></small>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
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
            artistName.textContent = '<?php echo htmlspecialchars($album['artiste']); ?>';
            audioPlayer.play();
        });
    });
</script>



<?php
$content = ob_get_clean();
include 'components/layout.php';
?>
