<?php
$pageTitle = '404 — Page introuvable';
require __DIR__ . '/../layout/header.php';
?>

<main class="page-container">
    <section style="text-align:center;padding:4rem 1rem;">
        <h1>404</h1>
        <p>La page que vous cherchez n'existe pas.</p>
        <a href="/" class="btn-primary">Retour à l'accueil</a>
    </section>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
