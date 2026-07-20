<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($prefix) ? 'Modifier' : 'Ajouter' ?> un Préfixe</title>
</head>
<body>

    <h1><?= isset($prefix) ? 'Modifier le préfixe' : 'Ajouter un nouveau préfixe' ?></h1>

    <?php if (session()->getFlashdata('error')) : ?>
        <p style="color: red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form action="<?= base_url('prefix/save') ?>" method="post">
        <?= csrf_field() ?>

        <!-- Champ caché stockant l'ID s'il s'agit d'une modification -->
        <input type="hidden" name="id" value="<?= isset($prefix) ? $prefix['id'] : '' ?>">

        <p>
            <label for="valeur">Préfixe :</label>
            <input type="text" name="valeur" id="valeur" value="<?= isset($prefix) ? esc($prefix['valeur']) : old('valeur') ?>" placeholder="Ex: 032" required>
        </p>

        <p>
            <input type="submit" value="<?= isset($prefix) ? 'Mettre à jour' : 'Enregistrer' ?>">
        </p>
    </form>

</body>
</html>