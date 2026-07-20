<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Situation Client - <?= $user['numero'] ?></title>
</head>
<body>

    <h1>Situation du Compte</h1>
    <p><b>Client concerné :</b> Unique Numéro [ <?= $user['numero'] ?> ]</p>
    
    <h2>Solde Actuel : <span style="color: green;"><?= number_format($solde, 2, ',', ' ') ?> Ar</span></h2>

    <h3>Historique Global des Opérations</h3>
    
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Date & Heure</th>
                <th>Type d'Opération</th>
                <th>Montant Brut</th>
                <th>Frais Appliqués</th>
                <th>Impact Réel Solde</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($historique)): ?>
                <?php foreach($historique as $h): ?>
                    <tr>
                        <td><?= $h['date'] ?></td>
                        <td><?= strtoupper($h['type']) ?></td>
                        <td><?= number_format($h['montant'], 2, ',', ' ') ?> Ar</td>
                        <td><?= number_format($h['frais'], 2, ',', ' ') ?> Ar</td>
                        <td style="font-weight: bold; color: <?= $h['impact'] >= 0 ? 'green' : 'red' ?>;">
                            <?= $h['impact'] >= 0 ? '+' : '' ?><?= number_format($h['impact'], 2, ',', ' ') ?> Ar
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Aucune transaction effectuée par ce numéro.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br><br>
    <a href="<?= base_url('/admin/clients') ?>">← Retourner à la liste des clients</a>

</body>
</html>