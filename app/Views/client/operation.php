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

        <!-- Champ Numéro Destinataire Unique (Pour Retrait uniquement) -->
        <div class="field" id="groupDestinataireUnique" style="display: none;">
            <label for="numero_destinataire">Numéro du destinataire</label>
            <input type="text" name="numero_destinataire" id="numero_destinataire" placeholder="Ex: 0341234567">
        </div>

        <!-- Section Numéros Multiples avec bouton "+" (Pour Transfert) -->
        <div class="field" id="groupDestinatairesMultiples" style="display: none;">
            <label>Numéro(s) destinataire(s)</label>
            <div id="containerNumeros">
                <div class="row-numero" style="display: flex; gap: 8px; margin-bottom: 8px;">
                    <input type="text" name="numeros_destinataires[]" class="input-destinataire-multi" placeholder="Ex: 0341234567">
                    <button type="button" id="btnAddNumero" style="padding: 0 12px; cursor: pointer;">+</button>
                </div>
            </div>
            <p class="hint">Le montant saisi sera divisé équitablement entre les destinataires (du même opérateur).</p>
        </div>

        <!-- Option Inclure les frais (Pour Retrait) -->
        <div class="field" id="groupFraisInclus" style="display: none;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="inclure_frais" id="inclure_frais" value="1" checked>
                Inclure les frais de retrait lors de l'envoi
            </label>
        </div>

        <div class="field">
            <label for="montant">Montant total (Ar)</label>
            <input type="number" step="0.01" name="montant" id="montant" placeholder="Entrer le montant total" required>
            <p class="hint">Les frais applicables sont calculés selon le barème en vigueur.</p>
        </div>

    </form>

</div>

<script>
    let saveTimer = null;
    let enCours = false;

    const selectType = document.getElementById('idType');
    const groupDestinataireUnique = document.getElementById('groupDestinataireUnique');
    const groupDestinatairesMultiples = document.getElementById('groupDestinatairesMultiples');
    const groupFraisInclus = document.getElementById('groupFraisInclus');
    const inputDestinataire = document.getElementById('numero_destinataire');
    const inputMontant = document.getElementById('montant');
    const checkboxFrais = document.getElementById('inclure_frais');
    const messageDiv = document.getElementById('message');
    const containerNumeros = document.getElementById('containerNumeros');
    const btnAddNumero = document.getElementById('btnAddNumero');

    // Ajout dynamique d'un champ destinataire avec le bouton "+"
    btnAddNumero.addEventListener('click', function() {
        const div = document.createElement('div');
        div.className = 'row-numero';
        div.style.cssText = 'display: flex; gap: 8px; margin-bottom: 8px;';
        div.innerHTML = `
            <input type="text" name="numeros_destinataires[]" class="input-destinataire-multi" placeholder="Ex: 0341234567">
            <button type="button" class="btn-remove-numero" style="padding: 0 12px; cursor: pointer; background: #ff4d4d; color: white; border: none; border-radius: 4px;">-</button>
        `;
        containerNumeros.appendChild(div);

        div.querySelector('input').addEventListener('input', declencherSauvegarde);
        div.querySelector('.btn-remove-numero').addEventListener('click', function() {
            div.remove();
            declencherSauvegarde();
        });
    });

    // Affichage contextuel des champs selon le type d'opération
    function toggleChamps() {
        let optionSelectionnee = selectType.options[selectType.selectedIndex];
        let libelle = optionSelectionnee.getAttribute('data-libelle');

        groupDestinataireUnique.style.display = 'none';
        groupDestinatairesMultiples.style.display = 'none';
        groupFraisInclus.style.display = 'none';

        if (libelle === 'retrait') {
            groupDestinataireUnique.style.display = 'block';
            groupFraisInclus.style.display = 'block';
        } else if (libelle === 'transfert') {
            groupDestinatairesMultiples.style.display = 'block';
        }
        // Dépôt : aucun champ de destinataire affiché
    }

    function declencherSauvegarde() {
        messageDiv.innerText = '';
        messageDiv.className = '';

        clearTimeout(saveTimer);
        saveTimer = setTimeout(function () {
            enregistrer();
        }, 800);
    }

    selectType.addEventListener('change', () => {
        toggleChamps();
        declencherSauvegarde();
    });

    inputMontant.addEventListener('input', declencherSauvegarde);
    inputDestinataire.addEventListener('input', declencherSauvegarde);
    checkboxFrais.addEventListener('change', declencherSauvegarde);
    
    document.querySelectorAll('.input-destinataire-multi').forEach(inp => {
        inp.addEventListener('input', declencherSauvegarde);
    });

    toggleChamps();

    function enregistrer() {
        let montantVal = inputMontant.value.trim();
        let optionSelectionnee = selectType.options[selectType.selectedIndex];
        let libelle = optionSelectionnee.getAttribute('data-libelle');

        if (montantVal === '' || parseFloat(montantVal) <= 0 || enCours) {
            return;
        }

        if (libelle === 'retrait' && inputDestinataire.value.trim() === '') return;
        if (libelle === 'transfert') {
            let auMoinsUnNumero = false;
            document.querySelectorAll('.input-destinataire-multi').forEach(input => {
                if (input.value.trim() !== '') auMoinsUnNumero = true;
            });
            if (!auMoinsUnNumero) return;
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

                inputMontant.value = '';
                inputDestinataire.value = '';
                document.querySelectorAll('.input-destinataire-multi').forEach((inp, index) => {
                    if (index === 0) inp.value = '';
                    else inp.parentElement.remove();
                });
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