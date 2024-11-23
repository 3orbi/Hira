<?php
$title = "Albums";
ob_start();
?>

    <h1>Albums</h1>
    <p>Explorez vos albums préférés ici.</p>
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-dark text-white">
                <img src="../public/placeholder.svg" class="card-img-top" alt="Album 1">
                <div class="card-body">
                    <h5 class="card-title">Album 1</h5>
                    <p class="card-text">Artiste: John Doe</p>
                    <a href="/album/1" class="btn btn-light">Voir</a>
                </div>
            </div>
        </div>
        <!-- Plus d'albums -->
    </div>

<?php
$content = ob_get_clean();
include '../components/Layout.php';

