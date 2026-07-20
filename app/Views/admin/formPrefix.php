<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= isset($prefix) ? 'Modifier' : 'Ajouter' ?> un préfixe<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1><?= isset($prefix) ? 'Modifier le préfixe' : 'Ajouter un préfixe' ?></h1>
    <p>Associez ce préfixe à un opérateur pour gérer les règles de transfert.</p>
</div>

<div class="card" style="max-width:440px;">

    <form action="<?= base_url('prefix/save') ?>" method="post">
        <?= csrf_field() ?>

        <input type="hidden" name="id" value="<?= isset($prefix) ? $prefix['id'] : '' ?>">

        <div class="field">
            <label for="idOperateur">Opérateur</label>
            <select name="idOperateur" id="idOperateur" required>
                <option value="">-- Choisir un opérateur --</option>
                <?php foreach ($operateurs as $op): ?>
                    <option value="<?= $op['id'] ?>" <?= (isset($prefix) && $prefix['idOperateur'] == $op['id']) ? 'selected' : '' ?>>
                        <?= esc($op['nom']) ?> <?= $op['est_interne'] ? '(Interne)' : '(Externe)' ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

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