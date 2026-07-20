<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Nouvelle opération<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Effectuer une opération</h1>
    <p>L'opération est enregistrée automatiquement dès que vous avez fini de saisir le montant.</p>
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
    let saveTimer = null;
    let enCours = false;

    document.getElementById('montant').addEventListener('input', function () {
        let montantInput = this;
        let messageDiv = document.getElementById('message');

        messageDiv.innerText = '';
        messageDiv.className = '';

        clearTimeout(saveTimer);
        saveTimer = setTimeout(function () {
            enregistrer(montantInput, messageDiv);
        }, 800);
    });

    function enregistrer(montantInput, messageDiv) {
        let montantVal = montantInput.value.trim();

        if (montantVal === '' || parseFloat(montantVal) <= 0 || enCours) {
            return;
        }

        enCours = true;
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
        })
        .finally(() => {
            enCours = false;
        });
    }
</script>

<?= $this->endSection() ?>
