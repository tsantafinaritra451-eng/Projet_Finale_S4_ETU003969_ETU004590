<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Situation des gains<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Situation des gains</h1>
    <p>Visualisation des gains perçus par Telmo et des commissions générées pour les autres réseaux.</p>
</div>



<div class="card" style="margin-bottom: 25px;">
    <h2 class="section-title" style="color: #333; border-bottom: 2px solid #eee; padding-bottom: 8px;">
         Nos Gains Réels (Telmo)
    </h2>
    <p><small>Gains nets basés sur l'intégralité de nos frais internes (Dépôts, Retraits, Transferts).</small></p>

    <div class="table-wrap">
        <table class="table" style="width:100%;">
            <thead>
                <tr>
                    <th>Type d'opération</th>
                    <th class="num" style="text-align: right;">Total des gains perçus</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($nosGains)) : ?>
                    <?php foreach ($nosGains as $gain) : ?>
                        <tr>
                            <td>
                                <span class="badge badge-<?= strtolower($gain['libelle']) ?>" style="text-transform: uppercase; font-weight: bold;">
                                    <?= esc($gain['libelle']) ?>
                                </span>
                            </td>
                            <td class="num" style="text-align: right; font-weight: bold;">
                                <?= number_format($gain['totalGains'], 2, ',', ' ') ?> Ar
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="2" class="empty" style="text-align: center; padding: 15px;">Aucun gain interne enregistré.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="border-top: 2px solid #333;">
                    <th style="text-align: left; padding-top: 10px;">Gain total Telmo</th>
                    <td class="num" style="text-align: right; padding-top: 10px; font-weight: bold; font-size: 1.15em;">
                        <?= number_format($totalTelmo, 2, ',', ' ') ?> Ar
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="card">
    <h2 class="section-title" style="color: #333; border-bottom: 2px solid #eee; padding-bottom: 8px;">
         Part de Commission des Autres Opérateurs
    </h2>
    <p><small>Gains calculés sur le pourcentage de commission appliqué directement sur nos frais lors des transferts sortants.</small></p>

    <div class="table-wrap">
        <table class="table" style="width:100%;">
            <thead>
                <tr>
                    <th>Opérateur Tiers</th>
                    <th class="num" style="text-align: right;">Commissions reversées</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($gainsAutres)) : ?>
                    <?php foreach ($gainsAutres as $gain) : ?>
                        <tr>
                            <td>
                                <span class="badge" style="background: #ffe6e6; color: #cc0000; padding: 4px 8px; border-radius: 4px; font-weight: bold;">
                                    <?= esc($gain['libelle']) ?>
                                </span>
                            </td>
                            <td class="num" style="text-align: right; color: #cc0000; font-weight: bold;">
                                <?= number_format($gain['totalGains'], 2, ',', ' ') ?> Ar
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="2" class="empty" style="text-align: center; padding: 15px;">Aucune commission générée pour d'autres opérateurs.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="border-top: 2px solid #333;">
                    <th style="text-align: left; padding-top: 10px;">Total reversé aux tiers</th>
                    <td class="num" style="text-align: right; padding-top: 10px; color: #cc0000; font-weight: bold; font-size: 1.15em;">
                        <?= number_format($totalAutres, 2, ',', ' ') ?> Ar
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?= $this->endSection() ?>