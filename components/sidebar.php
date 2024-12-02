<?php
    session_start();

    $admin = false;

    $role = $_SESSION['user']['role'] ?? null;

    if ($role === 'administrateur') {
        $admin = true;
    }

?>

<div class="sidebar bg-dark text-white p-3">
    <h3>Hira</h3>
    <ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link text-white" href="/">Accueil</a>
    </li>
    <?php
        if ( isset($_SESSION['user'])){
            echo '
<li class="nav-item">
        <a class="nav-link text-white" href="/library">Bibliothèque</a>
    </li>
            ';
        }
    ?>
    
    <li class="nav-item">
        <a class="nav-link text-white" href="/search">Recherche</a>
    </li>
    <?php 
        if ($admin) {
            echo'
                <li class="nav-item">
        <a class="nav-link text-white" href="/admin">Admin</a>
    </li>
            ';
        }
    ?>
    <?php
        if (isset($_SESSION['user'])) {
            echo '
                <li class="nav-item">
        <a class="nav-link text-white" href="/user/dashboard">
            Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-white" href="/deconnexion">
            Deconnexion
        </a>
    </li>
            ';
        } else {
            echo '
                <li class="nav-item">
        <a class="nav-link text-white" href="/inscription">
            Inscription
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-white" href="/connexion">
            Connexion
        </a>
    </li>
            ';
        }
    ?>
</ul>


</div>
