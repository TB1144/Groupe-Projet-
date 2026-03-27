<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="page-container">
    <section class="offres-header">
        <div class="header-top">
            <h1>Entreprises partenaires</h1>
            <a href="/entreprises/creer" class="btn-primary">+ Créer une entreprise</a>
        </div>

        <form method="GET" action="/entreprises" class="search-filters">
            <input
                type="text"
                name="nom"
                placeholder="Nom de l'entreprise..."
                class="filter-input" 
                value="<?= htmlspecialchars($nom ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
            <input
                type="text"
                name="ville"
                placeholder="Ville ou région"
                class="filter-input"
                value="<?= htmlspecialchars($ville ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
            <button type="submit" class="btn-primary">Filtrer</button>
        </form>
    </section>

    <section class="offres-results">
        <p class="results-count">
            <span><?= $total ?> entreprise<?= $total > 1 ? 's' : '' ?> trouvée<?= $total > 1 ? 's' : '' ?></span>
        </p>

        <?php if (empty($entreprises)): ?>
            <p class="empty-state">Aucune entreprise ne correspond à votre recherche.</p>
        <?php else: ?>
            <div class="cards-grid">
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
            </div>

            <?php if ($totalPages > 1): ?>
                <nav class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?nom=<?= urlencode($nom ?? '') ?>&ville=<?= urlencode($ville ?? '') ?>&page=<?= $i ?>"
                           class="page-btn <?= $i === $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>