<?php
http_response_code(404);
$title = 'Page non trouvée';
ob_start();
?>
<h1>404</h1>
<p>La page que vous cherchez n'existe pas.</p>
<a href="/" class="btn btn-primary">Retour à l'accueil</a>
<?php
$content = ob_get_clean();
include 'components/layout.php';
?>
