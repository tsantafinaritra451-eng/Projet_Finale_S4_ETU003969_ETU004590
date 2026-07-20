<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>

    <?php if (session()->getFlashdata('error')) : ?>
        <p style="color: red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form action="<?= base_url('user/login') ?>" method="post">
        <?= csrf_field() ?>
        <p>Entrer un numéro : 
            <input type="tel" name="num" id="num" required>
        </p>
        <input type="submit" value="OK">
    </form>

</body>
</html>