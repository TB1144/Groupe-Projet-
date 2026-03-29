<?php
$pageTitle = '403 — Accès refusé';
require __DIR__ . '/../layout/header.php';
?>

<main class="page-container">
    <div class="error-page">
        <div class="error-code">403</div>
        <h1>Accès refusé</h1>
        <p>Vous n'avez pas les droits nécessaires pour accéder à cette page.</p>
        <a href="/" class="btn-secondary">Retour à l'accueil</a>
    </div>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>