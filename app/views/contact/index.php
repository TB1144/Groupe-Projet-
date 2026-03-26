<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="page-container">
    <section class="page-header">
        <h1>Contactez-nous</h1>
        <p>Une question ? Notre équipe est là pour vous aider.</p>
    </section>

    <section class="login-container">
        <div class="login-box">
            <?php if (!empty($_SESSION['flash'])): ?>
                <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'], ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($_SESSION['flash']['message'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <form method="POST" action="/contact">
                <div class="input-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="votre@email.fr" required>
                </div>
                <div class="input-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" placeholder="Votre message..." required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;"></textarea>
                </div>
                <button type="submit" class="btn-submit">Envoyer</button>
            </form>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
