<!-- génération de token CSRF pour les formulaires de suppression -->
<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$pageTitle = 'Mes candidatures — Web4All';
require __DIR__ . '/../layout/header.php';
?>

<main class="candidatures-page">

    <div class="candidatures-hero">
        <span class="label">Espace étudiant</span>
        <h1>
            <?= ($_SESSION['role'] === 'etudiant') ? 'Mes candidatures' : 'Toutes les candidatures' ?>
        </h1>
        <p class="candidatures-subtitle">
            <?= count($candidatures) ?> candidature<?= count($candidatures) > 1 ? 's' : '' ?>
        </p>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'], ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($_SESSION['flash']['message'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if (empty($candidatures)): ?>
        <p class="empty-state">Aucune candidature pour le moment.</p>
    <?php else: ?>
        <?php foreach ($candidatures as $c): ?>
            <div class="candidature-row">

                <span class="cand-status status-pending">En attente</span>

                <div class="cand-info">
                    <h3><?= htmlspecialchars($c['offre_titre'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p><?= htmlspecialchars($c['entreprise_nom'], ENT_QUOTES, 'UTF-8') ?></p>

                    <?php if (in_array($_SESSION['role'], ['admin', 'pilote'])): ?>
                        <p>
                            <?= htmlspecialchars($c['etudiant_prenom'] . ' ' . $c['etudiant_nom'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    <?php endif; ?>

                    <p class="cand-date">
                        Postulé le <?= date('d/m/Y', strtotime($c['date_candidature'])) ?>
                    </p>
                </div>

                <div class="cand-docs">
                    <?php if ($_SESSION['role'] === 'etudiant' || $_SESSION['role'] === 'admin'): ?>
                        <form method="POST" action="/candidatures/<?= (int)$c['id'] ?>/supprimer"
                            style="display:inline"
                            onsubmit="return confirm('Retirer cette candidature ?')">
                            <input type="hidden" name="csrf_token"
                                value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" class="btn-remove-cand" title="Retirer">✕</button>
                        </form>
                    <?php endif; ?>

                    <?php if (!empty($c['cv'])): ?>
                        <a href="/uploads/cv/<?= htmlspecialchars($c['cv'], ENT_QUOTES, 'UTF-8') ?>"
                        class="doc-link" target="_blank">CV</a>
                    <?php endif; ?>

                    <?php if (!empty($c['lettre_motivation'])): ?>
                        <details style="max-width:300px;">
                            <summary class="doc-link" style="cursor:pointer;">Lettre de motivation</summary>
                            <p style="margin-top:8px; font-size:14px; color:#333; line-height:1.6;">
                                <?= nl2br(htmlspecialchars($c['lettre_motivation'], ENT_QUOTES, 'UTF-8')) ?>
                            </p>
                        </details>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>