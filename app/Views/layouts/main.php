<?php
$session = session();
$isAdmin = (bool) $session->get('is_admin');
$numero  = (string) $session->get('numero');
$uri     = '/' . trim(uri_string(), '/');

// Marque l'onglet courant : correspondance exacte ou sous-page (ex: /prefix/form)
$actif = static fn(string $base): string => ($uri === $base || str_starts_with($uri, $base . '/')) ? ' active' : '';

// Icônes du menu (SVG, sans dépendance externe)
$icones = [
    'dashboard' => '<path d="M3 3h7v8H3zM14 3h7v5h-7zM14 11h7v10h-7zM3 14h7v7H3z"/>',
    'prefix'    => '<path d="M4 7h16M4 12h16M4 17h10"/>',
    'frais'     => '<path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
    'gains'     => '<path d="M3 17l6-6 4 4 7-7"/><path d="M14 8h6v6"/>',
    'clients'   => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/>',
    'operation' => '<path d="M12 5v14M5 12h14"/>',
    'historique'=> '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
];

$liens = $isAdmin
    ? [
        '/admin/dashboard' => ['Tableau de bord', 'dashboard'],
        '/prefix'          => ['Préfixes', 'prefix'],
        '/admin/frais'     => ['Types &amp; frais', 'frais'],
        '/admin/gains'     => ['Gains', 'gains'],
        '/admin/clients'   => ['Clients', 'clients'],
    ]
    : [
        '/client/dashboard'  => ['Tableau de bord', 'dashboard'],
        '/operation'         => ['Nouvelle opération', 'operation'],
        '/client/historique' => ['Mon historique', 'historique'],
    ];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?: 'Telmo' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>

<body>

    <aside class="sidebar">

        <div class="brand">
            <span class="mark">T</span>
            <span>
                <span class="name">Telmo</span><br>
                <span class="role"><?= $isAdmin ? 'Espace opérateur' : 'Espace client' ?></span>
            </span>
        </div>

        <div class="menu-label">Menu</div>

        <nav>
            <?php foreach ($liens as $url => [$libelle, $icone]) : ?>
                <a href="<?= base_url($url) ?>" class="<?= trim($actif($url)) ?>">
                    <svg viewBox="0 0 24 24"><?= $icones[$icone] ?></svg>
                    <?= $libelle ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="sidebar-footer">

            <div class="account">
                <span class="avatar"><?= $isAdmin ? 'OP' : esc(substr($numero, -2)) ?></span>
                <span>
                    <span class="who"><?= $isAdmin ? 'Opérateur' : esc($numero) ?></span><br>
                    <span class="status">Connecté</span>
                </span>
            </div>

            <a class="logout" href="<?= base_url('logout') ?>">Déconnexion</a>

        </div>

    </aside>

    <main class="main">
        <div class="container">

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>

        </div>
    </main>

</body>

</html>
