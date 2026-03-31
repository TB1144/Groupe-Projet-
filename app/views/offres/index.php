<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$pageTitle       = 'Offres de stage — Web4All';
$metaDescription = 'Recherchez parmi toutes les offres de stage disponibles sur Web4All.';
$metaKeywords    = 'offres de stage, stage, CESI, PHP, JavaScript, Python, développeur, data, informatique';

require __DIR__ . '/../layout/header.php';
?>

<main class="page-container">

    <section class="page-header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <h1>Trouvez votre stage</h1>
            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'pilote'])): ?>
                <a href="/offres/creer" class="btn-primary">+ Créer une offre</a>
            <?php endif; ?>
        </div>

        <form method="GET" action="/offres" class="search-filters" role="search">

            <!-- Titre avec autocomplete -->
            <div class="autocomplete-wrapper">
                <input type="text" id="input-titre" name="titre"
                       placeholder="Titre ou compétence (ex: React, PHP…)"
                       value="<?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>"
                       autocomplete="off" aria-label="Rechercher par titre">
                <ul id="autocomplete-titre" class="autocomplete-list" hidden></ul>
            </div>

            <!-- Ville avec autocomplete -->
            <div class="autocomplete-wrapper">
                <input type="text" id="input-ville" name="ville"
                       placeholder="Ville ou région"
                       value="<?= htmlspecialchars($ville, ENT_QUOTES, 'UTF-8') ?>"
                       autocomplete="off" aria-label="Rechercher par ville">
                <ul id="autocomplete-ville" class="autocomplete-list" hidden></ul>
            </div>

            <!-- Durée -->
            <select name="duree" aria-label="Filtrer par durée" class="filter-select">
                <option value="0">Toutes durées</option>
                <?php foreach ([1, 2, 3, 4, 5, 6] as $d): ?>
                    <option value="<?= $d ?>" <?= $duree === $d ? 'selected' : '' ?>><?= $d ?> mois</option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-primary">Filtrer</button>
        </form>
    </section>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'], ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($_SESSION['flash']['message'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <p class="results-count">
        <?= $total ?> offre<?= $total > 1 ? 's' : '' ?> trouvée<?= $total > 1 ? 's' : '' ?>
    </p>

    <?php if (empty($offres)): ?>
        <p class="empty-state">Aucune offre ne correspond à votre recherche.</p>
    <?php else: ?>
        <section class="cards-grid" aria-label="Liste des offres de stage">
            <?php foreach ($offres as $offre): ?>
                <article class="card" id="offre-<?= (int)$offre['id'] ?>">
                    <div class="card-header">
                        <h2 class="card-title"><?= htmlspecialchars($offre['titre'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <span class="company-name">
                            <?= htmlspecialchars($offre['entreprise_nom'], ENT_QUOTES, 'UTF-8') ?>
                            — <?= htmlspecialchars($offre['entreprise_ville'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>

                    <div class="card-body">
                        <p><?= htmlspecialchars(
                            mb_substr($offre['description'], 0, 120) . (mb_strlen($offre['description']) > 120 ? '…' : ''),
                            ENT_QUOTES, 'UTF-8'
                        ) ?></p>
                        <div class="tags">
                            <?php foreach ($offre['competences'] ?? [] as $comp): ?>
                                <span class="tag"><?= htmlspecialchars($comp['nom'], ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endforeach; ?>
                            <span class="tag"><?= (int)$offre['duree'] ?> mois</span>
                        </div>
                    </div>

                    <div class="card-meta">
                        <span class="meta-item">Gratification : <?= htmlspecialchars($offre['remuneration'], ENT_QUOTES, 'UTF-8') ?> €/mois</span>
                        <span class="meta-item">Publié le <?= date('d/m/Y', strtotime($offre['date_offre'])) ?></span>
                        <span class="meta-item"><?= (int)$offre['nb_candidatures'] ?> candidature<?= $offre['nb_candidatures'] > 1 ? 's' : '' ?></span>
                    </div>

                    <div class="card-footer">
                        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                            <a href="/offres/<?= (int)$offre['id'] ?>" class="btn-secondary">Détails</a>

                            <?php if (($_SESSION['role'] ?? '') === 'etudiant'): ?>
                                <a href="/offres/<?= (int)$offre['id'] ?>/postuler" class="btn-secondary">Postuler</a>
                                <?php $enWishlist = in_array($offre['id'], $wishlistIds); ?>
                                <form method="POST" action="/wishlist/toggle" style="display:inline">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="id_offre" value="<?= (int)$offre['id'] ?>">
                                    <input type="hidden" name="retour" value="/offres#offre-<?= (int)$offre['id'] ?>">
                                    <button type="submit" class="btn-wishlist"
                                        title="<?= $enWishlist ? 'Retirer de la wishlist' : 'Ajouter à la wishlist' ?>">
                                        <?= $enWishlist ? '❤️' : '🤍' ?>
                                    </button>
                                </form>
                            <?php endif; ?>

                            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'pilote'])): ?>
                                <a href="/offres/<?= (int)$offre['id'] ?>/modifier" class="btn-secondary">Modifier</a>
                            <?php endif; ?>

                            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'pilote'])): ?>
                                <form method="POST" action="/offres/<?= (int)$offre['id'] ?>/supprimer"
                                      style="display:inline"
                                      onsubmit="return confirm('Supprimer cette offre ?')">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn-secondary btn-danger">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <?php if ($totalPages > 1): ?>
        <nav class="pagination" aria-label="Pagination des offres">
            <?php $queryParams = array_filter(['titre' => $titre, 'ville' => $ville, 'duree' => $duree ?: null]); ?>

            <?php if ($page > 1): ?>
                <a href="/offres?<?= http_build_query(array_merge($queryParams, ['page' => $page - 1])) ?>" class="btn-page">&laquo; Précédent</a>
            <?php else: ?>
                <span class="btn-page disabled">&laquo; Précédent</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="/offres?<?= http_build_query(array_merge($queryParams, ['page' => $i])) ?>"
                   class="btn-page <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="/offres?<?= http_build_query(array_merge($queryParams, ['page' => $page + 1])) ?>" class="btn-page">Suivant &raquo;</a>
            <?php else: ?>
                <span class="btn-page disabled">Suivant &raquo;</span>
            <?php endif; ?>
        </nav>
    <?php endif; ?>

