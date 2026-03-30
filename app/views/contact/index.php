<?php
$pageTitle       = 'Contact — Web4All';
$metaDescription = 'Contactez l\'équipe Web4All.';
require __DIR__ . '/../layout/header.php';
?>

<main class="page-container">
    <section class="page-header">
        <h1>Contactez-nous</h1>
        <p>Une question ? Notre équipe est là pour vous aider.</p>
    </section>

    <div class="create-form-wrapper">
        <div class="detail-section">

            <?php if (!empty($_SESSION['flash'])): ?>
                <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'], ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($_SESSION['flash']['message'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="/contact">

                <div class="input-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Votre nom" required
                           value="<?= htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="votre@email.fr" required
                           value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required
                              style="padding:15px; font-size:16px; border:3px solid var(--texte-noir);
                                     border-radius:4px; outline:none; font-family:inherit;
                                     resize:vertical; min-height:160px; width:100%; box-sizing:border-box;"
                              placeholder="Votre message..."
                    ><?= htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <button type="submit" class="btn-submit">Envoyer</button>

            </form>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>