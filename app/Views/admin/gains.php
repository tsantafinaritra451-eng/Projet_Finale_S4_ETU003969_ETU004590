<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gains de l'Opérateur</title>
</head>
<body>

    <h1>Rapport des Gains (Frais d'opérations)</h1>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Type d'Opération</th>
                <th>Total des Gains Perçus</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($gains)) : ?>
                <?php foreach ($gains as $gain) : ?>
                    <tr>
                        <td><?= esc(ucfirst($gain['libelle'])) ?></td>
                        <td><?= number_format($gain['totalGains'], 2, ',', ' ') ?> Ariary</td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="2">Aucun gain enregistré pour le moment.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th><strong>Total Général</strong></th>
                <th><strong><?= number_format($totalGeneral, 2, ',', ' ') ?> Ariary</strong></th>
            </tr>
        </tfoot>
    </table>

</body>
</html>