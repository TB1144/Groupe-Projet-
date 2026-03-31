<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="page-container">
    <div class="create-form-wrapper">

        <a href="/entreprises/<?= (int)$entreprise['id'] ?>" class="btn-secondary" style="display:inline-block; margin-bottom:30px;">← Retour</a>

        <div class="detail-section">
            <h1 style="margin-bottom:30px;">
                Modifier <?= htmlspecialchars($entreprise['nom'], ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error" style="margin-bottom:20px;">
                    <?php foreach ($errors as $e): ?>
                        <p><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/entreprises/<?= (int)$entreprise['id'] ?>/modifier">
                <input type="hidden" name="csrf_token"
                       value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                <div class="input-group">
                    <label for="nom">Nom <span class="required">*</span></label>
                    <input type="text" id="nom" name="nom" required
                           value="<?= htmlspecialchars($entreprise['nom'], ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label for="ville">Ville <span class="required">*</span></label>
                    <input type="text" id="ville" name="ville" required
                           value="<?= htmlspecialchars($entreprise['ville'], ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label for="email">Email de contact</label>
                    <input type="email" id="email" name="email"
                           value="<?= htmlspecialchars($entreprise['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label for="telephone">Téléphone</label>
                    <input type="text" id="telephone" name="telephone"
                           value="<?= htmlspecialchars($entreprise['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"
                              style="padding:15px; font-size:16px; border:3px solid var(--texte-noir); border-radius:4px; outline:none; font-family:inherit; resize:vertical; min-height:120px;"
                    ><?= htmlspecialchars($entreprise['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Enregistrer</button>
                    <a href="/entreprises/<?= (int)$entreprise['id'] ?>" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>