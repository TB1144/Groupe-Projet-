<?php
$pageTitle = 'Utilisateurs — Web4All';
require __DIR__ . '/../layout/header.php';

$currentRole    = $_SESSION['role'];
$roleLabels     = ['admin' => 'Admin', 'pilote' => 'Pilote', 'etudiant' => 'Étudiant'];
$roleBadgeClass = ['admin' => 'badge-admin', 'pilote' => 'badge-pilote', 'etudiant' => 'badge-etudiant'];
?>

<main class="page-container">

    <section class="page-header">
        <h1><?= $currentRole === 'pilote' ? 'Mes étudiants' : 'Utilisateurs' ?></h1>
        <form method="GET" action="/utilisateurs" class="search-filters">
            <input
                type="text"
                name="search"
                placeholder="Nom, prénom ou email..."
                value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
            >
            <?php if ($currentRole === 'admin'): ?>
                <select name="role">
                    <option value="">Tous les rôles</option>
                    <option value="admin"    <?= $role === 'admin'    ? 'selected' : '' ?>>Admin</option>
                    <option value="pilote"   <?= $role === 'pilote'   ? 'selected' : '' ?>>Pilote</option>
                    <option value="etudiant" <?= $role === 'etudiant' ? 'selected' : '' ?>>Étudiant</option>
                </select>
            <?php endif; ?>
            <a href="/utilisateurs/creer" class="btn-primary">Créer un utilisateur</a>
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
        <?= $total ?> utilisateur<?= $total > 1 ? 's' : '' ?> trouvé<?= $total > 1 ? 's' : '' ?>
    </p>

    <?php if (empty($users)): ?>
        <p class="empty-state">Aucun utilisateur ne correspond à votre recherche.</p>
    <?php else: ?>

        <div class="users-table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <?php if ($currentRole === 'admin'): ?>
                            <th>Rôle</th>
                            <th>Pilote</th>
                        <?php endif; ?>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="td-bold"><?= htmlspecialchars($u['nom'],    ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($u['prenom'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="td-muted"><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></td>

                            <?php if ($currentRole === 'admin'): ?>
                                <td>
                                    <span class="role-badge <?= $roleBadgeClass[$u['role']] ?? '' ?>">
                                        <?= $roleLabels[$u['role']] ?? $u['role'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($u['role'] === 'etudiant' && !empty($u['pilote_nom'])): ?>
                                        <span class="pilote-name">
                                            <?= htmlspecialchars($u['pilote_prenom'] . ' ' . $u['pilote_nom'], ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    <?php elseif ($u['role'] === 'etudiant'): ?>
                                        <span class="td-muted">Aucun pilote</span>
                                    <?php else: ?>
                                        <span class="td-muted">—</span>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            <td class="td-actions">
                                <a href="/utilisateurs/<?= (int)$u['id'] ?>/modifier" class="btn-secondary">Modifier</a>
                                <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                                    <form method="POST"
                                          action="/utilisateurs/<?= (int)$u['id'] ?>/supprimer"
                                          style="display:inline"
                                          onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        <input type="hidden" name="csrf_token"
                                               value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                        <button type="submit" class="btn-secondary btn-danger">Supprimer</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav class="pagination" style="margin-top:30px;">
                <?php
                $queryParams = array_filter(['search' => $search, 'role' => $role]);
                for ($i = 1; $i <= $totalPages; $i++):
                ?>
                    <a href="/utilisateurs?<?= http_build_query(array_merge($queryParams, ['page' => $i])) ?>"
                       class="btn-page <?= $i === $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </nav>
        <?php endif; ?>

    <?php endif; ?>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>