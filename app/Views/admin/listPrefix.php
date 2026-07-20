<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Préfixes<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Gestion des préfixes</h1>
    <p>Préfixes de numéro reconnus par l'opérateur lors de la connexion.</p>
</div>

<p><a class="btn" href="<?= base_url('prefix/form') ?>">Ajouter un préfixe</a></p>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Valeur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($prefixes) && is_array($prefixes)) : ?>
                    <?php foreach ($prefixes as $p) : ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><strong><?= esc($p['valeur']) ?></strong></td>
                            <td>
                                <div class="actions">
                                    <a class="btn-link" href="<?= base_url('prefix/form/' . $p['id']) ?>">Modifier</a>
                                    <a class="btn-link danger" href="<?= base_url('prefix/delete/' . $p['id']) ?>"
                                       onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="empty">Aucun préfixe configuré.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
