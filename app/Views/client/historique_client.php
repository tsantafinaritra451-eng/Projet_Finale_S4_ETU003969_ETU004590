<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Historique</title>
</head>
<body>

    <h1>Mon Historique d'Opérations</h1>
    <p><b>Votre numéro de compte :</b> <?= $user['numero'] ?></p>
    <h2>Votre Solde Actuel : <?= number_format($solde, 2, ',', ' ') ?> Ar</h2>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type d'Opération</th>
                <th>Montant</th>
                <th>Frais</th>
                <th>Impact sur le Solde</th>
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
                    <td colspan="5">Vous n'avez encore effectué aucune opération.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <a href="<?= base_url('/client/dashboard') ?>">Retour au tableau de bord</a>

</body>
</html>