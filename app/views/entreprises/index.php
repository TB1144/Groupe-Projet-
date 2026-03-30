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
            <div class="autocomplete-wrapper">
                <input
                    type="text"
                    id="input-nom"
                    name="nom"
                    placeholder="Nom de l'entreprise..."
                    value="<?= htmlspecialchars($nom ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    autocomplete="off"
                >
                <ul id="autocomplete-list" class="autocomplete-list" hidden></ul>
            </div>
            <input
                type="text"
                name="ville"
                placeholder="Ville ou région"
                value="<?= htmlspecialchars($ville ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
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
                        <?php if (!empty($entreprise['moyenne'])): ?>
                            <div class="stars">
                                <?php
                                $moyenne = $entreprise['moyenne'] ?? 0;
                                for ($i = 1; $i <= 5; $i++):
                                ?>
                                    <?php if ($i <= floor($moyenne)): ?>
                                        <span>★</span>
                                    <?php else: ?>
                                        <span>☆</span>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span style="margin-left:5px;">
                                    <?= $moyenne ? number_format($moyenne, 1) : 'N/A' ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="stars">
                                <span>Pas encore évaluée</span>
                            </div>
                        <?php endif; ?>
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
            <?php
            $queryParams = array_filter(['nom' => $nom ?? '', 'ville' => $ville ?? '']);
            ?>
            <nav class="pagination" aria-label="Pagination des entreprises">

                <?php if ($page > 1): ?>
                    <a href="/entreprises?<?= http_build_query(array_merge($queryParams, ['page' => $page - 1])) ?>"
                       class="btn-page" aria-label="Page précédente">
                        &laquo; Précédent
                    </a>
                <?php else: ?>
                    <span class="btn-page disabled" aria-disabled="true">&laquo; Précédent</span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/entreprises?<?= http_build_query(array_merge($queryParams, ['page' => $i])) ?>"
                       class="btn-page <?= $i === $page ? 'active' : '' ?>"
                       aria-current="<?= $i === $page ? 'page' : 'false' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="/entreprises?<?= http_build_query(array_merge($queryParams, ['page' => $page + 1])) ?>"
                       class="btn-page" aria-label="Page suivante">
                        Suivant &raquo;
                    </a>
                <?php else: ?>
                    <span class="btn-page disabled" aria-disabled="true">Suivant &raquo;</span>
                <?php endif; ?>

            </nav>
        <?php endif; ?>
    <?php endif; ?>

</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<style>
.autocomplete-wrapper {
    position: relative;
    flex: 1;
}
.autocomplete-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #d1d5db;
    border-top: none;
    border-radius: 0 0 8px 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
    list-style: none;
    margin: 0;
    padding: 4px 0;
    z-index: 100;
    max-height: 260px;
    overflow-y: auto;
}
.autocomplete-list li {
    padding: 9px 14px;
    cursor: pointer;
    font-size: .95rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}
.autocomplete-list li:hover,
.autocomplete-list li.active {
    background: #f0f4ff;
}
.autocomplete-list li .ac-nom   { font-weight: 500; }
.autocomplete-list li .ac-ville { font-size: .82rem; color: #6b7280; }
.autocomplete-list li .ac-highlight { color: #4f46e5; }
</style>

<script>
(function () {
    const input = document.getElementById('input-nom');
    const list  = document.getElementById('autocomplete-list');
    let activeIndex = -1;
    let debounceTimer;

    function highlight(text, query) {
        if (!query) return escHtml(text);
        const idx = text.toLowerCase().indexOf(query.toLowerCase());
        if (idx !== 0) return escHtml(text);
        return '<span class="ac-highlight">' + escHtml(text.slice(0, query.length)) + '</span>'
             + escHtml(text.slice(query.length));
    }

    function escHtml(s) {
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function renderItems(items, query) {
        list.innerHTML = '';
        activeIndex = -1;
        if (!items.length) { list.hidden = true; return; }

        items.forEach(function (item) {
            const li = document.createElement('li');
            li.innerHTML = '<span class="ac-nom">' + highlight(item.nom, query) + '</span>'
                         + (item.ville ? '<span class="ac-ville">' + escHtml(item.ville) + '</span>' : '');
            li.addEventListener('mousedown', function (e) {
                e.preventDefault();
                input.value = item.nom;
                list.hidden = true;
                input.form.submit();
            });
            list.appendChild(li);
        });
        list.hidden = false;
    }

    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const q = input.value.trim();
        if (q.length === 0) { list.hidden = true; return; }

        debounceTimer = setTimeout(function () {
            fetch('/api/entreprises/autocomplete?q=' + encodeURIComponent(q))
                .then(function (r) { return r.json(); })
                .then(function (data) { renderItems(data, q); })
                .catch(function () { list.hidden = true; });
        }, 180);
    });

    input.addEventListener('keydown', function (e) {
        const items = list.querySelectorAll('li');
        if (!items.length || list.hidden) return;

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
            list.hidden = true;
            return;
        }

        items.forEach(function (li, i) {
            li.classList.toggle('active', i === activeIndex);
        });
        if (activeIndex >= 0) input.value = items[activeIndex].querySelector('.ac-nom').textContent;
    });

    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !list.contains(e.target)) {
            list.hidden = true;
        }
    });
})();
</script>