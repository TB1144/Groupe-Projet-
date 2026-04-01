<?php
$pageTitle = 'Créer un utilisateur — Web4All';
require __DIR__ . '/../layout/header.php';

$currentRole = $_SESSION['role'];
?>

<main class="page-container">
    <div class="create-form-wrapper">

        <a href="/utilisateurs" class="btn-secondary" style="display:inline-block; margin-bottom:30px;">← Retour</a>

        <div class="detail-section">
            <h1 style="margin-bottom:8px;">
                <?= $currentRole === 'pilote' ? 'Ajouter un étudiant' : 'Créer un utilisateur' ?>
            </h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error" style="margin-bottom:20px;">
                    <?php foreach ($errors as $e): ?>
                        <p><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/utilisateurs/creer">
                <input type="hidden" name="csrf_token"
                       value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                <div class="input-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required
                           value="<?= htmlspecialchars($user['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required
                           value="<?= htmlspecialchars($user['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required
                           value="<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <?php if ($currentRole === 'admin'): ?>
                    <div class="input-group">
                        <label for="role">Rôle</label>
                        <select id="role" name="role" class="input-select" onchange="togglePilote(this.value)">
                            <option value="etudiant" <?= ($user['role'] ?? '') === 'etudiant' ? 'selected' : '' ?>>Étudiant</option>
                            <option value="pilote"   <?= ($user['role'] ?? '') === 'pilote'   ? 'selected' : '' ?>>Pilote</option>
                        </select>
                    </div>

                    <div class="input-group" id="pilote-group"
                         style="<?= ($user['role'] ?? 'etudiant') !== 'etudiant' ? 'display:none;' : '' ?>">
                        <label for="id_pilote">Pilote référent</label>
                        <select id="id_pilote" name="id_pilote" class="input-select">
                            <option value="">— Aucun pilote —</option>
                            <?php foreach ($pilotes as $p): ?>
                                <option value="<?= (int)$p['id'] ?>"
                                    <?= (int)($user['id_pilote'] ?? 0) === (int)$p['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['prenom'] . ' ' . $p['nom'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else: ?>
                    <p class="td-muted" style="margin-bottom:16px;">
                        Rôle : <strong>Étudiant</strong> — Pilote référent : <strong>vous</strong>
                    </p>
                <?php endif; ?>

                <div class="input-group">
                    <label for="password">Mot de passe <span class="required">*</span></label>
                    <input type="password" id="password" name="password"
                           placeholder="8 caractères minimum" minlength="8" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Créer l'utilisateur</button>
                    <a href="/utilisateurs" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
function togglePilote(role) {
    document.getElementById('pilote-group').style.display =
        role === 'etudiant' ? '' : 'none';
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>