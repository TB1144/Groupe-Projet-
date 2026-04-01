<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="page-container">
    <div class="create-form-wrapper">

        <a href="/offres/<?= (int)$offre['id'] ?>" class="btn-secondary"
           style="display:inline-block; margin-bottom:30px;">← Retour à l'offre</a>

        <div class="detail-section">
            <h1 style="margin-bottom:6px;">Postuler</h1>
            <p class="td-muted" style="margin-bottom:30px;">
                <?= htmlspecialchars($offre['titre'], ENT_QUOTES, 'UTF-8') ?>
                — <?= htmlspecialchars($offre['entreprise_nom'], ENT_QUOTES, 'UTF-8') ?>
            </p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error" style="margin-bottom:20px;">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST"
                  action="/offres/<?= (int)$offre['id'] ?>/postuler"
                  enctype="multipart/form-data">

                <input type="hidden" name="csrf_token"
                       value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                <!-- CV -->
                <div class="input-group">
                    <label for="cv">
                        CV <span class="required">*</span>
                        <span class="label-hint">(PDF, 5 Mo max)</span>
                    </label>
                    <div class="file-upload-zone" id="file-upload-zone">
                        <input type="file" id="cv" name="cv" accept=".pdf" class="file-input" required>
                        <div class="file-upload-content">
                            <span class="file-icon">📄</span>
                            <p>Glissez votre CV ici ou <span class="file-link">parcourez vos fichiers</span></p>
                            <p class="file-hint">Format PDF uniquement — 5 Mo maximum</p>
                        </div>
                        <p class="file-name" id="file-name"></p>
                    </div>
                </div>

                <!-- Lettre de motivation -->
                <div class="input-group">
                    <label for="lettre_motivation">
                        Lettre de motivation <span class="required">*</span>
                    </label>
                    <textarea id="lettre_motivation" name="lettre_motivation"
                              style="padding:15px; font-size:16px; border:3px solid var(--texte-noir);
                                     border-radius:4px; outline:none; font-family:inherit;
                                     resize:vertical; min-height:200px; width:100%; box-sizing:border-box;"
                              required placeholder="Présentez-vous et expliquez pourquoi cette offre vous intéresse..."
                    ><?= htmlspecialchars($_POST['lettre_motivation'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <p class="required-note"><span class="required">*</span> Champs obligatoires            &²HJGKURF56YDE4FTFDZFD</p>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Envoyer ma candidature</button>
                    <a href="/offres/<?= (int)$offre['id'] ?>" class="btn-secondary">Annuler</a>
                </div>

            </form>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<script>
    const fileInput  = document.getElementById('cv');
    const fileName   = document.getElementById('file-name');
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

    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('drag-over'));

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
</script>