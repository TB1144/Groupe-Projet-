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
        <div class="logo">
            <a href="/" style="text-decoration:none;color:inherit;">
                <h2>Web4<span class="highlight">All</span>.</h2>
            </a>
        </div>
        <nav class="nav-links">
            <ul>
                <li><a href="/">Accueil</a></li>
                <li><a href="/offres">Offres</a></li>
                <li><a href="/entreprises">Entreprises</a></li>
                <li><a href="/statistiques">Statistiques</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
        </nav>
        <div class="nav-actions">
            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['role'] === 'etudiant'): ?>
                    <a href="/wishlist" style="margin-right:10px;font-weight:800;">❤️ Wishlist</a>
                    <a href="/candidatures" style="margin-right:10px;font-weight:800;">Mes candidatures</a>
                <?php endif; ?>
                <a href="/logout" class="btn-primary">Déconnexion</a>
            <?php else: ?>
                <a href="/login" class="btn-primary">Connexion</a>
            <?php endif; ?>
        </div>
        <div class="burger-menu" id="burger-menu">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
    </header>
    <main>
