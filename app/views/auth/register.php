<?php
$pageTitle       = 'Créer un compte — Web4All';
$metaDescription = 'Créez votre compte étudiant Web4All pour accéder aux offres de stage CESI.';
require __DIR__ . '/../layout/header.php';
?>

<main class="login-page">
    <section class="login-container">
        <div class="login-box register-box">

            <h2>Créer un compte étudiant.</h2>
            <p>Rejoignez Web4All et accédez à toutes les offres de stage CESI.</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <form action="/register" method="POST" id="register-form">

                <div class="input-row">
                    <div class="input-group">
                        <label for="prenom">Prénom <span class="required">*</span></label>
                        <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>
                    </div>
                    <div class="input-group">
                        <label for="nom">Nom <span class="required">*</span></label>
                        <input type="text" id="nom" name="nom" placeholder="Nom de famille" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="email">Adresse e-mail <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="prenom.nom@viacesi.fr" required>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label for="password">Mot de passe <span class="required">*</span></label>
                        <input type="password" id="password" name="password"
                               placeholder="8 caractères minimum" required minlength="8">
                    </div>
                    <div class="input-group">
                        <label for="password-confirm">Confirmer <span class="required">*</span></label>
                        <input type="password" id="password-confirm" name="password_confirm"
                               placeholder="••••••••" required>
                    </div>
                </div>

                <div id="form-error" class="alert alert-error" style="display:none;"></div>

                <p class="required-note"><span class="required">*</span> Champs obligatoires</p>
                <button type="submit" class="btn-submit">Créer mon compte</button>

            </form>

            <div class="login-links">
                <p>Déjà inscrit ? <a href="/login">Se connecter</a></p>
            </div>

        </div>
    </section>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<script>
    document.getElementById('register-form').addEventListener('submit', (e) => {
        const pwd      = document.getElementById('password').value;
        const confirm  = document.getElementById('password-confirm').value;
        const errorBox = document.getElementById('form-error');

        if (pwd !== confirm) {
            e.preventDefault();
            errorBox.style.display = 'block';
            errorBox.textContent   = 'Les mots de passe ne correspondent pas.';
        } else {
            errorBox.style.display = 'none';
        }
    });
</script>