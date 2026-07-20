<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Clients</title>
</head>
<body>

    <h1>Gestion et Suivi des Clients</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID Utilisateur</th>
                <th>Numéro de Téléphone</th>
                <th>Date d'inscription</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><b><?= $u['numero'] ?></b></td>
                        <td><?= $u['date_creation'] ?></td>
                        <td>
                            <a href="<?= base_url('/admin/clients/situation/'.$u['id']) ?>">Voir situation</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Aucun client enregistré pour le moment.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <a href="<?= base_url('/admin/dashboard') ?>">Retour au Tableau de Bord Admin</a>

</body>
</html>