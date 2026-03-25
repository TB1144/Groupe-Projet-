<?php
$pageTitle       = htmlspecialchars($offre['titre'], ENT_QUOTES, 'UTF-8') . ' — Web4All';
$metaDescription = 'Détail de l\'offre de stage ' . htmlspecialchars($offre['titre'], ENT_QUOTES, 'UTF-8');
require __DIR__ . '/../layout/header.php';
?>

<main class="page-container">
    <section class="offre-detail">
        <div class="offre-detail-header">
            <h1><?= htmlspecialchars($offre['titre'], ENT_QUOTES, 'UTF-8') ?></h1>
            <span class="company-name">
                <?= htmlspecialchars($offre['entreprise_nom'], ENT_QUOTES, 'UTF-8') ?>
                — <?= htmlspecialchars($offre['entreprise_ville'], ENT_QUOTES, 'UTF-8') ?>
            </span>
        </div>

        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'], ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($_SESSION['flash']['message'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <div class="offre-detail-body">
            <p><?= nl2br(htmlspecialchars($offre['description'], ENT_QUOTES, 'UTF-8')) ?></p>

            <div class="offre-meta">
                <span class="meta-item">Durée : <?= (int)$offre['duree'] ?> mois</span>
                <span class="meta-item">Gratification : <?= htmlspecialchars($offre['remuneration'], ENT_QUOTES, 'UTF-8') ?> €/mois</span>
                <span class="meta-item">Publié le <?= date('d/m/Y', strtotime($offre['date_offre'])) ?></span>
            </div>

            <?php if (!empty($offre['competences'])): ?>
                <div class="tags">
                    <?php foreach ($offre['competences'] as $comp): ?>
                        <span class="tag"><?= htmlspecialchars($comp['nom'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="offre-detail-footer">
            <a href="/offres" class="btn-secondary">← Retour aux offres</a>

            <?php if (($_SESSION['role'] ?? '') === 'etudiant'): ?>
                <form method="POST" action="/wishlist/toggle" style="display:inline">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="id_offre" value="<?= (int)$offre['id'] ?>">
                    <input type="hidden" name="retour" value="/offres/<?= (int)$offre['id'] ?>">
                    <button type="submit" class="btn-wishlist">
                        <?= $enWishlist ? '❤️ Retirer de la wishlist' : '🤍 Ajouter à la wishlist' ?>
                    </button>
                </form>
            <?php endif; ?>

            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'pilote'])): ?>
                <a href="/offres/<?= (int)$offre['id'] ?>/modifier" class="btn-secondary">Modifier</a>
            <?php endif; ?>

            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                <form method="POST" action="/offres/<?= (int)$offre['id'] ?>/supprimer" style="display:inline"
                      onsubmit="return confirm('Supprimer cette offre ?')">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit" class="btn-danger">Supprimer</button>
                </form>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
