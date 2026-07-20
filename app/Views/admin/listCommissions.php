<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Configuration des Commissions<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Configuration % Commission des Opérateurs</h1>
    <p>Définissez le pourcentage de commission perçu par les autres opérateurs par rapport à nos frais de transfert.</p>
</div>

<div class="card">
    <table class="table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #ccc; text-align: left;">
                <th style="padding: 10px;">Opérateur</th>
                <th style="padding: 10px;">Type</th>
                <th style="padding: 10px;">Commission Actuelle (%)</th>
                <th style="padding: 10px; text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($operateurs)): ?>
                <?php foreach ($operateurs as $op): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px; font-weight: bold;"><?= esc($op['nom']) ?></td>
                        <td style="padding: 10px;"><span class="badge" style="background: #ffcccc; color: #cc0000; padding: 3px 8px; border-radius: 4px;">Externe</span></td>
                        <td style="padding: 10px; font-size: 1.1em; color: #d63384; font-weight: bold;"><?= esc($op['commission_pct']) ?> %</td>
                        <td style="padding: 10px; text-align: right;">
                            <!-- Formulaire en ligne pour une édition rapide -->
                            <form action="<?= base_url('admin/commissions/save') ?>" method="post" style="display: inline-flex; gap: 5px; align-items: center;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $op['id'] ?>">
                                <input type="number" name="commission_pct" step="0.01" min="0" max="100" 
                                       value="<?= $op['commission_pct'] ?>" 
                                       style="width: 80px; padding: 5px;" required>
                                <button type="submit" class="btn" style="padding: 5px 10px;">Mettre à jour</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="padding: 20px; text-align: center;">Aucun opérateur externe configuré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>