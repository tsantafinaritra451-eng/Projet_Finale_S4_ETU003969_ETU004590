<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Opération</title>
</head>
<body>

    <h2>Effectuer une Opération</h2>

    <div id="message" style="margin-bottom: 15px; font-weight: bold;"></div>

    <form id="operationForm">
        <?= csrf_field() ?>

        <p>
            <label for="idType">Type d'opération :</label>
            <select name="idType" id="idType" required>
                <?php foreach ($types as $type) : ?>
                    <option value="<?= $type['id'] ?>"><?= esc($type['libelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="montant">Montant :</label>
            <input type="number" step="0.01" name="montant" id="montant" placeholder="Entrer le montant" required>
        </p>
    </form>

    <script>
        document.getElementById('montant').addEventListener('blur', function () {
            let montantInput = this;
            let montantVal = montantInput.value.trim();
            let messageDiv = document.getElementById('message');

            messageDiv.innerText = '';

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
                    messageDiv.style.color = 'green';
                    messageDiv.innerText = data.message;
                    
                    montantInput.value = '';
                } else {
                    messageDiv.style.color = 'red';
                    messageDiv.innerText = data.message;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                messageDiv.style.color = 'red';
                messageDiv.innerText = "Une erreur est survenue lors de l'enregistrement.";
            });
        });
    </script>

</body>
</html>