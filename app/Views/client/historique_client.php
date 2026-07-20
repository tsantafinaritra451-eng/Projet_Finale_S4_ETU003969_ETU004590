<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Mon historique<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Mon historique</h1>
    <p>Compte : <strong><?= esc($user['numero']) ?></strong></p>
</div>

<div class="grid" style="margin-bottom:22px;">

    <div class="stat">
        <div class="label">Solde actuel</div>
        <div class="value <?= $solde >= 0 ? 'positive' : 'negative' ?>">
            <?= number_format($solde, 2, ',', ' ') ?> Ar
        </div>
    </div>

    <div class="stat accent">
        <div class="label">Opérations</div>
        <div class="value"><?= count($historique) ?></div>
    </div>

</div>

<div class="card">

    <h2 class="section-title">Mes opérations</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th class="num">Montant</th>
                    <th class="num">Frais</th>
                    <th class="num">Impact sur le solde</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($historique)) : ?>
                    <?php foreach ($historique as $h) : ?>
                        <tr>
                            <td><?= esc($h['date']) ?></td>
                            <td>
                                <span class="badge badge-<?= strtolower($h['type']) ?>">
                                    <?= esc(ucfirst($h['type'])) ?>
                                </span>
                            </td>
                            <td class="num"><?= number_format($h['montant'], 2, ',', ' ') ?> Ar</td>
                            <td class="num"><?= number_format($h['frais'], 2, ',', ' ') ?> Ar</td>
                            <td class="num <?= $h['impact'] >= 0 ? 'positive' : 'negative' ?>">
                                <?= $h['impact'] >= 0 ? '+' : '' ?><?= number_format($h['impact'], 2, ',', ' ') ?> Ar
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="empty">Vous n'avez encore effectué aucune opération.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>
