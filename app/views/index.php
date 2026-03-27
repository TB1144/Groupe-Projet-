<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Web4All — La plateforme exclusive de stages pour les étudiants du CESI. Trouvez votre stage parmi des centaines d'offres.">
    <meta name="keywords" content="stage, CESI, offre de stage, alternance, développeur, informatique, ingénieur">
    <title>Accueil — Web4All</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <?php require __DIR__ . '/layout/header.php'; ?>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Trouvez le stage de vos rêves en un clic.</h1>
                <p>La plateforme exclusive pour les étudiants du CESI. Explorez des centaines d'offres et propulsez votre carrière.</p>

                <div class="search-bar-fake">
                    <input type="text" placeholder="Quel stage recherchez-vous ? (ex: Développeur Web)">
                    <button class="btn-search">Rechercher</button>
                </div>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/layout/footer.php'; ?>

    <script>
        const burgerMenu = document.getElementById('burger-menu');
        const navLinks = document.querySelector('.nav-links');
        burgerMenu.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>
</body>
</html>
