<?php

namespace App\Controllers;

use App\Models\FraisModel;
use App\Models\TypeModel;


class FraisController extends BaseController
{
    protected $FraisModel;
    protected $TypeModel;

    public function __construct()
    {
        $this->FraisModel = new FraisModel();
        $this->TypeModel = new TypeModel();
    }

    public function index($id = null)
    {
        $baremeEnCours = null;

        if ($id !== null) {
            $baremeEnCours = $this->FraisModel->find($id);
        }

        $data = [
            'fraisDepot' => $this->FraisModel->where('idType', 1)->findAll(),
            'fraisRetrait' => $this->FraisModel->where('idType', 2)->findAll(),
            'fraisTransfert' => $this->FraisModel->where('idType', 3)->findAll(),
            'baremeEnCours' => $baremeEnCours
        ];

        return view('admin/liste_frais', $data);
    }

    public function ajoutBareme()
    {
        $idType = $this->request->getPost('idType');

        $this->FraisModel->insert([
            'idType' => $idType,
            'baremeMin' => $this->request->getPost('baremeMin'),
            'baremeMax' => $this->request->getPost('baremeMax'),
            'valeur_frais' => $this->request->getPost('valeur_frais')
        ]);

        return redirect()->to('/admin/frais')->with('success', 'Barème ajouté avec succès');

    }

    public function supprimerBareme($id)
    {
        $this->FraisModel->delete($id);
        return redirect()->to('/admin/frais')->with('success', 'Barème supprimé !');
    }

    public function modifierBareme($id)
    {
        $this->FraisModel->update($id, [
            'idType' => $this->request->getPost('idType'),
            'baremeMin' => $this->request->getPost('baremeMin'),
            'baremeMax' => $this->request->getPost('baremeMax'),
            'valeur_frais' => $this->request->getPost('valeur_frais')
        ]);

        return redirect()->to('/admin/frais')->with('success', 'Barème modifié avec succès');
    }

}