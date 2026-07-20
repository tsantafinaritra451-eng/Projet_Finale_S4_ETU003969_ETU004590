<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Tableau de bord Client<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Bienvenue sur votre espace client</h1>
    <p>Effectuez vos opérations et suivez vos mouvements.</p>
</div>

<div class="grid">

    <a class="tile" href="<?= base_url('operation') ?>">
        <span class="tile-icon">
            <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        </span>
        <h2>Effectuer une opération</h2>
        <p>Réaliser un dépôt, un retrait ou un transfert.</p>
    </a>

    <a class="tile" href="<?= base_url('client/historique') ?>">
        <span class="tile-icon">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
        </span>
        <h2>Mon historique</h2>
        <p>Consulter mon solde et l'ensemble de mes opérations.</p>
    </a>

</div>

<?= $this->endSection() ?>
