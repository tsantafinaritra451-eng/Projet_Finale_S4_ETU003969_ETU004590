<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Nouvelle opération<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Effectuer une opération</h1>
    <p>L'opération est enregistrée automatiquement dès que la saisie est complétée.</p>
</div>

<div class="card" style="max-width:480px;">

    <div id="message"></div>

    <form id="operationForm">
        <?= csrf_field() ?>

        <div class="field">
            <label for="idType">Type d'opération</label>
            <select name="idType" id="idType" required>
                <?php foreach ($types as $type) : ?>
                    <option value="<?= $type['id'] ?>" data-libelle="<?= esc(mb_strtolower($type['libelle'])) ?>">
                        <?= esc(ucfirst($type['libelle'])) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Champ Numéro Destinataire (Affiché pour Retrait) -->
        <div class="field" id="groupDestinataire" style="display: none;">
            <label for="numero_destinataire">Numéro du destinataire</label>
            <input type="text" name="numero_destinataire" id="numero_destinataire" placeholder="Ex: 0341234567">
        </div>

        <!-- Option Inclure les frais (Affiché pour Retrait) -->
        <div class="field" id="groupFraisInclus" style="display: none;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="inclure_frais" id="inclure_frais" value="1" checked>
                Inclure les frais de retrait lors de l'envoi
            </label>
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

    const selectType = document.getElementById('idType');
    const groupDestinataire = document.getElementById('groupDestinataire');
    const groupFraisInclus = document.getElementById('groupFraisInclus');
    const inputDestinataire = document.getElementById('numero_destinataire');
    const inputMontant = document.getElementById('montant');
    const checkboxFrais = document.getElementById('inclure_frais');
    const messageDiv = document.getElementById('message');

    // Affichage dynamique si le type est 'retrait'
    function toggleChampsRetrait() {
        let optionSelectionnee = selectType.options[selectType.selectedIndex];
        let libelle = optionSelectionnee.getAttribute('data-libelle');

        if (libelle === 'retrait') {
            groupDestinataire.style.display = 'block';
            groupFraisInclus.style.display = 'block';
            inputDestinataire.setAttribute('required', 'required');
        } else {
            groupDestinataire.style.display = 'none';
            groupFraisInclus.style.display = 'none';
            inputDestinataire.removeAttribute('required');
        }
    }

    // Gestion des événements de modification pour l'enregistrement automatique
    function declencherSauvegarde() {
        messageDiv.innerText = '';
        messageDiv.className = '';

        clearTimeout(saveTimer);
        saveTimer = setTimeout(function () {
            enregistrer();
        }, 800);
    }

    selectType.addEventListener('change', () => {
        toggleChampsRetrait();
        declencherSauvegarde();
    });

    inputMontant.addEventListener('input', declencherSauvegarde);
    inputDestinataire.addEventListener('input', declencherSauvegarde);
    checkboxFrais.addEventListener('change', declencherSauvegarde);

    toggleChampsRetrait(); // Initialisation au chargement

    function enregistrer() {
        let montantVal = inputMontant.value.trim();
        let optionSelectionnee = selectType.options[selectType.selectedIndex];
        let libelle = optionSelectionnee.getAttribute('data-libelle');

        // Ne rien faire si le montant est vide ou négatif
        if (montantVal === '' || parseFloat(montantVal) <= 0 || enCours) {
            return;
        }

        // Si c'est un retrait, attendre d'avoir saisi le destinataire
        if (libelle === 'retrait' && inputDestinataire.value.trim() === '') {
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

                // Réinitialisation des inputs
                inputMontant.value = '';
                inputDestinataire.value = '';
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