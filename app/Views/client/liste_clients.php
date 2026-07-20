<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Comptes clients<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Comptes clients</h1>
    <p>Suivi des clients enregistrés sur la plateforme.</p>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Numéro de téléphone</th>
                    <th>Date d'inscription</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)) : ?>
                    <?php foreach ($users as $u) : ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><strong><?= esc($u['numero']) ?></strong></td>
                            <td><?= esc($u['date_creation']) ?></td>
                            <td>
                                <a class="btn-link" href="<?= base_url('admin/clients/situation/' . $u['id']) ?>">
                                    Voir la situation
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="empty">Aucun client enregistré pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
