<?php
$pageTitle = '404 — Page introuvable';
require __DIR__ . '/../layout/header.php';
?>

<main class="page-container">
    <div class="error-page">
        <div class="error-code">404</div>
        <h1>Page introuvable</h1>
        <p>La page que vous cherchez n'existe pas ou a été déplacée.</p>
        <a href="/" class="btn-secondary">Retour à l'accueil</a>
    </div>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>