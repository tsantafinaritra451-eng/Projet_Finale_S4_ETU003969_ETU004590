<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Situation des gains<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Situation des gains</h1>
    <p>Gains perçus par l'opérateur via les frais d'opérations.</p>
</div>

<div class="grid" style="margin-bottom:22px;">
    <div class="stat accent">
        <div class="label">Total général</div>
        <div class="value positive"><?= number_format($totalGeneral, 2, ',', ' ') ?> Ar</div>
    </div>
</div>

<div class="card">

    <h2 class="section-title">Détail par type d'opération</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Type d'opération</th>
                    <th class="num">Total des gains perçus</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($gains)) : ?>
                    <?php foreach ($gains as $gain) : ?>
                        <tr>
                            <td>
                                <span class="badge badge-<?= strtolower($gain['libelle']) ?>">
                                    <?= esc(ucfirst($gain['libelle'])) ?>
                                </span>
                            </td>
                            <td class="num"><?= number_format($gain['totalGains'], 2, ',', ' ') ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="2" class="empty">Aucun gain enregistré pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>Total général</td>
                    <td class="num"><?= number_format($totalGeneral, 2, ',', ' ') ?> Ar</td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>

<?= $this->endSection() ?>
