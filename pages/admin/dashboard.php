<?php
$title = 'Dashboard Admin';

require 'components/database.php';

session_start();
ob_start();

// Vérifier si l'utilisateur est administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: /connexion');
    exit;
}

// Connexion à la base de données



// Statistiques globales
$statistiques = [
    'total_utilisateurs' => $pdo->query('SELECT COUNT(*) FROM utilisateurs')->fetchColumn(),
    'total_chansons' => $pdo->query('SELECT COUNT(*) FROM chansons')->fetchColumn(),
    'total_connexions' => $pdo->query('SELECT COUNT(*) FROM historique_connexions')->fetchColumn(),
];

// Top utilisateurs actifs (par nombre de connexions)
$stmt = $pdo->query('
    SELECT u.nom, u.email, COUNT(hc.id) AS total_connexions
    FROM utilisateurs u
    JOIN historique_connexions hc ON u.id = hc.utilisateur_id
    GROUP BY u.id
    ORDER BY total_connexions DESC
    LIMIT 5
');
$topUtilisateurs = $stmt->fetchAll();

// Top chansons les plus écoutées
$stmt = $pdo->query('
    SELECT c.titre AS chanson, a.nom AS artiste, COUNT(hl.id) AS total_lectures
    FROM historique_lectures hl
    JOIN chansons c ON hl.chanson_id = c.id
    JOIN artistes a ON c.artiste_id = a.id
    GROUP BY c.id
    ORDER BY total_lectures DESC
    LIMIT 5
');
$topChansons = $stmt->fetchAll();

//Liste des Utilisateurs
$stmt = $pdo->query('SELECT * FROM utilisateurs');
$utilisateurs = $stmt->fetchAll();

// Dernières actions (pages visitées)
$stmt = $pdo->query('
    SELECT u.nom, u.email, hp.url, hp.date_visite
    FROM historique_pages hp
    LEFT JOIN utilisateurs u ON hp.utilisateur_id = u.id
    ORDER BY hp.date_visite DESC
    LIMIT 5
');
$dernieresActions = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h1>Dashboard Administrateur</h1>

    <div class="row mt-4">
        <!-- Statistiques globales -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs</h5>
                    <p class="card-text"><?php echo htmlspecialchars($statistiques['total_utilisateurs']); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Chansons</h5>
                    <p class="card-text"><?php echo htmlspecialchars($statistiques['total_chansons']); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Connexions</h5>
                    <p class="card-text"><?php echo htmlspecialchars($statistiques['total_connexions']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Top utilisateurs actifs -->
    <h3 class="mt-5">Top utilisateurs actifs</h3>
    <ul class="list-group">
        <?php foreach ($topUtilisateurs as $utilisateur): ?>
            <li class="list-group-item">
                <?php echo htmlspecialchars($utilisateur['nom']); ?> (<?php echo htmlspecialchars($utilisateur['email']); ?>) - 
                <?php echo htmlspecialchars($utilisateur['total_connexions']); ?> connexions
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Top chansons écoutées -->
    <h3 class="mt-5">Top chansons les plus écoutées</h3>
    <ul class="list-group">
        <?php foreach ($topChansons as $chanson): ?>
            <li class="list-group-item">
                <?php echo htmlspecialchars($chanson['chanson']); ?> par <?php echo htmlspecialchars($chanson['artiste']); ?> - 
                <?php echo htmlspecialchars($chanson['total_lectures']); ?> écoutes
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Dernières actions -->
    <h3 class="mt-5">Dernières actions des utilisateurs</h3>
    <ul class="list-group">
        <?php foreach ($dernieresActions as $action): ?>
            <li class="list-group-item">
                <?php echo htmlspecialchars($action['nom'] ?? 'Utilisateur inconnu'); ?> 
                (<?php echo htmlspecialchars($action['email'] ?? 'Inconnu'); ?>) - 
                Visité : <?php echo htmlspecialchars($action['url']); ?> à <?php echo htmlspecialchars($action['date_visite']); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Liste des utilisateurs -->
    <h3 class="mt-5">Liste des utilisateurs</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($utilisateur['nom']); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur['email']); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur['role']); ?></td>
                        <td>
                            <a href="/admin/supprimer-utilisateur?id=<?php echo htmlspecialchars($utilisateur['id']); ?>" class="btn btn-danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
    // Fonction pour envoyer les chansons à l'iframe du lecteur
    function sendSongsToPlayer(songs) {
        const iframe = document.getElementById('player-iframe');
        if (iframe) {
            iframe.onload = () => {
                iframe.contentWindow.postMessage({ type: 'LOAD_SONGS', songs: songs }, '*');
            };
            if (iframe.contentWindow) {
                iframe.contentWindow.postMessage({ type: 'LOAD_SONGS', songs: songs }, '*');
            }
        }
    }

    // Fonction pour jouer une chanson spécifique
    function playSong(index) {
        const iframe = document.getElementById('player-iframe');
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.postMessage({ type: 'PLAY_SONG', songIndex: index }, '*');
        }
    }

    // Écouter les messages de l'iframe du lecteur
    window.addEventListener('message', function(event) {
        if (event.data.type === 'PLAYER_STATE') {
            console.log('État du lecteur:', event.data);
            // Vous pouvez ajouter ici du code pour mettre à jour l'interface utilisateur
            // en fonction de l'état du lecteur si nécessaire
        }
    });

    // Envoyer la liste des chansons à l'iframe du lecteur au chargement de la page
    window.addEventListener('load', () => {
        if (typeof songs !== 'undefined') {
            sendSongsToPlayer(songs);
        }
    });
</script>
</div>


<?php
$content = ob_get_clean();
include 'components/layout.php';
?>
