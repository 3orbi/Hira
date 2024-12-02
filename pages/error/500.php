<?php
http_response_code(500);
$title = 'Erreur Server';
ob_start();
?>
<h1>500</h1>
<p>Erreur Server</p>
<a href="/" class="btn btn-primary">Retour à l'accueil</a>
<?php
$content = ob_get_clean();
include 'components/layout.php';
?>
