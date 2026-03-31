<?php
$pageTitle       = htmlspecialchars($offre['titre'], ENT_QUOTES, 'UTF-8') . ' — Web4All';
$metaDescription = 'Détail de l\'offre de stage ' . htmlspecialchars($offre['titre'], ENT_QUOTES, 'UTF-8');
require __DIR__ . '/../layout/header.php';
?>

<main class="detail-page">

    <!-- Fil d'ariane -->
    <nav class="breadcrumb">
        <a href="/offres">← Toutes les offres</a>
    </nav>

    <!-- Flash message -->
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'], ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($_SESSION['flash']['message'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Hero -->
    <div class="detail-hero">
        <div>
            <span class="label">Offre de stage</span>
            <h1><?= htmlspecialchars($offre['titre'], ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="detail-location">
                🏢 <?= htmlspecialchars($offre['entreprise_nom'], ENT_QUOTES, 'UTF-8') ?>
                &nbsp;·&nbsp;
                📍 <?= htmlspecialchars($offre['entreprise_ville'], ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>

        <div class="detail-hero-right">
            <div class="detail-stat-box">
                <div style="font-size:28px; font-weight:800; letter-spacing:-1px; line-height:1;">
                    <?= (int)$offre['duree'] ?> mois
                </div>
                <div style="font-size:12px; font-weight:600; color:#555; margin-top:6px; text-transform:uppercase; letter-spacing:1px;">
                    Durée
                </div>
            </div>
            <div class="detail-stat-box" style="background-color:var(--accent-jaune);">
                <div style="font-size:24px; font-weight:800; letter-spacing:-1px; line-height:1;">
                    <?= htmlspecialchars($offre['remuneration'], ENT_QUOTES, 'UTF-8') ?>€
                </div>
                <div style="font-size:12px; font-weight:600; color:#555; margin-top:6px; text-transform:uppercase; letter-spacing:1px;">
                    /mois
                </div>
            </div>
        </div>
    </div>

    <!-- Grille principale -->
    <div class="detail-grid">

        <!-- Colonne gauche : contenu -->
        <div class="detail-main">

            <!-- Description -->
            <section class="detail-section">
                <h2 style="font-size:18px; font-weight:800; text-transform:uppercase; letter-spacing:1px; margin-bottom:20px; border-bottom:3px solid var(--accent-jaune); padding-bottom:10px;">
                    Description du poste
                </h2>
                <p><?= nl2br(htmlspecialchars($offre['description'], ENT_QUOTES, 'UTF-8')) ?></p>
            </section>

            <!-- Compétences -->
            <?php if (!empty($offre['competences'])): ?>
            <section class="detail-section">
                <h2 style="font-size:18px; font-weight:800; text-transform:uppercase; letter-spacing:1px; margin-bottom:20px; border-bottom:3px solid var(--accent-jaune); padding-bottom:10px;">
                    Compétences recherchées
                </h2>
                <div class="tags">
                    <?php foreach ($offre['competences'] as $comp): ?>
                        <span class="tag"><?= htmlspecialchars($comp['nom'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

        </div>

        <!-- Colonne droite : sidebar -->
        <aside class="detail-sidebar">

            <!-- Infos pratiques -->
            <div class="sidebar-box">
                <h3>Infos pratiques</h3>
                <div class="contact-detail-item">
                    <strong>Entreprise</strong>
                    <?= htmlspecialchars($offre['entreprise_nom'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div class="contact-detail-item">
                    <strong>Localisation</strong>
                    <?= htmlspecialchars($offre['entreprise_ville'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div class="contact-detail-item">
                    <strong>Durée</strong>
                    <?= (int)$offre['duree'] ?> mois
                </div>
                <div class="contact-detail-item">
                    <strong>Gratification</strong>
                    <?= htmlspecialchars($offre['remuneration'], ENT_QUOTES, 'UTF-8') ?> €/mois
                </div>
                <div class="contact-detail-item" style="margin-bottom:0;">
                    <strong>Publiée le</strong>
                    <?= date('d/m/Y', strtotime($offre['date_offre'])) ?>
                </div>
            </div>

            <!-- Actions étudiant -->
            <?php if (($_SESSION['role'] ?? '') === 'etudiant'): ?>
            <div class="sidebar-box sidebar-box-yellow">
                <h3>Intéressé(e) ?</h3>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <a href="/offres/<?= (int)$offre['id'] ?>/postuler" class="btn-secondary" style="text-align:center; display:block;">
                        Postuler à cette offre
                    </a>
                    <form method="POST" action="/wishlist/toggle">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="id_offre" value="<?= (int)$offre['id'] ?>">
                        <input type="hidden" name="retour" value="/offres/<?= (int)$offre['id'] ?>">
                        <button type="submit" class="btn-secondary" style="width:100%; text-align:center;">
                            <?= $enWishlist ? '❤️ Retirer de la wishlist' : '🤍 Ajouter à la wishlist' ?>
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <!-- Actions admin/pilote -->
            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'pilote'])): ?>
            <div class="sidebar-box">
                <h3>Administration</h3>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <a href="/offres/<?= (int)$offre['id'] ?>/modifier" class="btn-secondary" style="text-align:center; display:block;">
                        ✏️ Modifier l'offre
                    </a>
                    <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                        <form method="POST" action="/offres/<?= (int)$offre['id'] ?>/supprimer"
                              onsubmit="return confirm('Supprimer cette offre ?')">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" class="btn-secondary btn-danger" style="width:100%; text-align:center;">
                                🗑️ Supprimer
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </aside>
    </div>

</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>