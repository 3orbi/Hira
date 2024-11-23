<?php
global $currentPage;
?>

<div class="sidebar">

    <div class="sidebar-header">
        <i class="icon icon-music"></i>
        <span class="title">Hira</span>
    </div>


    <div class="sidebar-section">
        <h6 class="section-title">Ecouter Maintenant</h6>
        <ul class="menu">
            <li class="menu-item">
                <a href="../pages/index.php"
                   class="menu-link <?php echo $currentPage === 'listen-now' ? 'active' : ''; ?>">
                    <i class="icon icon-play"></i>
                    Pour Vous
                </a>
            </li>
            <li class="menu-item">
                <a href="../pages/search.php" class="menu-link <?php echo $currentPage === 'search' ? 'active' : ''; ?>">
                    <i class="icon icon-grid"></i>
                    Rechercher
                </a>
            </li>
            <li class="menu-item">
                <a href="../pages/for-you.php"
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
                <a href="#songs" class="menu-link <?php echo $currentPage === 'songs' ? 'active' : ''; ?>">
                    <i class="icon icon-music-note"></i>
                    Musiques
                </a>
            </li>

            <li class="menu-item">
                <a href="#artists" class="menu-link <?php echo $currentPage === 'artists' ? 'active' : ''; ?>">
                    <i class="icon icon-mic"></i>
                    Artistes
                </a>
            </li>

            <li class="menu-item">
                <a href="#playlists" class="menu-link <?php echo $currentPage === 'playlists' ? 'active' : ''; ?>">
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
    </div>
</div>
