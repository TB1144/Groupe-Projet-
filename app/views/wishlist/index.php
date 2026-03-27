<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Votre liste d'intérêt personnelle sur Web4All. Retrouvez et gérez les offres de stage que vous avez sauvegardées.">
    <meta name="keywords" content="wishlist, liste d'intérêt, stage sauvegardé, Web4All, CESI">
    <title>Ma Wishlist — Web4All</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <?php require __DIR__ . '/layout/header.php'; ?>

    <main class="wishlist-page">

        <div class="wishlist-hero">
            <span class="label">Mon espace</span>
            <h1>Ma<br>Wishlist</h1>
            <p class="wishlist-subtitle">3 offres sauvegardées</p>
        </div>

        <div class="wishlist-grid" id="wishlist-grid">

            <article class="wishlist-card" id="wl-1">
                <div class="wishlist-card-body">
                    <div class="wishlist-info">
                        <h3>Data Analyst Junior</h3>
                        <p class="wl-company">DataMetrics — Sophia-Antipolis (06)</p>
                        <div class="tags">
                            <span class="tag">Python</span>
                            <span class="tag">SQL</span>
                            <span class="tag">Power BI</span>
                            <span class="tag">6 mois</span>
                        </div>
                        <div class="wl-meta">
                            <span>700 €/mois</span>
                            <span>Publié le 5 mars 2026</span>
                            <span>7 candidatures</span>
                        </div>
                    </div>
                    <div class="wishlist-actions">
                        <a href="#" class="btn-secondary">Voir l'offre</a>
                        <a href="candidatures.html" class="btn-primary">Postuler</a>
                        <button class="btn-remove" onclick="retirerOffre('wl-1')" title="Retirer de la wishlist">✕ Retirer</button>
                    </div>
                </div>
            </article>

            <article class="wishlist-card" id="wl-2">
                <div class="wishlist-card-body">
                    <div class="wishlist-info">
                        <h3>Développeur Web Fullstack</h3>
                        <p class="wl-company">TechCorp SAS — Paris (75)</p>
                        <div class="tags">
                            <span class="tag">PHP</span>
                            <span class="tag">JavaScript</span>
                            <span class="tag">MySQL</span>
                            <span class="tag">6 mois</span>
                        </div>
                        <div class="wl-meta">
                            <span>650 €/mois</span>
                            <span>Publié le 15 mars 2026</span>
                            <span>4 candidatures</span>
                        </div>
                    </div>
                    <div class="wishlist-actions">
                        <a href="#" class="btn-secondary">Voir l'offre</a>
                        <a href="candidatures.html" class="btn-primary">Postuler</a>
                        <button class="btn-remove" onclick="retirerOffre('wl-2')" title="Retirer de la wishlist">✕ Retirer</button>
                    </div>
                </div>
            </article>

            <article class="wishlist-card" id="wl-3">
                <div class="wishlist-card-body">
                    <div class="wishlist-info">
                        <h3>Technicien Cybersécurité</h3>
                        <p class="wl-company">SecureNet — Paris (75)</p>
                        <div class="tags">
                            <span class="tag">Cybersécurité</span>
                            <span class="tag">Linux</span>
                            <span class="tag">Réseau</span>
                            <span class="tag">3 mois</span>
                        </div>
                        <div class="wl-meta">
                            <span>620 €/mois</span>
                            <span>Publié le 12 mars 2026</span>
                            <span>5 candidatures</span>
                        </div>
                    </div>
                    <div class="wishlist-actions">
                        <a href="#" class="btn-secondary">Voir l'offre</a>
                        <a href="candidatures.html" class="btn-primary">Postuler</a>
                        <button class="btn-remove" onclick="retirerOffre('wl-3')" title="Retirer de la wishlist">✕ Retirer</button>
                    </div>
                </div>
            </article>

        </div>

        <div class="wishlist-empty" id="wishlist-empty" style="display:none;">
            <p>Votre wishlist est vide.</p>
            <a href="offres.html" class="btn-primary">Explorer les offres</a>
        </div>

    </main>

    <?php require __DIR__ . '/layout/footer.php'; ?>

    <script>
        const burgerMenu = document.getElementById('burger-menu');
        const navLinks = document.querySelector('.nav-links');
        burgerMenu.addEventListener('click', () => navLinks.classList.toggle('active'));

        function retirerOffre(id) {
            const card = document.getElementById(id);
            card.style.opacity = '0';
            card.style.transition = 'opacity 0.3s ease';
            setTimeout(() => {
                card.remove();
                const remaining = document.querySelectorAll('.wishlist-card').length;
                document.querySelector('.wishlist-subtitle').textContent = `${remaining} offre${remaining > 1 ? 's' : ''} sauvegardée${remaining > 1 ? 's' : ''}`;
                if (remaining === 0) {
                    document.getElementById('wishlist-empty').style.display = 'flex';
                }
            }, 300);
        }
    </script>
</body>
</html>
