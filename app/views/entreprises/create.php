<?php
$pageTitle = 'Créer une entreprise — Web4All';
require __DIR__ . '/../layout/header.php';
?>

<main class="page-container">
    <div class="create-form-wrapper">

        <a href="/entreprises" class="btn-secondary" style="display:inline-block; margin-bottom:30px;">← Retour</a>

        <div class="detail-section">
            <h1 style="margin-bottom:30px;">Créer une entreprise</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error" style="margin-bottom:20px;">
                    <?php foreach ($errors as $e): ?>
                        <p><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/entreprises/creer">
                <input type="hidden" name="csrf_token"
                       value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                <div class="input-group">
                    <label for="nom">Nom <span class="required">*</span></label>
                    <input type="text" id="nom" name="nom" required
                           value="<?= htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label for="ville">Ville <span class="required">*</span></label>
                    <input type="text" id="ville" name="ville" required
                           value="<?= htmlspecialchars($_POST['ville'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label for="email">Email de contact</label>
                    <input type="email" id="email" name="email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label for="telephone">Téléphone</label>
                    <input type="text" id="telephone" name="telephone"
                           value="<?= htmlspecialchars($_POST['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"
                              style="padding:15px; font-size:16px; border:3px solid var(--texte-noir); border-radius:4px; outline:none; font-family:inherit; resize:vertical; min-height:120px;"
                    ><?= htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Créer l'entreprise</button>
                    <a href="/entreprises" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>