<?php
$title = 'Accueil';
ob_start();

session_start();
?>
<div class="container mt-5">
    <?php

    ?>
    <h1>Bienvenue  
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
    <p>Votre musique, où que vous soyez.</p>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card bg-dark text-white">
                <img src="/public/images/placeholder.svg" class="card-img-top" alt="Playlist 1">
                <div class="card-body">
                    <h5 class="card-title">Playlists Populaires</h5>
                    <p class="card-text">Explorez les meilleures playlists sélectionnées pour vous.</p>
                    <a href="/library.php" class="btn btn-light">Explorer</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-dark text-white">
                <img src="/public/images/placeholder.svg" class="card-img-top" alt="Recommandation">
                <div class="card-body">
                    <h5 class="card-title">Recommandé pour Vous</h5>
                    <p class="card-text">Découvrez des albums et artistes en fonction de vos goûts.</p>
                    <a href="/library.php" class="btn btn-light">Voir Plus</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include 'components/layout.php';
?>
