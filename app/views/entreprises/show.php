<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="page-container">
    <?php if (!$entreprise): ?>
        <p class="empty-state">Entreprise introuvable.</p>
    <?php else: ?>
        <section class="page-header">
            <h1><?= htmlspecialchars($entreprise['nom'], ENT_QUOTES, 'UTF-8') ?></h1>
            <span class="company-name"><?= htmlspecialchars($entreprise['ville'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
        </section>

        <section class="card" style="max-width:700px;margin:2rem auto;">
            <div class="card-body">
                <p><?= htmlspecialchars($entreprise['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($entreprise['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                <p><strong>Téléphone :</strong> <?= htmlspecialchars($entreprise['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="card-footer">
                <a href="/entreprises" class="btn-secondary">← Retour aux entreprises</a>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
