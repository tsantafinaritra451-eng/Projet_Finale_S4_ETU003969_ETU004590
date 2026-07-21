<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?= base_url('/client/saveEparne') ?>" method="post">
        <?= csrf_field() ?>
        <label for="ajouter pourcentage"></label>
        <input type="text" name="pourcentage" id="">
        <input type="submit" value="ajouter">
 </form>
</body>
</html>