<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OperationModel;
use App\Models\FraisModel;

class ClientController extends BaseController
{
    protected $userModel;
    protected $operationModel;
    protected $fraisModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->operationModel = new OperationModel();
        $this->fraisModel = new FraisModel();
    }

    public function index()
    {
        $data['users'] = $this->userModel->findAll();
        return view('client/liste_clients', $data);
    }

    public function situation($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/clients')->with('error', 'Utilisateur introuvable');
        }

        $operations = $this->operationModel->getHistoriqueParUser($id);

        $historiqueComplet = [];
        $solde = 0;

        foreach ($operations as $op) {
            $fraisData = $this->fraisModel->getFraisPourMontant($op['idType'], $op['montant']);
            $valeurFrais = $fraisData ? (float) $fraisData['valeur_frais'] : 0.0;

            $impactSolde = 0;
            $libelleType = strtolower($op['type_libelle']);

            if ($libelleType === 'depot') {
                $impactSolde = ($op['montant'] - $valeurFrais);
            } elseif ($libelleType === 'retrait') {
                $impactSolde = -($op['montant'] + $valeurFrais);
            } elseif ($libelleType === 'transfert') {
                $impactSolde = -($op['montant'] + $valeurFrais);
            }

            $solde += $impactSolde;

            $historiqueComplet[] = [
                'date' => $op['date_operation'],
                'type' => $op['type_libelle'],
                'montant' => $op['montant'],
                'frais' => $valeurFrais,
                'impact' => $impactSolde
            ];
        }

        $data = [
            'user' => $user,
            'solde' => $solde,
            'historique' => $historiqueComplet
        ];

        return view('client/situation_client', $data);
    }
    public function historiqueParClient()
    {
        $idUserConnecte = session()->get('user_id');

        if (!$idUserConnecte) {
            return redirect()->to('/')->with('error', 'Veuillez vous connecter.');
        }

        $user = $this->userModel->find($idUserConnecte);

        if (!$user) {
            return redirect()->to('/')->with('error', 'Utilisateur introuvable');
        }

        $operations = $this->operationModel->getHistoriqueParUser($idUserConnecte);

        $historiqueComplet = [];
        $solde = 0;

        foreach ($operations as $op) {
            $fraisData = $this->fraisModel->getFraisPourMontant($op['idType'], $op['montant']);
            $valeurFrais = $fraisData ? (float) $fraisData['valeur_frais'] : 0.0;

            $impactSolde = 0;
            $libelleType = strtolower($op['type_libelle']);

            if ($libelleType === 'depot') {
                $impactSolde = ($op['montant'] - $valeurFrais);
                } elseif ($libelleType === 'retrait') {
                    $impactSolde = -($op['montant'] + $valeurFrais);
            } elseif ($libelleType === 'transfert') {
                $impactSolde = -($op['montant'] + $valeurFrais);
            }

            $solde += $impactSolde;

            $historiqueComplet[] = [
                'date' => $op['date_operation'],
                'type' => $op['type_libelle'],
                'montant' => $op['montant'],
                'frais' => $valeurFrais,
                'impact' => $impactSolde
            ];
        }

        return view('client/historique_client', [
            'user' => $user,
            'solde' => $solde,
            'historique' => $historiqueComplet
        ]);
    }

}