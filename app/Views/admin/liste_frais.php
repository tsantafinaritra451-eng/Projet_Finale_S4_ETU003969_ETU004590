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

    <h2><?= $baremeEnCours ? 'Modifier le bareme' : 'Ajouter un bareme' ?></h2>
    
    <form action="<?= $baremeEnCours ? base_url('/admin/frais/modifier/'.$baremeEnCours['id']) : base_url('/admin/frais/ajout') ?>" method="POST">
        
        <label>Type d'operation :</label>
        <select name="idType" required>
            <option value="1" <?= ($baremeEnCours && $baremeEnCours['idType'] == 1) ? 'selected' : '' ?>>Depot</option>
            <option value="2" <?= ($baremeEnCours && $baremeEnCours['idType'] == 2) ? 'selected' : '' ?>>Retrait</option>
            <option value="3" <?= ($baremeEnCours && $baremeEnCours['idType'] == 3) ? 'selected' : '' ?>>Transfert</option>
        </select>
        
        <input type="number" name="baremeMin" placeholder="Min" value="<?= $baremeEnCours ? $baremeEnCours['baremeMin'] : '' ?>" required>
        <input type="number" name="baremeMax" placeholder="Max" value="<?= $baremeEnCours ? $baremeEnCours['baremeMax'] : '' ?>" required>
        <input type="number" name="valeur_frais" placeholder="Frais" value="<?= $baremeEnCours ? $baremeEnCours['valeur_frais'] : '' ?>" required>
        
        <button type="submit"><?= $baremeEnCours ? 'Modifier' : 'Ajouter' ?></button>
        
        <?php if ($baremeEnCours): ?>
            <a href="<?= base_url('/admin/frais') ?>">Annuler</a>
        <?php endif; ?>
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
                    <td><a href="<?= base_url('/admin/frais/modifier/'.$f['id']) ?>">Modifier</a></td>
                </tr>
            <?php endforeach; ?>

            <?php foreach($fraisRetrait as $f): ?>
                <tr>
                    <td>Retrait</td>
                    <td><?= $f['baremeMin'] ?> - <?= $f['baremeMax'] ?></td>
                    <td><?= $f['valeur_frais'] ?></td>
                    <td><a href="<?= base_url('/admin/frais/supprimer/'.$f['id']) ?>">Supprimer</a></td>
                    <td><a href="<?= base_url('/admin/frais/modifier/'.$f['id']) ?>">Modifier</a></td>
                </tr>
            <?php endforeach; ?>

            <?php foreach($fraisTransfert as $f): ?>
                <tr>
                    <td>Transfert</td>
                    <td><?= $f['baremeMin'] ?> - <?= $f['baremeMax'] ?></td>
                    <td><?= $f['valeur_frais'] ?></td>
                    <td><a href="<?= base_url('/admin/frais/supprimer/'.$f['id']) ?>">Supprimer</a></td>
                    <td><a href="<?= base_url('/admin/frais/modifier/'.$f['id']) ?>">Modifier</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="<?= base_url('/admin/clients') ?>">voir liste clients</a>

</body>
</html>