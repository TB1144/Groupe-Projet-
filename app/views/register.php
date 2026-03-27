<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Créez votre compte étudiant Web4All pour accéder aux offres de stage CESI.">
    <meta name="keywords" content="inscription, compte étudiant, Web4All, CESI, stage">
    <title>Créer un compte — Web4All</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <?php require __DIR__ . '/layout/header.php'; ?>

    <main class="login-page">
        <section class="login-container register-container">
            <div class="login-box register-box">
                <div class="register-header">
                    <h2>Créer un compte étudiant.</h2>
                    <p>Rejoignez Web4All et accédez à toutes les offres de stage CESI.</p>
                </div>

                <form action="#" method="POST" enctype="multipart/form-data" id="register-form">

                    <fieldset class="form-section">
                        <legend>Informations personnelles</legend>

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
                                <input type="password" id="password" name="password" placeholder="8 caractères minimum" required minlength="8">
                            </div>
                            <div class="input-group">
                                <label for="password-confirm">Confirmer le mot de passe <span class="required">*</span></label>
                                <input type="password" id="password-confirm" name="password_confirm" placeholder="••••••••" required>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-section">
                        <legend>Parcours CESI</legend>

                        <div class="input-group">
                            <label for="centre">Centre CESI <span class="required">*</span></label>
                            <select id="centre" name="centre" required>
                                <option value="" disabled selected>Sélectionnez votre centre</option>
                                <option value="nice">Nice</option>
                                <option value="aix-en-provence">Aix-en-Provence</option>
                                <option value="arras">Arras</option>
                                <option value="bordeaux">Bordeaux</option>
                                <option value="brest">Brest</option>
                                <option value="grenoble">Grenoble</option>
                                <option value="lemans">Le Mans</option>
                                <option value="lille">Lille</option>
                                <option value="lyon">Lyon</option>
                                <option value="marseille">Marseille</option>
                                <option value="montpellier">Montpellier</option>
                                <option value="nanterre">Nanterre</option>
                                <option value="nantes">Nantes</option>
                                <option value="orleans">Orléans</option>
                                <option value="rouen">Rouen</option>
                                <option value="strasbourg">Strasbourg</option>
                                <option value="toulouse">Toulouse</option>
                            </select>
                        </div>

                        <div class="input-row">
                            <div class="input-group">
                                <label for="formation">Formation <span class="required">*</span></label>
                                <select id="formation" name="formation" required>
                                    <option value="" disabled selected>Sélectionnez votre formation</option>
                                    <option value="bac3-info">Bachelor Informatique (BAC+3)</option>
                                    <option value="bac5-ing">Ingénieur Informatique (BAC+5)</option>
                                    <option value="bac5-cyber">Ingénieur Cybersécurité (BAC+5)</option>
                                    <option value="bac5-data">Ingénieur Data (BAC+5)</option>
                                    <option value="bac5-mgt">Manager SI (BAC+5)</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <label for="annee">Année de promotion <span class="required">*</span></label>
                                <select id="annee" name="annee" required>
                                    <option value="" disabled selected>Année</option>
                                    <option value="1">1ère année</option>
                                    <option value="2">2ème année</option>
                                    <option value="3">3ème année</option>
                                    <option value="4">4ème année</option>
                                    <option value="5">5ème année</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-section">
                        <legend>CV (optionnel)</legend>
                        <div class="input-group">
                            <label for="cv">Déposer votre CV</label>
                            <div class="file-upload-zone" id="file-upload-zone">
                                <input type="file" id="cv" name="cv" accept=".pdf" class="file-input">
                                <div class="file-upload-content">
                                    <span class="file-icon">📄</span>
                                    <p>Glissez votre CV ici ou <span class="file-link">parcourez vos fichiers</span></p>
                                    <p class="file-hint">Format PDF uniquement — 5 Mo maximum</p>
                                </div>
                                <p class="file-name" id="file-name"></p>
                            </div>
                        </div>
                    </fieldset>

                    <div class="form-footer">
                        <p class="required-note"><span class="required">*</span> Champs obligatoires</p>
                        <button type="submit" class="btn-submit">Créer mon compte</button>
                    </div>

                    <div id="form-error" class="form-error" style="display:none;"></div>
                </form>

                <div class="login-links">
                    <p>Déjà inscrit ? <a href="login.html">Se connecter</a></p>
                </div>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/layout/footer.php'; ?>

    <script>
        // Burger menu
        const burgerMenu = document.getElementById('burger-menu');
        const navLinks = document.querySelector('.nav-links');
        burgerMenu.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });

        // File upload
        const fileInput = document.getElementById('cv');
        const fileName = document.getElementById('file-name');
        const uploadZone = document.getElementById('file-upload-zone');

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                fileName.textContent = fileInput.files[0].name;
                uploadZone.classList.add('has-file');
            }
        });

        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('drag-over');
        });

        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('drag-over');
        });

        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('drag-over');
            const files = e.dataTransfer.files;
            if (files.length > 0 && files[0].type === 'application/pdf') {
                fileInput.files = files;
                fileName.textContent = files[0].name;
                uploadZone.classList.add('has-file');
            }
        });

        // Validation mot de passe
        const form = document.getElementById('register-form');
        const errorBox = document.getElementById('form-error');

        form.addEventListener('submit', (e) => {
            const pwd = document.getElementById('password').value;
            const confirm = document.getElementById('password-confirm').value;

            if (pwd !== confirm) {
                e.preventDefault();
                errorBox.style.display = 'block';
                errorBox.textContent = 'Les mots de passe ne correspondent pas.';
            } else {
                errorBox.style.display = 'none';
            }
        });
    </script>
</body>
</html>
