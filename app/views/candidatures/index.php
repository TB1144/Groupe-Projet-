<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="page-container">
    <section class="page-header">
        <h1>Mes candidatures</h1>
    </section>

    <?php if (empty($candidatures)): ?>
        <p class="empty-state">Vous n'avez pas encore postulé à une offre. <a href="/offres">Voir les offres</a></p>
    <?php else: ?>
        <section class="cards-grid">
            <?php foreach ($candidatures as $c): ?>
                <article class="card">
                    <div class="card-header">
                        <h2 class="card-title"><?= htmlspecialchars($c['offre_titre'] ?? '', ENT_QUOTES, 'UTF-8') ?></h2>
                        <span class="company-name"><?= htmlspecialchars($c['entreprise_nom'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <div class="card-meta">
                        <span class="meta-item">Postulé le <?= date('d/m/Y', strtotime($c['date_candidature'] ?? 'now')) ?></span>
                        <span class="meta-item">Statut : <?= htmlspecialchars($c['statut'] ?? 'En attente', ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
