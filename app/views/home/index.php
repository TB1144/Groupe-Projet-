<?php require __DIR__ . '/../layout/header.php'; ?>

<main>
    <section class="hero">
        <div class="hero-content">
            <h1>Trouvez le stage de vos rêves en un clic.</h1>
            <p>La plateforme exclusive pour les étudiants du CESI. Explorez des centaines d'offres et propulsez votre carrière.</p>
            <form method="GET" action="/offres" class="search-bar-fake">
                <input type="text" name="titre" placeholder="Quel stage recherchez-vous ? (ex: Développeur Web)">
                <button type="submit" class="btn-search">Rechercher</button>
            </form>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
