<?php

namespace App\Controllers;

use App\Models\OperationModel;
use App\Models\TypeModel;
use App\Models\FraisModel;

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
        $idType = $this->request->getPost('idType');
        $montant = (float) $this->request->getPost('montant');

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

        $fraisModel = new FraisModel();
        $frais = $fraisModel->getFraisAliquer($idType, $montant);

        $montantTotal = $montant + $frais;
        $montant = $montant - $frais;

        $libelleType = mb_strtolower(trim($type['libelle']));

        if (in_array($libelleType, ['retrait', 'transfert'])) {
            $soldeActuel = $this->calculerSolde($userId);

            if ($montantTotal > $soldeActuel) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Solde insuffisant ! (Solde actuel : ' . number_format($soldeActuel, 2, ',', ' ') . ' Ariary | Montant + Frais : ' . number_format($montantTotal, 2, ',', ' ') . ' Ariary)'
                ]);
            }
        }

        $this->operationModel->insert([
            'idUser' => $userId,
            'idType' => $idType,
            'montant' => $montant
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Opération effectuée avec succès ! (Frais appliqués : ' . number_format($frais, 2, ',', ' ') . ' Ariary)'
        ]);
    }

    private function calculerSolde($userId)
    {
        $resultats = $this->operationModel->getTotauxParType($userId);

        $depots = 0;
        $retraits = 0;
        $transferts = 0;

        foreach ($resultats as $row) {
            $libelle = mb_strtolower(trim($row['libelle']));

            if ($libelle === 'depot' || $libelle === 'dépôt') {
                $depots = (float) $row['total'];
            } elseif ($libelle === 'retrait') {
                $retraits = (float) $row['total'];
            } elseif ($libelle === 'transfert') {
                $transferts = (float) $row['total'];
            }
        }

        return $depots - ($retraits + $transferts);
    }
}