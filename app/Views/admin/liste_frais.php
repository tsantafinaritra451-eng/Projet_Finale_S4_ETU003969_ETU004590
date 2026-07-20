<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Types d'opérations &amp; frais<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
// Regroupe les trois jeux de barèmes pour n'afficher qu'un seul tableau
$baremes = [];
foreach (['Depot' => $fraisDepot, 'Retrait' => $fraisRetrait, 'Transfert' => $fraisTransfert] as $libelle => $liste) {
    foreach ($liste as $f) {
        $baremes[] = $f + ['libelle' => $libelle];
    }
}
?>

<div class="page-header">
    <h1>Types d'opérations &amp; frais</h1>
    <p>Barèmes de frais appliqués par tranche de montant.</p>
</div>

<div class="card">

    <h2 class="section-title"><?= $baremeEnCours ? 'Modifier le barème' : 'Ajouter un barème' ?></h2>

    <form action="<?= $baremeEnCours ? base_url('admin/frais/modifier/' . $baremeEnCours['id']) : base_url('admin/frais/ajout') ?>" method="POST">

        <div class="form-row">

            <div class="field">
                <label for="idType">Type d'opération</label>
                <select name="idType" id="idType" required>
                    <option value="1" <?= ($baremeEnCours && $baremeEnCours['idType'] == 1) ? 'selected' : '' ?>>Dépôt</option>
                    <option value="2" <?= ($baremeEnCours && $baremeEnCours['idType'] == 2) ? 'selected' : '' ?>>Retrait</option>
                    <option value="3" <?= ($baremeEnCours && $baremeEnCours['idType'] == 3) ? 'selected' : '' ?>>Transfert</option>
                </select>
            </div>

            <div class="field">
                <label for="baremeMin">Montant minimum (Ar)</label>
                <input type="number" name="baremeMin" id="baremeMin" placeholder="Ex : 100"
                       value="<?= $baremeEnCours ? $baremeEnCours['baremeMin'] : '' ?>" required>
            </div>

            <div class="field">
                <label for="baremeMax">Montant maximum (Ar)</label>
                <input type="number" name="baremeMax" id="baremeMax" placeholder="Ex : 1000"
                       value="<?= $baremeEnCours ? $baremeEnCours['baremeMax'] : '' ?>" required>
            </div>

            <div class="field">
                <label for="valeur_frais">Frais (Ar)</label>
                <input type="number" name="valeur_frais" id="valeur_frais" placeholder="Ex : 50"
                       value="<?= $baremeEnCours ? $baremeEnCours['valeur_frais'] : '' ?>" required>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn"><?= $baremeEnCours ? 'Modifier' : 'Ajouter' ?></button>
            <?php if ($baremeEnCours) : ?>
                <a class="btn btn-secondary" href="<?= base_url('admin/frais') ?>">Annuler</a>
            <?php endif; ?>
        </div>

    </form>

</div>

<div class="card">

    <h2 class="section-title">Liste des barèmes</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Tranche de montant</th>
                    <th class="num">Frais</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($baremes)) : ?>
                    <?php foreach ($baremes as $f) : ?>
                        <tr>
                            <td>
                                <span class="badge badge-<?= strtolower($f['libelle']) ?>"><?= $f['libelle'] ?></span>
                            </td>
                            <td class="num" style="text-align:left;">
                                <?= number_format($f['baremeMin'], 0, ',', ' ') ?>
                                &ndash;
                                <?= number_format($f['baremeMax'], 0, ',', ' ') ?> Ar
                            </td>
                            <td class="num"><?= number_format($f['valeur_frais'], 0, ',', ' ') ?> Ar</td>
                            <td>
                                <div class="actions">
                                    <a class="btn-link" href="<?= base_url('admin/frais/modifier/' . $f['id']) ?>">Modifier</a>
                                    <a class="btn-link danger" href="<?= base_url('admin/frais/supprimer/' . $f['id']) ?>"
                                       onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="empty">Aucun barème configuré.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>
