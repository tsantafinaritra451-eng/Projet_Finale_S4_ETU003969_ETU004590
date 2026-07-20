<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Préfixes</title>
</head>
<body>

    <h1>Gestion des Préfixes</h1>

    <?php if (session()->getFlashdata('success')) : ?>
        <p style="color: green;"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <p style="color: red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <p>
        <a href="<?= base_url('prefix/form') ?>">+ Ajouter un préfixe</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Valeur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($prefixes) && is_array($prefixes)) : ?>
                <?php foreach ($prefixes as $p) : ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= esc($p['valeur']) ?></td>
                        <td>
                            <a href="<?= base_url('prefix/form/' . $p['id']) ?>">Modifier</a> |
                            <a href="<?= base_url('prefix/delete/' . $p['id']) ?>" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">Aucun préfixe trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="<?= base_url('/admin/dashboard') ?>">Retour au Tableau de Bord Admin</a>
     
</body>
</html>