</main>

<style>
.autocomplete-wrapper {
    position: relative;
    flex: 1;
    min-width: 160px;
}

.autocomplete-wrapper input {
    width: 100%;
    padding: 10px;
    border: 3px solid var(--texte-noir);
    font-family: inherit;
    font-size: 16px;
    box-sizing: border-box;
}

.filter-select {
    padding: 10px;
    border: 3px solid var(--texte-noir);
    font-family: inherit;
    font-size: 16px;
    background-color: var(--bg-blanc);
    cursor: pointer;
    outline: none;
}

.autocomplete-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--bg-blanc);
    border: 3px solid var(--texte-noir);
    border-top: none;
    list-style: none;
    margin: 0;
    padding: 4px 0;
    z-index: 200;
    max-height: 260px;
    overflow-y: auto;
    box-shadow: 4px 4px 0 var(--texte-noir);
}

.autocomplete-list li {
    padding: 9px 14px;
    cursor: pointer;
    font-size: 15px;
}

.autocomplete-list li:hover,
.autocomplete-list li.active { background: var(--accent-jaune); }

.autocomplete-list li .ac-nom { font-weight: 700; }
.ac-highlight                  { text-decoration: underline; }
</style>

<script>
    const flash = document.querySelector('.alert');
    if (flash) flash.scrollIntoView({ behavior: 'smooth', block: 'center' });

    (function () {
        function escHtml(s) {
            return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }

        function highlight(text, query) {
            if (!query) return escHtml(text);
            const idx = text.toLowerCase().indexOf(query.toLowerCase());
            if (idx === -1) return escHtml(text);
            return escHtml(text.slice(0, idx))
                 + '<span class="ac-highlight">' + escHtml(text.slice(idx, idx + query.length)) + '</span>'
                 + escHtml(text.slice(idx + query.length));
        }

        function makeAutocomplete(inputEl, listEl, apiUrl, labelKey) {
            let activeIndex = -1;
            let debounceTimer;

            function renderItems(items, query) {
                listEl.innerHTML = '';
                activeIndex = -1;
                if (!items.length) { listEl.hidden = true; return; }
                items.forEach(function (item) {
                    const li  = document.createElement('li');
                    const label = item[labelKey] || '';
                    li.innerHTML = '<span class="ac-nom">' + highlight(label, query) + '</span>';
                    li.addEventListener('mousedown', function (e) {
                        e.preventDefault();
                        inputEl.value = label;
                        listEl.hidden = true;
                        inputEl.form.submit();
                    });
                    listEl.appendChild(li);
                });
                listEl.hidden = false;
            }

            inputEl.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                const q = inputEl.value.trim();
                if (q.length === 0) { listEl.hidden = true; return; }
                debounceTimer = setTimeout(function () {
                    fetch(apiUrl + '?q=' + encodeURIComponent(q))
                        .then(function (r) { return r.json(); })
                        .then(function (data) { renderItems(data, q); })
                        .catch(function () { listEl.hidden = true; });
                }, 180);
            });

            inputEl.addEventListener('keydown', function (e) {
                const items = listEl.querySelectorAll('li');
                if (!items.length || listEl.hidden) return;
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    activeIndex = Math.min(activeIndex + 1, items.length - 1);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    activeIndex = Math.max(activeIndex - 1, -1);
                } else if (e.key === 'Enter' && activeIndex >= 0) {
                    e.preventDefault();
                    items[activeIndex].dispatchEvent(new MouseEvent('mousedown'));
                    return;
                } else if (e.key === 'Escape') {
                    listEl.hidden = true; return;
                }
                items.forEach(function (li, i) { li.classList.toggle('active', i === activeIndex); });
                if (activeIndex >= 0) inputEl.value = items[activeIndex].querySelector('.ac-nom').textContent;
            });

            document.addEventListener('click', function (e) {
                if (!inputEl.contains(e.target) && !listEl.contains(e.target)) listEl.hidden = true;
            });
        }

        makeAutocomplete(
            document.getElementById('input-titre'),
            document.getElementById('autocomplete-titre'),
            '/api/offres/autocomplete-titre',
            'titre'
        );

        makeAutocomplete(
            document.getElementById('input-ville'),
            document.getElementById('autocomplete-ville'),
            '/api/offres/autocomplete-ville',
            'ville'
        );
    })();
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>