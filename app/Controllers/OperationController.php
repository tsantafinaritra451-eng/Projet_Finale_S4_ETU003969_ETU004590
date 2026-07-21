<?php

namespace App\Controllers;

use App\Models\OperationModel;
use App\Models\TypeModel;
use App\Models\FraisModel;
use App\Models\UserModel;
use App\Models\PrefixModel;
use App\Models\PromotionModel;

use CodeIgniter\Database\Config;

class OperationController extends BaseController
{
    protected $operationModel;

    public function __construct()
    {
        $this->operationModel = new OperationModel();
    }

    public function index()
    {
        $typeModel = new TypeModel();

        return view('client/operation', [
            'types' => $typeModel->findAll()
        ]);
    }

    public function save()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Accès non autorisé.'
            ]);
        }

        $userId = session()->get('user_id');
        $idType = (int) $this->request->getPost('idType');
        $montant = (float) $this->request->getPost('montant');
        $inclureFrais = $this->request->getPost('inclure_frais') === '1';

        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Utilisateur non connecté ou session expirée.'
            ]);
        }

        if ($montant <= 0) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Le montant doit être supérieur à 0.'
            ]);
        }

        $typeModel = new TypeModel();
        $type = $typeModel->find($idType);

        if (!$type) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Type d\'opération invalide.'
            ]);
        }

        $libelleType = mb_strtolower(trim($type['libelle']));
        $userModel = new UserModel();
        $expediteur = $userModel->find($userId);
        $db = Config::connect();

        // -------------------------------------------------------------
        // CAS 1 : TRANSFERT (DIVISÉ ÉQUITABLEMENT)
        // -------------------------------------------------------------
        if ($libelleType === 'transfert') {

            $promotionModel = new PromotionModel();

            $pourcentage = $promotionModel->findAll();


            $numerosBruts = $this->request->getPost('numeros_destinataires');
            $numeros = [];

            if (is_array($numerosBruts)) {
                foreach ($numerosBruts as $num) {
                    $numTrim = trim((string) $num);
                    if (!empty($numTrim)) {
                        $numeros[] = $numTrim;
                    }
                }
            }

            if (empty($numeros)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Veuillez saisir au moins un numéro destinataire.'
                ]);
            }

            // 1. Contrôle : tous les numéros doivent être valides et du MÊME OPÉRATEUR
            $idOperateurReference = null;
            $opRecepteur = null;
            $destinatairesValides = [];

            foreach ($numeros as $numero) {
                $destinataire = $userModel->where('numero', $numero)->first();
                if (!$destinataire) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Le numéro destinataire ' . esc($numero) . ' n\'existe pas dans le système.'
                    ]);
                }

                if ($expediteur && $expediteur['numero'] === $numero) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Vous ne pouvez pas effectuer un transfert vers votre propre numéro (' . esc($numero) . ').'
                    ]);
                }

                $operateur = $this->obtenirOperateurParNumero($numero);
                if (!$operateur) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Opérateur introuvable pour le numéro : ' . esc($numero)
                    ]);
                }

                if ($idOperateurReference === null) {
                    $idOperateurReference = $operateur['id'];
                    $opRecepteur = $operateur;
                } elseif ($idOperateurReference !== $operateur['id']) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Erreur : Tous les numéros destinataires doivent appartenir au MÊME opérateur.'
                    ]);
                }

                $destinatairesValides[] = $destinataire;
            }

            // 2. Division équitable du montant entre les destinataires
            $nbDestinataires = count($destinatairesValides);
            $montantParDestinataire = $montant / $nbDestinataires;

            // 3. Identification de l'opérateur de l'expéditeur
            $opExpediteur = $this->obtenirOperateurParNumero($expediteur['numero']);

            // 4. Calcul des frais selon la tranche du montant individuel reçu
            $fraisModel = new FraisModel();
            $fraisUnitaire = (float) $fraisModel->getFraisAliquer($idType, $montantParDestinataire);
            $fraisTotal = $fraisUnitaire;

            // 5. Calcul de la commission par destinataire
            $commissionOpRecepteur = 0.0;
            $memeOperateur = ($opExpediteur && $opRecepteur && $opExpediteur['id'] === $opRecepteur['id']);

            if (!$memeOperateur) {
                $pctCommission = isset($opRecepteur['commission_pct']) ? (float) $opRecepteur['commission_pct'] : 0.0;
                $commissionOpRecepteur = ($fraisUnitaire * $pctCommission) / 100;
            }

            if ($memeOperateur) {
                $fraisTotal = ((float) $pourcentage * $fraisUnitaire) / 100;
            }

            // 6. Vérification du solde global de l'expéditeur
            $montantTotalDebite = $montant + $fraisTotal;
            $soldeActuel = $this->calculerSolde($userId);

            if ($montantTotalDebite > $soldeActuel) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Solde insuffisant ! Requis : ' . number_format($montantTotalDebite, 2, ',', ' ') . ' Ar (Montant: ' . number_format($montant, 2, ',', ' ') . ' + Frais: ' . number_format($fraisTotal, 2, ',', ' ') . ' Ar)'
                ]);
            }

            // 7. Obtenir les types d'opérations
            $typeTransfert = $typeModel->where('libelle', 'transfert')->first();
            $typeDepot = $typeModel->where('libelle', 'depot')->first();

            // 8. Enregistrement transactionnel (double écriture par destinataire)
            $db->transStart();

            foreach ($destinatairesValides as $destinataire) {
                // Débit individuel chez l'expéditeur
                $this->operationModel->insert([
                    'idUser' => $userId,
                    'idType' => $typeTransfert['id'],
                    'montant' => $montantParDestinataire + $fraisTotal,
                    'numero_destinataire' => $destinataire['numero'],
                    'frais_notre_gain' => $fraisTotal,
                    'commission_operateur' => 0.0,
                    'idOperationParent' => null
                ]);

                $idParent = $this->operationModel->getInsertID();

                // Crédit de la part exacte reçue chez le destinataire
                $this->operationModel->insert([
                    'idUser' => $destinataire['id'],
                    'idType' => $typeDepot['id'],
                    'montant' => $montantParDestinataire,
                    'numero_destinataire' => null,
                    'frais_notre_gain' => 0.0,
                    'commission_operateur' => $commissionOpRecepteur,
                    'idOperationParent' => $idParent
                ]);
            }

            $db->transComplete();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Transfert effectué ! Chaque destinataire (' . $nbDestinataires . ') a reçu ' . number_format($montantParDestinataire, 2, ',', ' ') . ' Ar.'
            ]);
        }

        // -------------------------------------------------------------
        // CAS 2 : RETRAIT AVEC DESTINATAIRE
        // -------------------------------------------------------------
        elseif ($libelleType === 'retrait') {

            $numeroDestinataire = trim((string) $this->request->getPost('numero_destinataire'));

            if (empty($numeroDestinataire)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Veuillez saisir le numéro du destinataire.'
                ]);
            }

            $destinataire = $userModel->where('numero', $numeroDestinataire)->first();
            if (!$destinataire) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Le numéro destinataire n\'existe pas.'
                ]);
            }

            // Seul notre opérateur interne (Telmo) possède un barème de frais.
            // Si le compte connecté est externe (Orange, Airtel...), aucun frais n'est perçu.
            $fraisExpediteur = 0.0;

            if ($expediteur && $this->estOperateurInterne($expediteur['numero'])) {
                $fraisModel = new FraisModel();
                $fraisExpediteur = (float) $fraisModel->getFraisAliquer($idType, $montant);
            }

            // « Inclure les frais » désigne qui supporte le barème. Avec l'option, l'expéditeur
            // paie les frais en plus pour que le destinataire touche le montant plein ; sans elle,
            // le solde n'est amputé que du montant et les frais sont retenus sur ce que reçoit
            // le destinataire. Dans les deux cas : débit = reçu + frais acquis à l'opérateur.
            $montantRetrait = $inclureFrais ? ($montant + $fraisExpediteur) : $montant;
            $montantRecu = $montantRetrait - $fraisExpediteur;
            $soldeActuel = $this->calculerSolde($userId);

            if ($montantRecu <= 0) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Montant trop faible : les frais (' . number_format($fraisExpediteur, 2, ',', ' ') . ' Ar) absorbent la totalité du retrait. Cochez « Inclure les frais » ou augmentez le montant.'
                ]);
            }

            if ($montantRetrait > $soldeActuel) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Solde insuffisant ! (Solde actuel : ' . number_format($soldeActuel, 2, ',', ' ') . ' Ar)'
                ]);
            }

            $typeRetrait = $typeModel->where('libelle', 'retrait')->first();
            $typeDepot = $typeModel->where('libelle', 'depot')->first();

            $db->transStart();

            $this->operationModel->insert([
                'idUser' => $userId,
                'idType' => $typeRetrait['id'],
                'montant' => $montantRetrait,
                'numero_destinataire' => $numeroDestinataire,
                'frais_notre_gain' => $fraisExpediteur,
                'commission_operateur' => 0.0,
                'idOperationParent' => null
            ]);

            $idOperationParent = $this->operationModel->getInsertID();

            // L'opérateur du destinataire ne perçoit rien sur un retrait :
            // le barème appartient entièrement à l'opérateur de l'expéditeur.
            $this->operationModel->insert([
                'idUser' => $destinataire['id'],
                'idType' => $typeDepot['id'],
                'montant' => $montantRecu,
                'numero_destinataire' => null,
                'frais_notre_gain' => 0.0,
                'commission_operateur' => 0.0,
                'idOperationParent' => $idOperationParent
            ]);

            $db->transComplete();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Retrait/Envoi réussi vers le ' . esc($numeroDestinataire) . ' ! Reçu : ' . number_format($montantRecu, 2, ',', ' ') . ' Ar (frais : ' . number_format($fraisExpediteur, 2, ',', ' ') . ' Ar).'
            ]);
        }

        // -------------------------------------------------------------
        // CAS 3 : DÉPÔT SIMPLE (SUR SON PROPRE COMPTE)
        // -------------------------------------------------------------
        else {
            // Le barème de frais n'existe que chez notre opérateur interne (Telmo).
            // Si le compte connecté appartient à un opérateur externe (Orange, Airtel...),
            // aucun frais n'est prélevé et personne ne perçoit de gain.
            $frais = 0.0;

            if ($expediteur && $this->estOperateurInterne($expediteur['numero'])) {
                $fraisModel = new FraisModel();
                $frais = (float) $fraisModel->getFraisAliquer($idType, $montant);
            }

            $this->operationModel->insert([
                'idUser' => $userId,
                'idType' => $idType,
                'montant' => $montant - $frais,
                'numero_destinataire' => null,
                'frais_notre_gain' => $frais,
                'commission_operateur' => 0.0
            ]);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Dépôt de ' . number_format($montant, 2, ',', ' ') . ' Ar effectué sur votre compte avec succès !'
            ]);
        }
    }

    /**
     * Recherche de l'opérateur par préfixe via PrefixModel
     */
    private function obtenirOperateurParNumero($numero)
    {
        $prefixModel = new PrefixModel();

        $numeroEchappe = Config::connect()->escape($numero);

        return $prefixModel->select('operateur.*')
            ->join('operateur', 'operateur.id = prefix.idOperateur')
            ->where("{$numeroEchappe} LIKE prefix.valeur || '%'", null, false)
            ->orderBy('LENGTH(prefix.valeur)', 'DESC', false)
            ->first();
    }

    /**
     * Le barème de frais ne s'applique qu'aux numéros de notre opérateur interne (Telmo).
     * Un numéro externe (Orange, Airtel...) n'a pas de barème : aucun frais, donc aucun gain.
     */
    private function estOperateurInterne($numero)
    {
        $operateur = $this->obtenirOperateurParNumero($numero);

        return $operateur && (int) $operateur['est_interne'] === 1;
    }

    /**
     * Calcule le solde courant : Dépôt - (Retrait + Transfert)
     */
    private function calculerSolde($userId)
    {
        $resultats = $this->operationModel->getTotauxParType($userId);

        $depots = 0;
        $retraits = 0;
        $transferts = 0;

        foreach ($resultats as $row) {
            $libelle = mb_strtolower(trim($row['libelle']));

            if ($libelle === 'depot') {
                $depots += (float) $row['total'];
            } elseif ($libelle === 'retrait') {
                $retraits += (float) $row['total'];
            } elseif ($libelle === 'transfert') {
                $transferts += (float) $row['total'];
            }
        }

        return $depots - ($retraits + $transferts);
    }
}