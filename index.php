<?php
// Détection de la route
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = trim($uri, '/');

// Liste des routes disponibles
$routes = [
    '' => 'pages/index.php',
    'library' => 'pages/library.php',
    'search' => 'pages/search.php',
    'error/404' => 'pages/error/404.php',
    'error/500' => 'pages/error/500.php',

    'playlists' => 'pages/playlists/playlists.php',
    'playlist' => 'pages/playlists/playlist.php',
    'playlist_add' => 'pages/playlists/playlist_add.php',
    'song_add' => 'pages/song_add.php',

    'connexion' => 'pages/auth/connexion.php',
    'deconnexion' => 'pages/auth/deconnexion.php',
    "inscription" => 'pages/auth/inscription.php',

    "album" => 'pages/album.php',
    "artist" => 'pages/artist.php',


    'user/dashboard' => 'pages/user/dashboard.php',

    'admin' => 'pages/admin/dashboard.php',
];

// Charge la route ou affiche une erreur 404
if (array_key_exists($route, $routes)) {
    include $routes[$route];
} else {
    include 'pages/error/404.php';
}
?>

