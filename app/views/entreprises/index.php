<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="page-container">
    <section class="page-header">
        <h1>Entreprises partenaires</h1>
        <p>Découvrez les entreprises qui proposent des stages sur Web4All.</p>
    </section>

    <?php if (empty($entreprises)): ?>
        <p class="empty-state">Aucune entreprise disponible pour le moment.</p>
    <?php else: ?>
        <section class="cards-grid">
            <?php foreach ($entreprises as $entreprise): ?>
                <article class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <?= htmlspecialchars($entreprise['nom'], ENT_QUOTES, 'UTF-8') ?>
                        </h2>
                        <span class="company-name">
                            <?= htmlspecialchars($entreprise['ville'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <p><?= htmlspecialchars(mb_substr($entreprise['description'] ?? '', 0, 120), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="/entreprises/<?= (int)$entreprise['id'] ?>" class="btn-secondary">Voir l'entreprise</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
