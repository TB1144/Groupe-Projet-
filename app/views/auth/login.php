<?php
$pageTitle = 'Connexion — Web4All';
$metaDescription = 'Connectez-vous à Web4All pour accéder à votre espace.';
require __DIR__ . '/../layout/header.php';
?>

<main class="login-page">
    <section class="login-container">
        <div class="login-box">
            <h2>Bon retour parmi nous.</h2>
            <p>Connectez-vous pour accéder à vos offres de stage.</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form action="/login" method="POST">
                <div class="input-group">
                    <label for="email">Adresse E-mail</label>
                    <input type="email" id="email" name="email" placeholder="prenom.nom@viacesi.fr" required>
                </div>
                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn-submit">Se connecter</button>
            </form>

            <div class="login-links">
                <a href="#">Mot de passe oublié ?</a>
            </div>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
