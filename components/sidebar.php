<?php
global $currentPage;

    session_start();

    $admin = false;

    $role = $_SESSION['user']['role'] ?? null;

    if ($role === 'administrateur') {
        $admin = true;
    }

?>

<div class="sidebar">

    <div class="sidebar-header">
        <img src="/public/logo.svg" alt="Hira Logo" class="logo">
    </div>


    <div class="sidebar-section">
        <h6 class="section-title">Ecouter Maintenant</h6>
        <ul class="menu">
            <?php
                if ($admin) {
                    echo '
                        <li class="menu-item">
                            <a href="/admin" class="menu-link">
                                <i class="icon icon-dashboard"></i>
                                Dashboard Admin
                            </a>
                        </li>
                    ';
                }
            ?>
            <li class="menu-item">
                <a href="/"
                   class="menu-link <?php echo $currentPage === 'listen-now' ? 'active' : ''; ?>">
                    <i class="icon icon-play"></i>
                    Pour Vous
                </a>
            </li>
            <li class="menu-item">
                <a href="/search" class="menu-link <?php echo $currentPage === 'search' ? 'active' : ''; ?>">
                    <i class="icon icon-grid"></i>
                    Rechercher
                </a>
            </li>
            <li class="menu-item">
                <a href="/for-you"
                   class="menu-link <?php echo $currentPage === 'for-you' ? 'active' : ''; ?>">
                    <i class="icon icon-user"></i>
                    Pour Vous
                </a>
            </li>
        </ul>
    </div>

    

    <!-- Library Section -->
    <div class="sidebar-section">
        <h6 class="section-title">Bibliothèque</h6>
        <ul class="menu">
            <li class="menu-item">
                <a href="/library" class="menu-link <?php echo $currentPage === 'songs' ? 'active' : ''; ?>">
                    <i class="icon icon-music-note"></i>
                    Musiques
                </a>
            </li>

            <li class="menu-item">
                <a href="/artists" class="menu-link <?php echo $currentPage === 'artists' ? 'active' : ''; ?>">
                    <i class="icon icon-mic"></i>
                    Artistes
                </a>
            </li>

            <li class="menu-item">
                <a href="/playlists" class="menu-link <?php echo $currentPage === 'playlists' ? 'active' : ''; ?>">
                    <i class="icon icon-playlist"></i>
                    Playlists
                </a>
            </li>
        </ul>
    </div>

    <!-- Playlists Section -->
    <div class="sidebar-section">
        <h6 class="section-title">Playlists</h6>
        <ul class="menu">
            <li class="menu-item">
                <a href="#recently-added"
                   class="menu-link <?php echo $currentPage === 'recently-added' ? 'active' : ''; ?>">
                    <i class="icon icon-clock"></i>
                    Recently Added
                </a>
            </li>
            <li class="menu-item">
                <a href="#recently-played"
                   class="menu-link <?php echo $currentPage === 'recently-played' ? 'active' : ''; ?>">
                    <i class="icon icon-play-circle"></i>
                    Recently Played
                </a>
            </li>
            <li class="menu-item">
                <a href="#top-songs" class="menu-link <?php echo $currentPage === 'top-songs' ? 'active' : ''; ?>">
                    <i class="icon icon-heart"></i>
                    Top Songs
                </a>
            </li>
            <li class="menu-item">
                <a href="#top-albums" class="menu-link <?php echo $currentPage === 'top-albums' ? 'active' : ''; ?>">
                    <i class="icon icon-chart"></i>
                    Top Albums
                </a>
            </li>
        </ul>

        <!-- Authentication Section -->
<div class="sidebar-section">
    <h6 class="section-title">Compte</h6>
    <ul class="menu">
        <?php 
            if (isset($_SESSION['user'])){
                echo '
                    <li class="menu-item">
                        <a href="/dashboard" class="menu-link">
                            <i class="icon icon-user"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/deconnexion" class="menu-link">
                            <i class="icon icon-sign-out"></i>
                            Deconnexion
                        </a>
                    </li>
                ';
            } else {
                echo '
                    <li class="menu-item">
                        <a href="/inscription" class="menu-link">
                            <i class="icon icon-user-plus"></i>
                            Inscription
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/connexion" class="menu-link">
                            <i class="icon icon-sign-in"></i>
                            Connexion
                        </a>
                    </li>
                ';
            }
        ?>
        
    </ul>
</div>

    </div>
</div>
