<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Tableau de bord Opérateur<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Tableau de bord</h1>
    <p>Configuration de l'opérateur et suivi de l'activité.</p>
</div>

<div class="grid">

    <a class="tile" href="<?= base_url('prefix') ?>">
        <span class="tile-icon">
            <svg viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
        </span>
        <h2>Préfixes</h2>
        <p>Configurer les préfixes valides de l'opérateur (ex : 033, 037).</p>
    </a>

    <a class="tile" href="<?= base_url('admin/frais') ?>">
        <span class="tile-icon">
            <svg viewBox="0 0 24 24"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </span>
        <h2>Types d'opérations &amp; frais</h2>
        <p>Gérer les dépôts, retraits et transferts, et leurs barèmes de frais par tranche.</p>
    </a>

    <a class="tile" href="<?= base_url('admin/gains') ?>">
        <span class="tile-icon">
            <svg viewBox="0 0 24 24"><path d="M3 17l6-6 4 4 7-7"/><path d="M14 8h6v6"/></svg>
        </span>
        <h2>Situation des gains</h2>
        <p>Consulter les gains perçus via les frais de retrait et de transfert.</p>
    </a>

    <a class="tile" href="<?= base_url('admin/clients') ?>">
        <span class="tile-icon">
            <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/></svg>
        </span>
        <h2>Comptes clients</h2>
        <p>Voir la situation des clients : solde et historique des opérations.</p>
    </a>

     <a class="tile" href="<?= base_url('admin/commissions') ?>">
        <span class="tile-icon">
            <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/></svg>
        </span>
        <h2>Configuration % en plus de commissions</h2>
        <p>Configuration % en plus de commissions pour les transferts vers les autres opérateurs </p>
    </a>

        <a class="tile" href="<?= base_url('admin/montants_a_envoyer') ?>">
        <span class="tile-icon">
            <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/></svg>
        </span>
        <h2>Situation des montants à envoyer </h2>
        <p>Situation des montants à envoyer à chaque opérateur</p>
    </a>
</div>

<?= $this->endSection() ?>
