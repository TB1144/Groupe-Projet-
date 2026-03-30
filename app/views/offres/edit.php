<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$pageTitle = 'Modifier l\'offre — Web4All';
require __DIR__ . '/../layout/header.php';
?>

<main class="page-container">
    <section class="login-container">
        <div class="login-box" style="max-width: 1000px;>
            <h1>Modifier l'offre</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/offres/<?= (int)$offre['id'] ?>/modifier">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                <div class="input-group">
                    <label for="titre">Titre <span class="required">*</span></label>
                    <input type="text" id="titre" name="titre" required
                           value="<?= htmlspecialchars($offre['titre'], ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label for="description">Description <span class="required">*</span></label>
                    <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($offre['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="input-group">
                    <label for="id_entreprise">Entreprise <span class="required">*</span></label>
                    <select id="id_entreprise" name="id_entreprise" required>
                        <option value="">Sélectionnez une entreprise</option>
                        <?php foreach ($entreprises as $ent): ?>
                            <option value="<?= (int)$ent['id'] ?>"
                                <?= (int)$offre['entreprise_id'] === (int)$ent['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ent['nom'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label for="remuneration">Gratification (€/mois)</label>
                        <input type="number" id="remuneration" name="remuneration" min="0" step="0.01"
                               value="<?= htmlspecialchars($offre['remuneration'], ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="input-group">
                        <label for="duree">Durée (mois) <span class="required">*</span></label>
                        <input type="number" id="duree" name="duree" min="1" required
                               value="<?= (int)$offre['duree'] ?>">
                    </div>
                </div>

                <div class="input-group">
                    <label for="date_offre">Date de l'offre <span class="required">*</span></label>
                    <input type="date" id="date_offre" name="date_offre" required
                           value="<?= htmlspecialchars($offre['date_offre'], ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label>Compétences</label>
                    <div class="tags">
                        <?php foreach ($competences as $comp): ?>
                            <label class="tag-checkbox">
                                <input type="checkbox" name="competences[]" value="<?= (int)$comp['id'] ?>"
                                    <?= in_array((int)$comp['id'], $competencesSelectionnees) ? 'checked' : '' ?>>
                                <?= htmlspecialchars($comp['nom'], ENT_QUOTES, 'UTF-8') ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Enregistrer</button>
            </form>

            <div class="login-links">
                <a href="/offres/<?= (int)$offre['id'] ?>">← Retour à l'offre</a>
            </div>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
