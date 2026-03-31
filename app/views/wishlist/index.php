<?php
$pageTitle = 'Ma Wishlist — Web4All';
require __DIR__ . '/../layout/header.php';

// Paramètres de recherche en cours
$searchEntreprise = htmlspecialchars($_GET['search_entreprise'] ?? '', ENT_QUOTES, 'UTF-8');

// Query string pour conserver les filtres dans les liens de pagination
$queryString = $searchEntreprise !== '' ? '&search_entreprise=' . urlencode($searchEntreprise) : '';
?>

<main class="wishlist-page">

    <div class="wishlist-hero">
        <span class="label">Mon espace</span>
        <h1>Ma Wishlist</h1>
        <p class="wishlist-subtitle">
            <?= $total ?> offre<?= $total > 1 ? 's' : '' ?> sauvegardée<?= $total > 1 ? 's' : '' ?>
        </p>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'], ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($_SESSION['flash']['message'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- ── Barre de recherche ─────────────────────────────────────────────── -->
    <form method="GET" action="/wishlist" class="search-bar">
        <div class="search-field">
            <label for="search_entreprise">Rechercher une entreprise</label>
            <input
                type="text"
                id="search_entreprise"
                name="search_entreprise"
                value="<?= $searchEntreprise ?>"
                placeholder="Nom de l'entreprise…"
                class="search-input"
            >
        </div>
        <button type="submit" class="btn-search"> Rechercher</button>
        <?php if ($searchEntreprise !== ''): ?>
            <a href="/wishlist" class="btn-reset">✕ Réinitialiser</a>
        <?php endif; ?>
    </form>

    <?php if (empty($items)): ?>
        <div class="wishlist-empty">
            <p>Aucune offre trouvée<?= $searchEntreprise !== '' ? ' pour cette recherche' : '' ?>.</p>
            <?php if ($searchEntreprise === ''): ?>
                <a href="/offres" class="btn-primary">Explorer les offres</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="wishlist-grid">
            <?php foreach ($items as $item): ?>
                <article class="wishlist-card">
                    <div class="wishlist-card-body">

                        <div class="wishlist-info">
                            <h3><?= htmlspecialchars($item['titre'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="wl-company">
                                <?= htmlspecialchars($item['entreprise_nom'], ENT_QUOTES, 'UTF-8') ?>
                                — <?= htmlspecialchars($item['entreprise_ville'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <div class="wl-meta">
                                <span><?= htmlspecialchars($item['remuneration'], ENT_QUOTES, 'UTF-8') ?> €/mois</span>
                                <span><?= (int)$item['duree'] ?> mois</span>
                                <span>Publié le <?= date('d/m/Y', strtotime($item['date_offre'])) ?></span>
                                <span><?= (int)$item['nb_candidatures'] ?> candidature<?= $item['nb_candidatures'] > 1 ? 's' : '' ?></span>
                            </div>
                        </div>

                        <div class="wishlist-actions">
                            <a href="/offres/<?= (int)$item['id_offre'] ?>" class="btn-secondary">
                                Voir l'offre
                            </a>
                            <a href="/offres/<?= (int)$item['id_offre'] ?>/postuler" class="btn-secondary">
                                Postuler
                            </a>
                            <form method="POST" action="/wishlist/toggle"
                                  onsubmit="return confirm('Retirer cette offre de votre wishlist ?')">
                                <input type="hidden" name="csrf_token"
                                       value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="id_offre" value="<?= (int)$item['id_offre'] ?>">
                                <input type="hidden" name="retour" value="/wishlist<?= $page > 1 ? '?page=' . $page . $queryString : ($queryString ? '?' . ltrim($queryString, '&') : '') ?>">
                                <button type="submit" class="btn-remove">✕ Retirer</button>
                            </form>
                        </div>

                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav class="pagination" aria-label="Pagination de la wishlist">
                <?php if ($page > 1): ?>
                    <a href="/wishlist?page=<?= $page - 1 ?><?= $queryString ?>" class="btn-page">&laquo; Précédent</a>
                <?php else: ?>
                    <span class="btn-page disabled">&laquo; Précédent</span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/wishlist?page=<?= $i ?><?= $queryString ?>"
                       class="btn-page <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="/wishlist?page=<?= $page + 1 ?><?= $queryString ?>" class="btn-page">Suivant &raquo;</a>
                <?php else: ?>
                    <span class="btn-page disabled">Suivant &raquo;</span>
                <?php endif; ?>
            </nav>
        <?php endif; ?>

    <?php endif; ?>

</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
