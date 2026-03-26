<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="page-container">
    <section class="page-header">
        <h1>Statistiques</h1>
        <p>Retrouvez ici les statistiques des offres et candidatures sur Web4All.</p>
    </section>

    <section class="cards-grid">
        <article class="card">
            <div class="card-header">
                <h2 class="card-title">Offres disponibles</h2>
            </div>
            <div class="card-body">
                <p>Consultez les offres de stage disponibles sur la plateforme.</p>
                <a href="/offres" class="btn-secondary">Voir les offres</a>
            </div>
        </article>

        <article class="card">
            <div class="card-header">
                <h2 class="card-title">Entreprises partenaires</h2>
            </div>
            <div class="card-body">
                <p>Découvrez les entreprises qui proposent des stages.</p>
                <a href="/entreprises" class="btn-secondary">Voir les entreprises</a>
            </div>
        </article>
    </section>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
