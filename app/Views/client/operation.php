<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Nouvelle opération<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Effectuer une opération</h1>
    <p>L'opération est enregistrée automatiquement dès que vous quittez le champ montant.</p>
</div>

<div class="card" style="max-width:480px;">

    <div id="message"></div>

    <form id="operationForm">
        <?= csrf_field() ?>

        <div class="field">
            <label for="idType">Type d'opération</label>
            <select name="idType" id="idType" required>
                <?php foreach ($types as $type) : ?>
                    <option value="<?= $type['id'] ?>"><?= esc(ucfirst($type['libelle'])) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label for="montant">Montant (Ar)</label>
            <input type="number" step="0.01" name="montant" id="montant" placeholder="Entrer le montant" required>
            <p class="hint">Les frais applicables sont calculés selon le barème en vigueur.</p>
        </div>

    </form>

</div>

<script>
    document.getElementById('montant').addEventListener('blur', function () {
        let montantInput = this;
        let montantVal = montantInput.value.trim();
        let messageDiv = document.getElementById('message');

        messageDiv.innerText = '';
        messageDiv.className = '';

        if (montantVal === '' || parseFloat(montantVal) <= 0) {
            return;
        }

        let formData = new FormData(document.getElementById('operationForm'));

        fetch('<?= base_url('operation/save') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                messageDiv.className = 'alert alert-success';
                messageDiv.innerText = data.message;

                montantInput.value = '';
            } else {
                messageDiv.className = 'alert alert-error';
                messageDiv.innerText = data.message;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            messageDiv.className = 'alert alert-error';
            messageDiv.innerText = "Une erreur est survenue lors de l'enregistrement.";
        });
    });
</script>

<?= $this->endSection() ?>
