<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="page-container">

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'], ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($_SESSION['flash']['message'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if (!$entreprise): ?>
        <p class="empty-state">Entreprise introuvable.</p>
    <?php else: ?>

        <section class="page-header">
            <h1><?= htmlspecialchars($entreprise['nom'], ENT_QUOTES, 'UTF-8') ?></h1>
            <span class="company-name"><?= htmlspecialchars($entreprise['ville'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
        </section>

        <section class="card" style="max-width:700px;margin:2rem auto;">
            <div class="card-body">
                <p><?= htmlspecialchars($entreprise['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($entreprise['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                <p><strong>Téléphone :</strong> <?= htmlspecialchars($entreprise['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="card-footer">
                <a href="/entreprises" class="btn-secondary">← Retour aux entreprises</a>
            </div>
        </section>

        <section class="evaluations" style="max-width:700px;margin:2rem auto;">
            <h2>Évaluations</h2>

            <?php if ($moyenne !== null): ?>
                <p><strong>Moyenne :</strong> <?= $moyenne ?> / 5</p>
            <?php else: ?>
                <p>Aucune évaluation pour le moment.</p>
            <?php endif; ?>

            <?php if (!empty($evaluations)): ?>
                <?php foreach ($evaluations as $eval): ?>
                    <div class="card" style="margin-bottom:1rem;">
                        <div class="card-body">
                            <p>
                                <strong><?= htmlspecialchars($eval['nom'] . ' ' . $eval['prenom'], ENT_QUOTES, 'UTF-8') ?></strong>
                                – <?= (int)$eval['note'] ?>/5
                            </p>
                            <p><?= nl2br(htmlspecialchars($eval['commentaire'] ?? '', ENT_QUOTES, 'UTF-8')) ?></p>
                            <small>Le <?= date('d/m/Y', strtotime($eval['date_evaluation'])) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'pilote'])): ?>
            <?php
            $userEvaluation = null;
            if (!empty($_SESSION['user_id']) && isset($entreprise['id'])) {
                $evaluationModel = new Evaluation();
                $userEvaluation = $evaluationModel->findByEtudiantAndEntreprise($_SESSION['user_id'], $entreprise['id']);
            }
            ?>
            <section class="detail-section" style="max-width:700px;margin:2rem auto;">
                <h2 style="border-bottom: 3px solid var(--accent-jaune); padding-bottom:10px; margin-bottom:20px;">
                    Laisser une évaluation
                </h2>

                <?php if ($userEvaluation): ?>
                    <p>Vous avez déjà laissé une évaluation.</p>
                <?php else: ?>
                    <form method="POST" action="/entreprises/<?= $entreprise['id'] ?>/evaluer">
                        <div class="form-group">
                            <label>Note</label>
                            <div class="star-picker" id="star-picker">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <button type="button" class="star-pick" data-value="<?= $i ?>">★</button>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="note" id="note-input" value="0">
                        </div>
                        <div class="form-group">
                            <label for="commentaire">Commentaire</label>
                            <textarea name="commentaire" id="commentaire" class="eval-input" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn-primary">Envoyer l'évaluation</button>
                    </form>
                <?php endif; ?>
            </section>
        <?php endif; ?>

    <?php endif; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const picker = document.getElementById('star-picker');
    const stars = document.querySelectorAll('.star-pick');
    const noteInput = document.getElementById('note-input');

    if (!picker) return;

    picker.addEventListener('mouseover', (e) => {
        const star = e.target.closest('.star-pick');
        if (!star) return;
        const val = parseInt(star.dataset.value);
        stars.forEach(s => {
            s.style.color = parseInt(s.dataset.value) <= val ? 'var(--accent-jaune)' : '#ccc';
        });
    });

    picker.addEventListener('mouseleave', () => {
        const selected = parseInt(noteInput.value);
        stars.forEach(s => {
            s.style.color = parseInt(s.dataset.value) <= selected ? 'var(--accent-jaune)' : '#ccc';
        });
    });

    picker.addEventListener('click', (e) => {
        const star = e.target.closest('.star-pick');
        if (!star) return;
        const val = parseInt(star.dataset.value);
        noteInput.value = val;
        stars.forEach(s => {
            s.style.color = parseInt(s.dataset.value) <= val ? 'var(--accent-jaune)' : '#ccc';
        });
    });

    document.querySelector('form').addEventListener('submit', (e) => {
        if (parseInt(noteInput.value) < 1) {
            e.preventDefault();
            alert('Veuillez sélectionner une note avant d\'envoyer.');
        }
    });
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>