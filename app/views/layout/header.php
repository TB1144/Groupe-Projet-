<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (!empty($metaDescription)): ?>
    <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <?php endif; ?>
    <?php if (!empty($metaKeywords)): ?>
    <meta name="keywords" content="<?= htmlspecialchars($metaKeywords) ?>">
    <?php endif; ?>
    <title><?= $pageTitle ?? 'Web4All — Trouvez votre stage' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="navbar">

        <!-- Logo -->
        <div class="logo">
            <a href="/" class="logo-link">
                <h2>Web4<span class="highlight">All</span>.</h2>
            </a>
        </div>

        <!-- Nav desktop -->
        <nav class="nav-links" id="nav-links" aria-label="Navigation principale">
            <ul>
                <li><a href="/">Accueil</a></li>
                <li><a href="/offres">Offres</a></li>
                <li><a href="/entreprises">Entreprises</a></li>
                <li><a href="/statistiques">Statistiques</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>

            <!-- Actions dans le menu mobile (dupliquées pour mobile) -->
            <div class="nav-actions-mobile">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="mobile-username">
                        <?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?>
                    </span>
                    <?php if (($_SESSION['role'] ?? '') === 'etudiant'): ?>
                        <a href="/wishlist" class="mobile-nav-link">❤️ Wishlist</a>
                        <a href="/candidatures" class="mobile-nav-link">📄 Mes candidatures</a>
                    <?php endif; ?>
                    <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                        <a href="/utilisateurs" class="mobile-nav-link">👥 Utilisateurs</a>
                    <?php endif; ?>
                    <a href="/logout" class="btn-primary mobile-btn">Déconnexion</a>
                <?php else: ?>
                    <a href="/register" class="btn-orange mobile-btn">S'inscrire</a>
                    <a href="/login" class="btn-primary mobile-btn">Connexion</a>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Actions desktop -->
        <div class="nav-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (($_SESSION['role'] ?? '') === 'etudiant'): ?>
                    <a href="/wishlist" class="nav-action-link">❤️ Wishlist</a>
                    <a href="/candidatures" class="nav-action-link">Mes candidatures</a>
                <?php endif; ?>
                
                <span class="nav-username">
                    <?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?>
                </span>

                <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                    <a href="/utilisateurs" class="btn-orange">Utilisateurs</a>
                <?php endif; ?>

                <a href="/logout" class="btn-primary">Déconnexion</a>
            <?php else: ?>
                <a href="/register" class="btn-orange">S'inscrire</a>
                <a href="/login" class="btn-primary">Connexion</a>
            <?php endif; ?>
        </div>

        <!-- Burger -->
        <button class="burger-menu" id="burger-menu" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="nav-links">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </button>

    </header>

    <script>
        const burger  = document.getElementById('burger-menu');
        const navLinks = document.getElementById('nav-links');

        burger.addEventListener('click', () => {
            const isOpen = navLinks.classList.toggle('active');
            burger.setAttribute('aria-expanded', isOpen);
            burger.setAttribute('aria-label', isOpen ? 'Fermer le menu' : 'Ouvrir le menu');
        });

        // Ferme le menu si on clique en dehors
        document.addEventListener('click', (e) => {
            if (!burger.contains(e.target) && !navLinks.contains(e.target)) {
                navLinks.classList.remove('active');
                burger.setAttribute('aria-expanded', 'false');
            }
        });

        // Ferme le menu sur resize vers desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                navLinks.classList.remove('active');
                burger.setAttribute('aria-expanded', 'false');
            }
        });
    </script>