<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Commissions à envoyer<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Situation des commissions à envoyer</h1>
    <p>Visualisation exclusive des commissions dues aux réseaux partenaires.</p>
</div>

<div class="grid" style="margin-bottom:22px;">
    <div class="stat accent" style="background: #fff5e6; border-left: 4px solid #ff9900;">
        <div class="label" style="color: #555;">Total global des commissions à verser</div>
        <div class="value" style="color: #ff9900; font-weight: bold;">
            <?= number_format($totalCommissionsGlobal, 2, ',', ' ') ?> Ar
        </div>
    </div>
</div>

<div class="card">
    <h2 class="section-title" style="color: #333; border-bottom: 2px solid #eee; padding-bottom: 8px;">
        🚚 Répartition des commissions par opérateur
    </h2>

    <div class="table-wrap">
        <table class="table" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #ccc; text-align: left;">
                    <th style="padding: 10px;">Opérateur Réseau</th>
                    <th style="padding: 10px; text-align: center;">Nombre de transactions</th>
                    <th style="padding: 10px;" class="num">Total Commissions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($commissionsSortantes)) : ?>
                    <?php foreach ($commissionsSortantes as $flux) : ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px; font-weight: bold;">
                                <span class="badge" style="background: #fff5e6; color: #ff9900; padding: 4px 8px; border-radius: 4px;">
                                    <?= esc($flux['operateur_nom']) ?>
                                </span>
                            </td>
                            <td style="padding: 10px; text-align: center; font-weight: bold;">
                                <?= $flux['nombre_transactions'] ?>
                            </td>
                            <td style="padding: 10px; font-size: 1.1em; font-weight: bold; color: #ff9900;" class="num">
                                <?= number_format($flux['total_commissions'], 2, ',', ' ') ?> Ar
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="empty" style="text-align: center; padding: 20px;">Aucune commission à envoyer.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>