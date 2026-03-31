<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="page-container">

    <section class="page-header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <h1>Entreprises partenaires</h1>
            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'pilote'])): ?>
                <a href="/entreprises/creer" class="btn-primary">+ Créer une entreprise</a>
            <?php endif; ?>
        </div>

        <form method="GET" action="/entreprises" class="search-filters">

            <!-- Nom avec autocomplete -->
            <div class="autocomplete-wrapper">
                <input
                    type="text"
                    id="input-nom"
                    name="nom"
                    placeholder="Nom de l'entreprise..."
                    value="<?= htmlspecialchars($nom ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    autocomplete="off"
                >
                <ul id="autocomplete-nom" class="autocomplete-list" hidden></ul>
            </div>

            <!-- Ville avec autocomplete -->
            <div class="autocomplete-wrapper">
                <input
                    type="text"
                    id="input-ville"
                    name="ville"
                    placeholder="Ville ou région"
                    value="<?= htmlspecialchars($ville ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    autocomplete="off"
                >
                <ul id="autocomplete-ville" class="autocomplete-list" hidden></ul>
            </div>

            <!-- Note minimum -->
            <select name="note_min" class="filter-select">
                <option value="0" <?= ($noteMin ?? 0) == 0 ? 'selected' : '' ?>>Toutes les notes</option>
                <option value="1" <?= ($noteMin ?? 0) == 1 ? 'selected' : '' ?>>★ 1+</option>
                <option value="2" <?= ($noteMin ?? 0) == 2 ? 'selected' : '' ?>>★★ 2+</option>
                <option value="3" <?= ($noteMin ?? 0) == 3 ? 'selected' : '' ?>>★★★ 3+</option>
                <option value="4" <?= ($noteMin ?? 0) == 4 ? 'selected' : '' ?>>★★★★ 4+</option>
                <option value="5" <?= ($noteMin ?? 0) == 5 ? 'selected' : '' ?>>★★★★★ 5</option>
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
        <?= $total ?> entreprise<?= $total > 1 ? 's' : '' ?> trouvée<?= $total > 1 ? 's' : '' ?>
    </p>

    <?php if (empty($entreprises)): ?>
        <p class="empty-state">Aucune entreprise ne correspond à votre recherche.</p>
    <?php else: ?>
        <div class="cards-grid">
            <?php foreach ($entreprises as $entreprise): ?>
                <article class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <?= htmlspecialchars($entreprise['nom'], ENT_QUOTES, 'UTF-8') ?>
                        </h2>
                        <span class="company-name">
                            <?= htmlspecialchars($entreprise['ville'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <p><?= htmlspecialchars(mb_substr($entreprise['description'] ?? '', 0, 120), ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="rating-row">
                            <?php if (!empty($entreprise['moyenne'])): ?>
                                <span class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?= $i <= round($entreprise['moyenne']) ? '★' : '☆' ?>
                                    <?php endfor; ?>
                                </span>
                                <span class="rating-label"><?= number_format($entreprise['moyenne'], 1) ?> / 5</span>
                            <?php else: ?>
                                <span class="rating-label">Pas encore évaluée</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                            <a href="/entreprises/<?= (int)$entreprise['id'] ?>" class="btn-secondary">Détails</a>
                            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'pilote'])): ?>
                                <a href="/entreprises/<?= (int)$entreprise['id'] ?>/modifier" class="btn-secondary">Modifier</a>
                            <?php endif; ?>
                            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                                <form method="POST" action="/entreprises/<?= (int)$entreprise['id'] ?>/supprimer"
                                      style="display:inline"
                                      onsubmit="return confirm('Supprimer cette entreprise ?')">
                                    <input type="hidden" name="csrf_token"
                                           value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn-secondary btn-danger">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <?php $queryParams = array_filter(['nom' => $nom ?? '', 'ville' => $ville ?? '', 'note_min' => ($noteMin ?? 0) > 0 ? $noteMin : null]); ?>
            <nav class="pagination" aria-label="Pagination des entreprises">
                <?php if ($page > 1): ?>
                    <a href="/entreprises?<?= http_build_query(array_merge($queryParams, ['page' => $page - 1])) ?>" class="btn-page">&laquo; Précédent</a>
                <?php else: ?>
                    <span class="btn-page disabled">&laquo; Précédent</span>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/entreprises?<?= http_build_query(array_merge($queryParams, ['page' => $i])) ?>"
                       class="btn-page <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="/entreprises?<?= http_build_query(array_merge($queryParams, ['page' => $page + 1])) ?>" class="btn-page">Suivant &raquo;</a>
                <?php else: ?>
                    <span class="btn-page disabled">Suivant &raquo;</span>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>

</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<style>
/* Les deux wrappers autocomplete prennent flex:1 comme les inputs normaux */
.autocomplete-wrapper {
    position: relative;
    flex: 1;
    min-width: 160px;
}

/* L'input à l'intérieur prend toute la largeur du wrapper */
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
    min-width: 140px;
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
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}

.autocomplete-list li:hover,
.autocomplete-list li.active { background: var(--accent-jaune); }

.autocomplete-list li .ac-nom   { font-weight: 700; }
.autocomplete-list li .ac-ville { font-size: 13px; color: #555; }
.ac-highlight                   { text-decoration: underline; }
</style>

<script>
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

    function makeAutocomplete(inputEl, listEl, apiUrl, labelKey, subKey) {
        let activeIndex = -1;
        let debounceTimer;

        function renderItems(items, query) {
            listEl.innerHTML = '';
            activeIndex = -1;
            if (!items.length) { listEl.hidden = true; return; }

            items.forEach(function (item) {
                const li = document.createElement('li');
                const label = item[labelKey] || '';
                const sub   = subKey && item[subKey] ? item[subKey] : '';
                li.innerHTML = '<span class="ac-nom">' + highlight(label, query) + '</span>'
                             + (sub ? '<span class="ac-ville">' + escHtml(sub) + '</span>' : '');
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

    // Nom — affiche nom + ville en sous-titre
    makeAutocomplete(
        document.getElementById('input-nom'),
        document.getElementById('autocomplete-nom'),
        '/api/entreprises/autocomplete',
        'nom',
        'ville'
    );

    // Ville — affiche juste la ville
    makeAutocomplete(
        document.getElementById('input-ville'),
        document.getElementById('autocomplete-ville'),
        '/api/entreprises/autocomplete-ville',
        'ville',
        null
    );
})();
</script>