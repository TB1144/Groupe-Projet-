<?php
$pageTitle       = 'Statistiques — Web4All';
$metaDescription = 'Statistiques des offres et candidatures sur Web4All.';
require __DIR__ . '/../layout/header.php';
?>

<main class="stats-page">

    <div class="stats-hero">
        <span class="label">Tableau de bord</span>
        <h1>Statistiques<br>des offres</h1>
        <p class="stats-subtitle">Indicateurs clés mis à jour en temps réel.</p>
    </div>

    <!-- ── KPI ──────────────────────────────────────────────────────── -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-number"><?= $totalOffres ?></div>
            <div class="kpi-label">Offres disponibles</div>
        </div>
        <div class="kpi-card kpi-yellow">
            <div class="kpi-number"><?= number_format($moyenneCand, 1) ?></div>
            <div class="kpi-label">Candidatures / offre en moyenne</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-number"><?= $totalEntreprises ?></div>
            <div class="kpi-label">Entreprises partenaires</div>
        </div>
        <div class="kpi-card kpi-yellow">
            <div class="kpi-number"><?= $pourcentageDureeMax ?>%</div>
            <div class="kpi-label">Offres de <?= $maxDuree ?> mois (durée dominante)</div>
        </div>
    </div>

    <!-- ── Carrousel ─────────────────────────────────────────────────── -->
    <div class="carousel-section">
        <div class="carousel-header">
            <h2>Indicateurs détaillés</h2>
            <div class="carousel-controls">
                <button class="carousel-btn" id="prev-btn">&#8592;</button>
                <span id="carousel-indicator">1 / 4</span>
                <button class="carousel-btn" id="next-btn">&#8594;</button>
            </div>
        </div>

        <div class="carousel-track-wrapper">
            <div class="carousel-track" id="carousel-track">

                <!-- Carte 1 : Répartition par durée -->
                <div class="carousel-card">
                    <h3>Répartition par durée de stage</h3>
                    <div class="bar-chart">
                        <?php foreach ($repartitionDuree as $row):
                            $pct = $totalOffres > 0 ? round(($row['nb'] / $totalOffres) * 100) : 0;
                            $isMax = $row['duree'] === $maxDuree;
                        ?>
                            <div class="bar-row">
                                <span class="bar-label"><?= (int)$row['duree'] ?> mois</span>
                                <div class="bar-bg">
                                    <div class="bar-fill <?= $isMax ? 'bar-fill-accent' : '' ?>"
                                         style="width:<?= max($pct, 3) ?>%">
                                        <?= $pct ?>%
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Carte 2 : Top wishlist -->
                <div class="carousel-card">
                    <h3>Top 5 — Offres les plus wishlistées</h3>
                    <?php if (empty($topWishlist)): ?>
                        <p style="color:#555;">Aucune offre en wishlist pour le moment.</p>
                    <?php else: ?>
                        <ol class="top-list">
                            <?php foreach ($topWishlist as $i => $item): ?>
                                <li>
                                    <span class="top-rank"><?= $i + 1 ?></span>
                                    <div class="top-info">
                                        <strong><?= htmlspecialchars($item['titre'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        <span><?= htmlspecialchars($item['entreprise_nom'], ENT_QUOTES, 'UTF-8') ?> — <?= (int)$item['nb_ajouts'] ?> ajout<?= $item['nb_ajouts'] > 1 ? 's' : '' ?></span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif; ?>
                </div>

                <!-- Carte 3 : Répartition par compétence -->
                <div class="carousel-card">
                    <h3>Top compétences demandées</h3>
                    <div class="bar-chart">
                        <?php
                        // Requête inline pour les top compétences
                        $db = Database::getInstance()->getConnection();
                        $topComps = $db->query(
                            "SELECT c.nom, COUNT(oc.id_offre) AS nb
                             FROM competences c
                             JOIN offre_competences oc ON c.id = oc.id_competence
                             GROUP BY c.id
                             ORDER BY nb DESC
                             LIMIT 5"
                        )->fetchAll(PDO::FETCH_ASSOC);
                        $maxComp = !empty($topComps) ? $topComps[0]['nb'] : 1;
                        foreach ($topComps as $i => $comp):
                            $pct = round(($comp['nb'] / $maxComp) * 100);
                        ?>
                            <div class="bar-row">
                                <span class="bar-label"><?= htmlspecialchars($comp['nom'], ENT_QUOTES, 'UTF-8') ?></span>
                                <div class="bar-bg">
                                    <div class="bar-fill <?= $i === 0 ? 'bar-fill-accent' : '' ?>"
                                         style="width:<?= max($pct, 3) ?>%">
                                        <?= (int)$comp['nb'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Carte 4 : Candidatures -->
                <div class="carousel-card">
                    <h3>Activité des candidatures</h3>
                    <div class="stat-detail-grid">
                        <div class="stat-detail">
                            <div class="stat-big"><?= $totalCandidatures ?></div>
                            <div class="stat-desc">Candidatures totales déposées</div>
                        </div>
                        <div class="stat-detail">
                            <div class="stat-big"><?= number_format($moyenneCand, 1) ?></div>
                            <div class="stat-desc">Moyenne de candidatures par offre</div>
                        </div>
                        <div class="stat-detail">
                            <div class="stat-big"><?= $pourcentageEtudiantsActifs ?>%</div>
                            <div class="stat-desc">Des étudiants ont au moins 1 candidature</div>
                        </div>
                        <div class="stat-detail">
                            <div class="stat-big"><?= $maxCandidatures ?></div>
                            <div class="stat-desc">Max de candidatures sur une seule offre</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="carousel-dots" id="carousel-dots">
            <span class="dot active" data-index="0"></span>
            <span class="dot" data-index="1"></span>
            <span class="dot" data-index="2"></span>
            <span class="dot" data-index="3"></span>
        </div>
    </div>

</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<script>
    const track     = document.getElementById('carousel-track');
    const dots      = document.querySelectorAll('.dot');
    const indicator = document.getElementById('carousel-indicator');
    const total     = track.querySelectorAll('.carousel-card').length;
    let current     = 0;

    function goTo(index) {
        current = (index + total) % total;
        track.style.transform = `translateX(-${current * 100}%)`;
        dots.forEach((d, i) => d.classList.toggle('active', i === current));
        indicator.textContent = `${current + 1} / ${total}`;
    }

    document.getElementById('prev-btn').addEventListener('click', () => goTo(current - 1));
    document.getElementById('next-btn').addEventListener('click', () => goTo(current + 1));
    dots.forEach(dot => dot.addEventListener('click', () => goTo(+dot.dataset.index)));

    // setInterval(() => goTo(current + 1), 5000); on peut remettre pour auto-rotation mais ça peut être dérangeant pour l'utilisateur
</script>