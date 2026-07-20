<?php

namespace App\Controllers;

use App\Models\OperationModel;
use App\Models\TypeModel;
use App\Models\FraisModel;
use App\Models\FraisObtenuModel;

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

        $userId  = session()->get('user_id');
        $idType  = $this->request->getPost('idType');
        $montant = (float) $this->request->getPost('montant');

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

        $fraisModel = new FraisModel();
        $frais      = $fraisModel->getFraisAliquer($idType, $montant);

        $libelleType = mb_strtolower(trim($type['libelle']));
        $libelleType = str_replace(['é', 'è', 'ê'], 'e', $libelleType);

        if (in_array($libelleType, ['retrait', 'transfert'])) {
            $montantAInserer = $montant + $frais;

            $soldeActuel = $this->calculerSolde($userId);

            if ($montantAInserer > $soldeActuel) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Solde insuffisant ! (Solde actuel : ' . number_format($soldeActuel, 2, ',', ' ') . ' Ariary)'
                ]);
            }
        } else {
            $montantAInserer = $montant - $frais;
        }

        $this->operationModel->insert([
            'idUser'  => $userId,
            'idType'  => $idType,
            'montant' => $montantAInserer
        ]);

        if ($frais > 0) {
            $this->enregistrerFraisObtenu($idType, $frais);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Opération effectuée avec succès ! (Frais : ' . number_format($frais, 2, ',', ' ') . ' Ariary)'
        ]);
    }

    private function enregistrerFraisObtenu($idType, $montantFrais)
    {
        $fraisObtenuModel = new FraisObtenuModel();
        
        $fraisObtenuModel->insert([
            'idType'  => $idType,
            'montant' => $montantFrais
        ]);
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