<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gérez vos candidatures de stage sur Web4All. Retrouvez les offres auxquelles vous avez postulé, votre CV et vos lettres de motivation.">
    <meta name="keywords" content="candidatures, postulation, stage, CV, lettre de motivation, Web4All, CESI">
    <title>Mes Candidatures — Web4All</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <?php require __DIR__ . '/layout/header.php'; ?>

    <main class="candidatures-page">

        <div class="candidatures-hero">
            <span class="label">Mon espace</span>
            <h1>Mes<br>Candidatures</h1>
            <p class="candidatures-subtitle">2 candidatures en cours</p>
        </div>

        <!-- Formulaire de candidature (modale simulée) -->
        <div class="candidature-form-box" id="form-postuler">
            <h2>Postuler à une offre</h2>
            <form id="candidature-form" novalidate>
                <div class="form-group">
                    <label for="offre-select">Offre visée</label>
                    <select id="offre-select" name="offre" required>
                        <option value="" disabled selected>Sélectionnez une offre…</option>
                        <option value="data-analyst">Data Analyst Junior — DataMetrics</option>
                        <option value="fullstack">Développeur Web Fullstack — TechCorp SAS</option>
                        <option value="mobile">Développeur Mobile — AppFactory</option>
                        <option value="cyber">Technicien Cybersécurité — SecureNet</option>
                        <option value="chef-projet">Assistant Chef de Projet IT — Web4All Agency</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cv-upload">CV (PDF)</label>
                    <input type="file" id="cv-upload" name="cv" accept=".pdf" required>
                </div>
                <div class="form-group">
                    <label for="lm">Lettre de motivation</label>
                    <textarea id="lm" name="lm" placeholder="Rédigez votre lettre de motivation…" required></textarea>
                </div>
                <button type="submit" class="btn-send">Envoyer ma candidature →</button>
                <div class="success-msg" id="cand-success">
                    ✔ Candidature envoyée avec succès !
                </div>
            </form>
        </div>

        <!-- Liste des candidatures -->
        <div class="candidatures-list">
            <h2 class="section-title-line">Mes candidatures déposées</h2>

            <div class="candidature-row">
                <div class="cand-status status-pending">En attente</div>
                <div class="cand-info">
                    <h3>Data Analyst Junior</h3>
                    <p>DataMetrics — Sophia-Antipolis (06)</p>
                    <p class="cand-date">Déposée le 20 mars 2026</p>
                </div>
                <div class="cand-docs">
                    <a href="#" class="doc-link">📄 Voir le CV</a>
                    <a href="#" class="doc-link">📝 Voir la LM</a>
                </div>
                <button class="btn-remove-cand" title="Retirer la candidature">✕</button>
            </div>

            <div class="candidature-row">
                <div class="cand-status status-pending">En attente</div>
                <div class="cand-info">
                    <h3>Développeur Web Fullstack</h3>
                    <p>TechCorp SAS — Paris (75)</p>
                    <p class="cand-date">Déposée le 18 mars 2026</p>
                </div>
                <div class="cand-docs">
                    <a href="#" class="doc-link">📄 Voir le CV</a>
                    <a href="#" class="doc-link">📝 Voir la LM</a>
                </div>
                <button class="btn-remove-cand" title="Retirer la candidature">✕</button>
            </div>

        </div>

    </main>

    <?php require __DIR__ . '/layout/footer.php'; ?>

    <script>
        const burgerMenu = document.getElementById('burger-menu');
        const navLinks = document.querySelector('.nav-links');
        burgerMenu.addEventListener('click', () => navLinks.classList.toggle('active'));

        document.getElementById('candidature-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const fields = this.querySelectorAll('[required]');
            let valid = true;
            fields.forEach(f => {
                if (!f.value.trim()) { f.style.borderColor = '#E53E3E'; valid = false; }
                else f.style.borderColor = 'var(--texte-noir)';
            });
            if (valid) {
                document.getElementById('cand-success').style.display = 'block';
                this.reset();
            }
        });

        document.querySelectorAll('.btn-remove-cand').forEach(btn => {
            btn.addEventListener('click', () => {
                const row = btn.closest('.candidature-row');
                row.style.opacity = '0';
                row.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    row.remove();
                    const count = document.querySelectorAll('.candidature-row').length;
                    document.querySelector('.candidatures-subtitle').textContent =
                        `${count} candidature${count > 1 ? 's' : ''} en cours`;
                }, 300);
            });
        });
    </script>
</body>
</html>
