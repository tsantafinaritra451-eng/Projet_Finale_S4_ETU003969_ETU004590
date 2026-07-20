<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>

<body>

    <div class="auth">
        <div class="auth-box">

            <div class="auth-brand">
                <span class="mark">T</span>
                Telmo
            </div>

            <div class="card">

                <h1>Connexion</h1>
                <p class="subtitle">Saisissez votre numéro pour accéder à votre compte.</p>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>

                <form action="<?= base_url('user/login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="field">
                        <label for="num">Numéro de téléphone</label>
                        <input type="tel" name="num" id="num" placeholder="Numéro à 10 chiffres" required autofocus>
                    </div>

                    <button type="submit" class="btn">Se connecter</button>
                </form>

                <div class="auth-note">
                    <?php if (!empty($prefix)) : ?>
                        Préfixes acceptés :
                        <?php foreach ($prefix as $i => $p) : ?>
                            <code><?= esc($p['valeur']) ?></code><?= $i < count($prefix) - 1 ? ' ' : '' ?>
                        <?php endforeach; ?>
                        <br>
                    <?php endif; ?>
                    Accès opérateur : <code>0000000000</code>
                </div>

            </div>

        </div>
    </div>

</body>

</html>
