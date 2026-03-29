<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$pageTitle       = 'Offres de stage — Web4All';
$metaDescription = 'Recherchez parmi toutes les offres de stage disponibles sur Web4All.';
$metaKeywords    = 'offres de stage, stage, CESI, PHP, JavaScript, Python, développeur, data, informatique';

require __DIR__ . '/../layout/header.php';
?>

<main class="page-container">

    <section class="page-header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <h1>Trouvez votre stage</h1>
            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'pilote'])): ?>
                <a href="/offres/creer" class="btn-primary">+ Créer une offre</a>
            <?php endif; ?>
        </div>

        <form method="GET" action="/offres" class="search-filters" role="search">
            <input type="text" name="titre" placeholder="Titre ou compétence (ex: React, PHP, Data…)"
                   value="<?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>" aria-label="Rechercher par titre">
            <input type="text" name="ville" placeholder="Ville ou région"
                   value="<?= htmlspecialchars($ville, ENT_QUOTES, 'UTF-8') ?>" aria-label="Rechercher par ville">
            <select name="duree" aria-label="Filtrer par durée">
                <option value="0">Toutes durées</option>
                <?php foreach ([1, 2, 3, 4, 5, 6] as $d): ?>
                    <option value="<?= $d ?>" <?= $duree === $d ? 'selected' : '' ?>><?= $d ?> mois</option>
                <?php endforeach; ?>
            </select>
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
        <?= $total ?> offre<?= $total > 1 ? 's' : '' ?> trouvée<?= $total > 1 ? 's' : '' ?>
    </p>

    <?php
    // Paramètres de recherche actifs + page courante (réutilisés pour la wishlist ET la pagination)
    $queryParams = array_filter([
        'titre' => $titre,
        'ville' => $ville,
        'duree' => $duree ?: null,
        'page'  => $page > 1 ? $page : null,
    ]);
    ?>

    <?php if (empty($offres)): ?>
        <p class="empty-state">Aucune offre ne correspond à votre recherche.</p>
    <?php else: ?>
        <section class="cards-grid" aria-label="Liste des offres de stage">
            <?php foreach ($offres as $offre):
                $retourUrl = '/offres' . (!empty($queryParams) ? '?' . http_build_query($queryParams) : '') . '#offre-' . (int)$offre['id'];
            ?>
                <article class="card" id="offre-<?= (int)$offre['id'] ?>">
                    <div class="card-header">
                        <h2 class="card-title"><?= htmlspecialchars($offre['titre'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <span class="company-name">
                            <?= htmlspecialchars($offre['entreprise_nom'], ENT_QUOTES, 'UTF-8') ?>
                            — <?= htmlspecialchars($offre['entreprise_ville'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>

                    <div class="card-body">
                        <p><?= htmlspecialchars(
                            mb_substr($offre['description'], 0, 120) . (mb_strlen($offre['description']) > 120 ? '…' : ''),
                            ENT_QUOTES, 'UTF-8'
                        ) ?></p>
                        <div class="tags">
                            <?php foreach ($offre['competences'] ?? [] as $comp): ?>
                                <span class="tag"><?= htmlspecialchars($comp['nom'], ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endforeach; ?>
                            <span class="tag"><?= (int)$offre['duree'] ?> mois</span>
                        </div>
                    </div>

                    <div class="card-meta">
                        <span class="meta-item">Gratification : <?= htmlspecialchars($offre['remuneration'], ENT_QUOTES, 'UTF-8') ?> €/mois</span>
                        <span class="meta-item">Publié le <?= date('d/m/Y', strtotime($offre['date_offre'])) ?></span>
                        <span class="meta-item"><?= (int)$offre['nb_candidatures'] ?> candidature<?= $offre['nb_candidatures'] > 1 ? 's' : '' ?></span>
                    </div>

                    <div class="card-footer">
                        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                            <a href="/offres/<?= (int)$offre['id'] ?>" class="btn-secondary">Détails</a>

                            <?php if (($_SESSION['role'] ?? '') === 'etudiant'): ?>
                                <a href="/offres/<?= (int)$offre['id'] ?>/postuler" class="btn-secondary">Postuler</a>

                                <?php $enWishlist = in_array($offre['id'], $wishlistIds); ?>
                                <form method="POST" action="/wishlist/toggle" style="display:inline">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="id_offre" value="<?= (int)$offre['id'] ?>">
                                    <input type="hidden" name="retour" value="<?= htmlspecialchars($retourUrl, ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn-wishlist"
                                        title="<?= $enWishlist ? 'Retirer de la wishlist' : 'Ajouter à la wishlist' ?>">
                                        <?= $enWishlist ? '❤️' : '🤍' ?>
                                    </button>
                                </form>
                            <?php endif; ?>

                            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'pilote'])): ?>
                                <a href="/offres/<?= (int)$offre['id'] ?>/modifier" class="btn-secondary">Modifier</a>
                            <?php endif; ?>

                            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                                <form method="POST" action="/offres/<?= (int)$offre['id'] ?>/supprimer"
                                      style="display:inline"
                                      onsubmit="return confirm('Supprimer cette offre ?')">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn-secondary btn-danger">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <?php if ($totalPages > 1): ?>
        <nav class="pagination" aria-label="Pagination des offres">
            <?php
            // On réutilise $queryParams défini plus haut, en remplaçant juste 'page'
            $paginationBase = array_filter([
                'titre' => $titre,
                'ville' => $ville,
                'duree' => $duree ?: null,
            ]);
            ?>

            <?php if ($page > 1): ?>
                <a href="/offres?<?= http_build_query(array_merge($paginationBase, ['page' => $page - 1])) ?>"
                   class="btn-page">&laquo; Précédent</a>
            <?php else: ?>
                <span class="btn-page disabled">&laquo; Précédent</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="/offres?<?= http_build_query(array_merge($paginationBase, ['page' => $i])) ?>"
                   class="btn-page <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="/offres?<?= http_build_query(array_merge($paginationBase, ['page' => $page + 1])) ?>"
                   class="btn-page">Suivant &raquo;</a>
            <?php else: ?>
                <span class="btn-page disabled">Suivant &raquo;</span>
            <?php endif; ?>
        </nav>
    <?php endif; ?>

</main>

<script>
    const flash = document.querySelector('.alert');
    if (flash) flash.scrollIntoView({ behavior: 'smooth', block: 'center' });
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>