<?php

namespace App\Controllers;

use App\Models\OperationModel;
use App\Models\TypeModel;
use App\Models\FraisModel;
use App\Models\UserModel;
use App\Models\PrefixModel;
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
                'status'  => 'error',
                'message' => 'Accès non autorisé.'
            ]);
        }

        $userId             = session()->get('user_id');
        $idType             = (int) $this->request->getPost('idType');
        $montant            = (float) $this->request->getPost('montant');
        $numeroDestinataire = trim((string) $this->request->getPost('numero_destinataire'));
        $inclureFrais       = $this->request->getPost('inclure_frais') === '1';

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Utilisateur non connecté ou session expirée.'
            ]);
        }

        if ($montant <= 0) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Le montant doit être supérieur à 0.'
            ]);
        }

        $typeModel = new TypeModel();
        $type      = $typeModel->find($idType);

        if (!$type) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Type d\'opération invalide.'
            ]);
        }

        $libelleType = mb_strtolower(trim($type['libelle']));
        $db          = Config::connect();

        // -------------------------------------------------------------
        // CAS 1 : RETRAIT AVEC DESTINATAIRE (Retrait chez A -> Dépôt chez B)
        // -------------------------------------------------------------
        if ($libelleType === 'retrait') {

            // 1. Vérification de la présence du numéro
            if (empty($numeroDestinataire)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Veuillez saisir le numéro du destinataire.'
                ]);
            }

            // 2. Vérification de l'existence du destinataire dans la table user
            $userModel    = new UserModel();
            $destinataire = $userModel->where('numero', $numeroDestinataire)->first();

            if (!$destinataire) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Le numéro destinataire (' . esc($numeroDestinataire) . ') n\'existe pas dans notre base de données.'
                ]);
            }

            // Empêcher l'envoi vers son propre numéro
            $expediteur = $userModel->find($userId);
            if ($expediteur && $expediteur['numero'] === $numeroDestinataire) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Vous ne pouvez pas effectuer un retrait vers votre propre numéro.'
                ]);
            }

            // 3. Identification des opérateurs (Expéditeur vs Receveur)
            $opExpediteur = $this->obtenirOperateurParNumero($expediteur['numero']);
            $opRecepteur  = $this->obtenirOperateurParNumero($numeroDestinataire);

            // Types d'opérations requis pour la double écriture
            $typeRetrait = $typeModel->where('libelle', 'retrait')->first();
            $typeDepot   = $typeModel->where('libelle', 'depot')->first();

            // 4. Calcul du frais selon le barème
            $fraisModel      = new FraisModel();
            $fraisExpediteur = (float) $fraisModel->getFraisAliquer($idType, $montant);

            // Le receveur ne supporte aucun frais sur le dépôt reçu
            $fraisRecepteur  = 0.0;

            // 5. Calcul du montant débité
            // Si "Inclure les frais" est coché : Retrait = Montant + Frais, Dépôt = Montant
            $montantRetrait = $inclureFrais ? ($montant + $fraisExpediteur) : $montant;
            $montantDepot   = $montant;

            // 6. Vérification du solde de l'expéditeur
            $soldeActuel = $this->calculerSolde($userId);
            if ($montantRetrait > $soldeActuel) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Solde insuffisant ! (Solde actuel : ' . number_format($soldeActuel, 2, ',', ' ') . ' Ar)'
                ]);
            }

            // 7. Enregistrement transactionnel en base de données
            $db->transStart();

            // Retrait chez l'expéditeur (frais du barème appliqués)
            $this->operationModel->insert([
                'idUser'              => $userId,
                'idType'              => $typeRetrait['id'],
                'montant'             => $montantRetrait,
                'numero_destinataire' => $numeroDestinataire,
                'frais_notre_gain'    => $fraisExpediteur,
                'idOperationParent'   => null
            ]);

            $idOperationParent = $this->operationModel->getInsertID();

            // Dépôt chez le destinataire (0 frais)
            $this->operationModel->insert([
                'idUser'              => $destinataire['id'],
                'idType'              => $typeDepot['id'],
                'montant'             => $montantDepot,
                'numero_destinataire' => null,
                'frais_notre_gain'    => $fraisRecepteur,
                'idOperationParent'   => $idOperationParent
            ]);

            $db->transComplete();

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Retrait/Envoi réussi vers le ' . esc($numeroDestinataire) . ' ! (Débité : ' . number_format($montantRetrait, 2, ',', ' ') . ' Ar)'
            ]);
        }

        // -------------------------------------------------------------
        // CAS 2 : AUTRES OPÉRATIONS (DÉPÔT / TRANSFERT SIMPLE)
        // -------------------------------------------------------------
        else {
            $fraisModel = new FraisModel();
            $frais      = (float) $fraisModel->getFraisAliquer($idType, $montant);

            $this->operationModel->insert([
                'idUser'           => $userId,
                'idType'           => $idType,
                'montant'          => $montant,
                'frais_notre_gain' => $frais
            ]);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Dépôt effectué avec succès !'
            ]);
        }
    }

    private function obtenirOperateurParNumero($numero)
    {
        $prefixModel = new PrefixModel();

        return $prefixModel->select('operateur.*')
            ->join('operateur', 'operateur.id = prefix.idOperateur')
            ->where('? LIKE CONCAT(prefix.valeur, "%")', [$numero])
            ->first();
    }

   
    private function calculerSolde($userId)
    {
        $resultats = $this->operationModel->getTotauxParType($userId);

        $depots     = 0;
        $retraits   = 0;
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