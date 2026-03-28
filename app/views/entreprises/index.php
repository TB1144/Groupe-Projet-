<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="page-container">

    <section class="page-header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <h1>Entreprises partenaires</h1>
            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'pilote'])): ?>
                <a href="/entreprises/creer" class="btn-primary">+ Créer une entreprise</a>
            <?php endif; ?>
        </div>

        <form method="GET" action="/entreprises" class="search-filters">
            <input
                type="text"
                name="nom"
                placeholder="Nom de l'entreprise..."
                value="<?= htmlspecialchars($nom ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
            <input
                type="text"
                name="ville"
                placeholder="Ville ou région"
                value="<?= htmlspecialchars($ville ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
            <button type="submit" class="btn-primary">Filtrer</button>
        </form>
    </section>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'], ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($_SESSION['flash']['message'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <p class="results-count">
        <?= $total ?> entreprise<?= $total > 1 ? 's' : '' ?> trouvée<?= $total > 1 ? 's' : '' ?>
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
                        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                            <a href="/entreprises/<?= (int)$entreprise['id'] ?>" class="btn-secondary">Détails</a>

                            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'pilote'])): ?>
                                <a href="/entreprises/<?= (int)$entreprise['id'] ?>/modifier" class="btn-secondary">Modifier</a>
                            <?php endif; ?>

                            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                                <form method="POST" action="/entreprises/<?= (int)$entreprise['id'] ?>/supprimer"
                                    style="display:inline"
                                    onsubmit="return confirm('Supprimer cette entreprise ?')">
                                    <input type="hidden" name="csrf_token"
                                        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn-secondary btn-danger">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <?php
            $queryParams = array_filter(['nom' => $nom ?? '', 'ville' => $ville ?? '']);
            ?>
            <nav class="pagination" aria-label="Pagination des entreprises">

                <?php if ($page > 1): ?>
                    <a href="/entreprises?<?= http_build_query(array_merge($queryParams, ['page' => $page - 1])) ?>"
                       class="btn-page" aria-label="Page précédente">
                        &laquo; Précédent
                    </a>
                <?php else: ?>
                    <span class="btn-page disabled" aria-disabled="true">&laquo; Précédent</span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/entreprises?<?= http_build_query(array_merge($queryParams, ['page' => $i])) ?>"
                       class="btn-page <?= $i === $page ? 'active' : '' ?>"
                       aria-current="<?= $i === $page ? 'page' : 'false' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="/entreprises?<?= http_build_query(array_merge($queryParams, ['page' => $page + 1])) ?>"
                       class="btn-page" aria-label="Page suivante">
                        Suivant &raquo;
                    </a>
                <?php else: ?>
                    <span class="btn-page disabled" aria-disabled="true">Suivant &raquo;</span>
                <?php endif; ?>

            </nav>
        <?php endif; ?>
    <?php endif; ?>

</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>