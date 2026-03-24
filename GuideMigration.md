# Guide de migration : HTML statique → vues PHP MVC

## Principe

Chaque fichier `.html` statique devient une vue `.php` dans `app/views/`.
C'est le contrôleur qui charge les données et passe les variables à la vue.
La vue ne fait aucune requête SQL – elle affiche seulement ce qu'elle reçoit.

```
[Navigateur] → router.php → Contrôleur → Modèle (SQL) → Vue PHP → HTML final
```

---

## Étape 1 – Créer le dossier de vues

```
app/views/
├── layout/
│   ├── header.php        ← déjà présent ✓
│   └── footer.php        ← déjà présent ✓
├── auth/
│   └── login.php         ← vient de login.html
├── entreprises/
│   ├── index.php         ← vient de entreprises.html
│   ├── show.php          ← vient de entreprise-detail.html
│   ├── create.php        ← vient de entreprise-creer.html
│   └── edit.php          ← nouveau
├── offres/
│   ├── index.php         ← vient de offres.html
│   ├── show.php          ← nouveau
│   ├── create.php        ← nouveau
│   └── edit.php          ← nouveau
├── etudiants/
│   ├── index.php         ← nouveau
│   ├── show.php          ← nouveau
│   ├── create.php        ← nouveau
│   └── edit.php          ← nouveau
├── candidatures/
│   └── index.php         ← vient de candidatures.html
├── wishlist/
│   └── index.php         ← vient de wishlist.html
├── dashboard/
│   └── index.php         ← nouveau
├── statistiques/
│   └── index.php         ← vient de statistiques.html
├── mentions-legales/
│   └── index.php         ← vient de mentions-legales.html
└── errors/
    ├── 403.php
    └── 404.php
```

---

## Étape 2 – Anatomie d'une vue PHP

Chaque vue suit exactement ce squelette, rien de plus :

```php
<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="container">
  <h1><?= htmlspecialchars($variable, ENT_QUOTES, 'UTF-8') ?></h1>

  <!-- Affichage des données passées par le contrôleur -->
  <?php foreach ($items as $item): ?>
    <p><?= htmlspecialchars($item['nom'], ENT_QUOTES, 'UTF-8') ?></p>
  <?php endforeach; ?>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
```

Règle absolue : JAMAIS de `new PDO(...)` ou de requête SQL dans une vue.

---

## Étape 3 – Anatomie du contrôleur correspondant

```php
// app/controllers/entreprisecontroller.php

public function index(): void
{
    // 1. Récupérer les données via le modèle
    $entreprises = $this->entrepriseModel->search($_GET['search'] ?? '', 10, 0);

    // 2. Charger la vue – les variables locales sont automatiquement disponibles
    require __DIR__ . '/../views/entreprises/index.php';
}
```

---

## Étape 4 – Câbler les routes dans router.php

Exemple de structure de routeur minimaliste :

```php
// public/router.php  (ou router.php à la racine selon votre projet)

require_once __DIR__ . '/../app/controllers/entreprisecontroller.php';
require_once __DIR__ . '/../app/controllers/etudiantcontroller.php';
// ... autres contrôleurs

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Retirer le préfixe du sous-dossier si nécessaire
// $uri = str_replace('/mon-sous-dossier', '', $uri);

// Extraire les segments de l'URL
$segments = explode('/', trim($uri, '/'));
// /entreprises/5/modifier → ['entreprises', '5', 'modifier']

$controller = $segments[0] ?? 'home';
$id         = isset($segments[1]) && is_numeric($segments[1]) ? (int)$segments[1] : null;
$action     = $segments[2] ?? null;

match(true) {

    // Entreprises
    $controller === 'entreprises' && $id === null
        => (new EnterpriseController())->index(),

    $controller === 'entreprises' && $id !== null && $action === null
        => (new EnterpriseController())->show($id),

    $controller === 'entreprises' && $action === 'modifier'
        => (new EnterpriseController())->edit($id),

    $controller === 'entreprises' && $action === 'supprimer' && $method === 'POST'
        => (new EnterpriseController())->delete($id),

    // Étudiants
    $controller === 'etudiants' && $id === null
        => (new EtudiantController())->index(),

    $controller === 'etudiants' && $id !== null && $action === null
        => (new EtudiantController())->show($id),

    $controller === 'etudiants' && $action === 'modifier'
        => (new EtudiantController())->edit($id),

    $controller === 'etudiants' && $action === 'supprimer' && $method === 'POST'
        => (new EtudiantController())->delete($id),

    // 404 par défaut
    default => (function() {
        http_response_code(404);
        require __DIR__ . '/../app/views/errors/404.php';
    })()
};
```

---

## Étape 5 – Migration fichier par fichier

Pour chaque fichier `.html` statique, faire ces 4 actions dans l'ordre :

### 1. Identifier les données dynamiques
Ouvrir le `.html` et repérer tout ce qui est en dur mais devrait venir de la BDD.
Ex dans `entreprise-detail.html` : le nom, la description, les offres, les notes.

### 2. Créer la méthode dans le contrôleur
```php
public function show(int $id): void
{
    $entreprise     = $this->entrepriseModel->findById($id);
    $offres         = $this->offreModel->findByEntreprise($id);
    $evaluations    = $this->evaluationModel->findByEntreprise($id);
    $moyenne        = $this->evaluationModel->moyenneByEntreprise($id);

    require __DIR__ . '/../views/entreprises/show.php';
}
```

### 3. Créer le fichier de vue PHP
- Copier le HTML existant dans `app/views/[section]/[action].php`
- Remplacer toutes les données en dur par `<?= htmlspecialchars($variable, ENT_QUOTES, 'UTF-8') ?>`
- Supprimer le `<head>` complet et remplacer par `<?php require '../layout/header.php'; ?>`
- Supprimer le `<footer>` et remplacer par `<?php require '../layout/footer.php'; ?>`
- Pour les listes : `<?php foreach ($offres as $offre): ?> ... <?php endforeach; ?>`

### 4. Ajouter la route dans router.php
Ajouter la ligne `match` correspondante (voir étape 4).

---

## Étape 6 – Bloquer les fichiers HTML statiques

Une fois toutes les vues migrées, bloquer l'accès direct aux `.html` dans `.htaccess` :

```apache
# Bloquer l'accès direct aux fichiers HTML statiques à la racine
<FilesMatch "\.html$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

Ou les supprimer simplement si le routeur couvre toutes les URLs.

---

## Checklist de migration (par vue)

- [ ] Données en dur remplacées par des variables PHP
- [ ] `htmlspecialchars()` sur TOUTES les variables affichées
- [ ] Header/footer via `require` du layout
- [ ] Aucune requête SQL dans la vue
- [ ] Route câblée dans router.php
- [ ] Token CSRF dans tous les formulaires POST
- [ ] Vérification de rôle dans le contrôleur
- [ ] Fichier HTML statique supprimé ou bloqué