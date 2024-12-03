<?php
$title = 'Ajouter une Chanson';


ob_start();
session_start();

// Vérifier si l'utilisateur est administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: /connexion.php');
    exit;
}

// Connexion à la base de données
require 'components/database.php';


$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $artisteNom = trim($_POST['artiste_nom']);
    $genreNom = trim($_POST['genre_nom']);
    $albumId = empty($_POST['album_id']) ? null : $_POST['album_id'];
    $duree = trim($_POST['duree']);
    $urlFichier = trim($_POST['url_fichier']);
    $img_url = trim($_POST['img_url']);

    // Validation des champs obligatoires
    if (empty($titre) || empty($artisteNom) || empty($genreNom) || empty($duree) || empty($urlFichier)) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $duree)) {
        $error = 'La durée doit être au format HH:MM:SS.';
    } elseif (!filter_var($urlFichier, FILTER_VALIDATE_URL)) {
        $error = 'L\'URL du fichier est invalide.';
    } else {
        try {
            $pdo->beginTransaction();

            // Vérifier si l'artiste existe
            $stmt = $pdo->prepare('SELECT id FROM artistes WHERE nom = :nom');
            $stmt->execute(['nom' => $artisteNom]);
            $artiste = $stmt->fetch();

            if (!$artiste) {
                // Ajouter un nouvel artiste
                $stmt = $pdo->prepare('INSERT INTO artistes (nom, cree_le) VALUES (:nom, NOW())');
                $stmt->execute(['nom' => $artisteNom]);
                $artisteId = $pdo->lastInsertId();
            } else {
                $artisteId = $artiste['id'];
            }

            // Vérifier si le genre existe
            $stmt = $pdo->prepare('SELECT id FROM genres WHERE nom = :nom');
            $stmt->execute(['nom' => $genreNom]);
            $genre = $stmt->fetch();

            if (!$genre) {
                // Ajouter un nouveau genre
                $stmt = $pdo->prepare('INSERT INTO genres (nom) VALUES (:nom)');
                $stmt->execute(['nom' => $genreNom]);
                $genreId = $pdo->lastInsertId();
            } else {
                $genreId = $genre['id'];
            }

            // Ajouter la chanson dans la base de données
            $stmt = $pdo->prepare('
                INSERT INTO chansons (titre, artiste_id, album_id, genre_id, duree, cree_le, url_fichier, img_url)
                VALUES (:titre, :artiste_id, :album_id, :genre_id, :duree, NOW(), :url_fichier, :img_url)
            ');
            $stmt->execute([
                'titre' => $titre,
                'artiste_id' => $artisteId,
                'album_id' => $albumId,
                'genre_id' => $genreId,
                'duree' => $duree,
                'url_fichier' => $urlFichier,
                'img_url' => $img_url,
            ]);

            $pdo->commit();
            $success = 'Chanson ajoutée avec succès !';
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Erreur lors de l\'ajout : ' . $e->getMessage();
        }
    }
}

// Récupérer les albums pour le formulaire
$albums = $pdo->query('SELECT id, titre FROM albums ORDER BY titre ASC')->fetchAll();
?>

<head>
    <style>
        .py-16 {
            padding-bottom: 4rem;
        }
    </style>
</head>

<div class="py-16 container mt-5 mb-4">
    <h1>Ajouter une Chanson</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" id="titre" name="titre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="artiste_nom" class="form-label">Artiste</label>
            <input type="text" id="artiste_nom" name="artiste_nom" class="form-control" list="artistes-options" required placeholder="Recherchez ou ajoutez un artiste">
            <datalist id="artistes-options"></datalist>
        </div>
        <div class="mb-3">
            <label for="genre_nom" class="form-label">Genre</label>
            <input type="text" id="genre_nom" name="genre_nom" class="form-control" list="genres-options" required placeholder="Recherchez ou ajoutez un genre">
            <datalist id="genres-options"></datalist>
        </div>
        <div class="mb-3">
            <label for="album_id" class="form-label">Album (facultatif)</label>
            <select id="album_id" name="album_id" class="form-select">
                <option value="">Aucun</option>
                <?php foreach ($albums as $album): ?>
                    <option value="<?php echo $album['id']; ?>"><?php echo htmlspecialchars($album['titre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="duree" class="form-label">Durée (HH:MM:SS)</label>
            <input type="text" id="duree" name="duree" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="url_fichier" class="form-label">URL du Fichier (Supabase)</label>
            <input type="url" id="url_fichier" name="url_fichier" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="url_cover" class="form-label">URL de la Cover (Supabase)</label>
            <input type="url" id="img_url" name="img_url" class="form-control">
        </div>
        
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
    <div class='m-4'>

    </div>
</div>

<script>
    // Autocomplétion pour les artistes
    document.getElementById('artiste_nom').addEventListener('input', function () {
        const query = this.value;
        if (query.length > 1) {
            fetch(`/pages/search_artist.php?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    const datalist = document.getElementById('artistes-options');
                    datalist.innerHTML = ''; // Efface les anciennes suggestions
                    data.forEach(artist => {
                        const option = document.createElement('option');
                        option.value = artist.nom;
                        datalist.appendChild(option);
                    });
                });
        }
    });

    // Autocomplétion pour les genres
    document.getElementById('genre_nom').addEventListener('input', function () {
        const query = this.value;
        if (query.length > 1) {
            fetch(`/pages/search_genre.php?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    const datalist = document.getElementById('genres-options');
                    datalist.innerHTML = ''; // Efface les anciennes suggestions
                    data.forEach(genre => {
                        const option = document.createElement('option');
                        option.value = genre.nom;
                        datalist.appendChild(option);
                    });
                });
        }
    });
</script>

<?php
$content = ob_get_clean();
include 'components/layout_sans_player.php';
?>
