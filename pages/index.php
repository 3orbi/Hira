<?php
$title = 'Accueil';
ob_start();

session_start();
?>

<style>
    .bouton-lecture {
    bottom: 10px;
    right: 10px;
    width: 30px;
    height: 30px;
    background-color: black; 
    color: white;
    border: none;
    border-radius: 50%; 
    display: flex;
    justify-content: center;
    align-items: center;
    transition: background-color 0.3s ease; 
    position: absolute;
}

.bouton-lecture:hover {
    background-color: red; 
    color: white;
    transform: scale(1.1); 
    box-shadow: 0 4px 10px rgba(255, 0, 0, 0.5); 
}
</style>

<div class="container mt-5">
    <div class="text-center">
        <h1 class=" fw-bold">Bienvenue  
            <span>
                <?php 
                    if (isset($_SESSION['user'])) {
                        echo $_SESSION['user']['nom'];
                    } else {
                        echo 'sur Hira';
                    }
                ?>
            </span>
        </h1>
        <h4 class="text-muted fw-bold">Votre musique, où que vous soyez.</h4>
    </div>

    <div class="container-fluid px-0">
    <div class="banner-container position-relative overflow-hidden">
        <div class="banner">
            <img src="public/img/bdlmzeninth.jpg" alt="Promotion" class="img-fluid banner-image w-100">
            <div class="banner-content position-absolute text-white">
                <h2 class="artist-name">La musique</h2>
                <h3 class="album-title fw-bold">BDLM vol1 </h3>
                <p><button class="btn bouton-lecture position-absolute"> <i class="bi bi-play-fill"></i> </button></p>
            </div>
        </div>
    </div>
    </div>

    <div class="d-flex flex-wrap justify-content-start mt-4">
        <?php 
        // Exemple des données des cartes
        $cards = [
            ["img" => "/public/img/album1.jpg", "title" => "Playlists Populaires", "text" => "Explorez les meilleures playlists.", "link" => "#"],
            ["img" => "/public/img/saison00.jpg", "title" => "Saison: 00", "text" => "Luidji", "link" => "#"],
            ["img" => "/public/img/lynn1.jpg", "title" => "3x5", "text" => "Est-ce que c'est par là qu'on pleure - Lynn", "link" => "#"],
            ["img" => "/public/img/blond.jpg", "title" => "BLONDE", "text" => "Godspeed - Frank Ocean", "link" => "#"],
            ["img" => "/public/img/jolagreen.jpg", "title" => "A COLORS SHOW", "text" => "360TrickShot - Jolagreen", "link" => "#"],
            ["img" => "/public/img/freudian.jpg", "title" => "Freudian", "text" => "Best Part - Daniel Ceasar", "link" => "#"],
            ["img" => "/public/img/khalid.jpg", "title" => "AMERICAN TEEN", "text" => "Young Dumb & Broke - Khalid", "link" => "#"],
            ["img" => "/public/img/khalid2.jpg", "title" => "Free Spirit", "text" => "Talk - Khalid", "link" => "#"],
            ["img" => "/public/img/tristessebusiness.jpg", "title" => "Tristesse Business", "text" => "Tu le mérites - Luidji", "link" => "#"],
            ["img" => "/public/img/boscolo.jpg", "title" => "Boscolo", "text" => "Le Rouge - Luidji", "link" => "#"],
            ["img" => "/public/img/album1.jpg", "title" => "Playlists Populaires", "text" => "Explorez les meilleures playlists.", "link" => "#"],
            ["img" => "/public/img/saison00.jpg", "title" => "Saison: 00", "text" => "Luidji", "link" => "#"],
            ["img" => "/public/img/lynn1.jpg", "title" => "3x5", "text" => "Est-ce que c'est par là qu'on pleure - Lynn", "link" => "#"],
            ["img" => "/public/img/blond.jpg", "title" => "BLONDE", "text" => "Godspeed - Frank Ocean", "link" => "#"],
            ["img" => "/public/img/jolagreen.jpg", "title" => "A COLORS SHOW", "text" => "360TrickShot - Jolagreen", "link" => "#"],
            ["img" => "/public/img/freudian.jpg", "title" => "Freudian", "text" => "Best Part - Daniel Ceasar", "link" => "#"],
            ["img" => "/public/img/khalid.jpg", "title" => "AMERICAN TEEN", "text" => "Young Dumb & Broke - Khalid", "link" => "#"],
            ["img" => "/public/img/khalid2.jpg", "title" => "Free Spirit", "text" => "Talk - Khalid", "link" => "#"],
            ["img" => "/public/img/tristessebusiness.jpg", "title" => "Tristesse Business", "text" => "Tu le mérites - Luidji", "link" => "#"],
            ["img" => "/public/img/boscolo.jpg", "title" => "Boscolo", "text" => "Le Rouge - Luidji", "link" => "#"],
        ];

        foreach ($cards as $card): ?>
        <div class="card bg-dark text-white m-2 position-relative" style="width: 15rem; border-radius: 10px; overflow: hidden;">
            <img src="<?= $card['img'] ?>" class="card-img-top" alt="Image" style="height: 12rem; object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title"><?= $card['title'] ?></h5>
                <a href="#" class="card-text-link" style="text-decoration: none; color: inherit;">
                    <p class="card-text"><?= $card['text'] ?></p>
                </a>
            </div>
            <button class="btn bouton-lecture position-absolute">
                <i class="bi bi-play-fill"></i>
            </button>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'components/layout.php';
?>
