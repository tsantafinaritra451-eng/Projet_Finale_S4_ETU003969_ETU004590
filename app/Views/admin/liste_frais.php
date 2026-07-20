<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Frais</title>
</head>
<body>

    <h1>Configuration des Frais Operateur</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <p><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <h2>Ajouter un bareme</h2>
    <form action="<?= base_url('/admin/frais/ajout') ?>" method="POST">
        <label>Type d'operation :</label>
        <select name="idType" required>
            <option value="1">Depot</option>
            <option value="2">Retrait</option>
            <option value="3">Transfert</option>
        </select>
        
        <input type="number" name="baremeMin" placeholder="Min" required>
        <input type="number" name="baremeMax" placeholder="Max" required>
        <input type="number" name="valeur_frais" placeholder="Frais" required>
        <button type="submit">Ajouter</button>
    </form>

    <h2>Liste globale des baremes</h2>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Tranche</th>
                <th>Frais</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($fraisDepot as $f): ?>
                <tr>
                    <td>Depot</td>
                    <td><?= $f['baremeMin'] ?> - <?= $f['baremeMax'] ?></td>
                    <td><?= $f['valeur_frais'] ?></td>
                    <td><a href="<?= base_url('/admin/frais/supprimer/'.$f['id']) ?>">Supprimer</a></td>
                </tr>
            <?php endforeach; ?>

            <?php foreach($fraisRetrait as $f): ?>
                <tr>
                    <td>Retrait</td>
                    <td><?= $f['baremeMin'] ?> - <?= $f['baremeMax'] ?></td>
                    <td><?= $f['valeur_frais'] ?></td>
                    <td><a href="<?= base_url('/admin/frais/supprimer/'.$f['id']) ?>">Supprimer</a></td>
                </tr>
            <?php endforeach; ?>

            <?php foreach($fraisTransfert as $f): ?>
                <tr>
                    <td>Transfert</td>
                    <td><?= $f['baremeMin'] ?> - <?= $f['baremeMax'] ?></td>
                    <td><?= $f['valeur_frais'] ?></td>
                    <td><a href="<?= base_url('/admin/frais/supprimer/'.$f['id']) ?>">Supprimer</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>