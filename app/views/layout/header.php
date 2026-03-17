<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'StageIT - Trouvez votre stage' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">StageIT</div>
            <ul class="nav-links">
                <li><a href="/">Accueil</a></li>
                <li><a href="/offres">Offres</a></li>
                <?php if(isset($_SESSION['user'])): ?>
                    <li><a href="/logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/login">Connexion</a></li>
                <?php endif; ?>
            </ul>
            <div class="burger"><span></span></div>
        </nav>
    </header>
    <main class="container">
