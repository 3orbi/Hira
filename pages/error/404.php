<?php
http_response_code(404);
$title = "404";
ob_start();
?>

<main>
  <div class="container">
    <h1>404</h1>
    <p>Page not found</p>
    <a href="/index.php">Go back to home</a>
  </div>
</main>

<?php
$content = ob_get_clean();
include $_SERVER['DOCUMENT_ROOT'] . '/components/Layout.php';
