<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= isset($prefix) ? 'Modifier' : 'Ajouter' ?> un préfixe<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1><?= isset($prefix) ? 'Modifier le préfixe' : 'Ajouter un préfixe' ?></h1>
    <p>Les numéros commençant par ce préfixe seront acceptés à la connexion.</p>
</div>

<div class="card" style="max-width:440px;">

    <form action="<?= base_url('prefix/save') ?>" method="post">
        <?= csrf_field() ?>

        <!-- Champ caché stockant l'ID s'il s'agit d'une modification -->
        <input type="hidden" name="id" value="<?= isset($prefix) ? $prefix['id'] : '' ?>">

        <div class="field">
            <label for="valeur">Préfixe</label>
            <input type="text" name="valeur" id="valeur"
                   value="<?= isset($prefix) ? esc($prefix['valeur']) : old('valeur') ?>"
                   placeholder="Ex : 032" required autofocus>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn"><?= isset($prefix) ? 'Mettre à jour' : 'Enregistrer' ?></button>
            <a class="btn btn-secondary" href="<?= base_url('prefix') ?>">Annuler</a>
        </div>
    </form>

</div>

<?= $this->endSection() ?>
