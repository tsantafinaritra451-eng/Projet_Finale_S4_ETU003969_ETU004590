<?php

namespace App\Controllers;

use App\Models\FraisModel;
use App\Models\TypeModel;


class FraisController extends BaseController{
    protected $FraisModel;
    protected $TypeModel;

    public function __construct()
    {
        $this->FraisModel = new FraisModel();
        $this->TypeModel = new TypeModel();
    }

    public function index(){
        $data = [
            'fraisDepot'     => $this->FraisModel->where('idType', 1)->findAll(),
            'fraisRetrait'   => $this->FraisModel->where('idType', 2)->findAll(),
            'fraisTransfert' => $this->FraisModel->where('idType', 3)->findAll(),
        ];
        
        return view('admin/liste_frais', $data);

    }

    public function ajoutBareme(){
        $idType = $this->request->getPost('idType');

        $this->FraisModel->insert([
            'idType' => $idType,
            'baremeMin' => $this->request->getPost('baremeMin'),
            'baremeMax' => $this->request->getPost('baremeMax'),
            'valeur_frais' => $this->request->getPost('valeur_frais')
        ]);

        return redirect()->to('/admin/frais')->with('success', 'Barème ajouté avec succès');

    }

    public function supprimerBareme($id){
       $this->FraisModel->delete($id);
        return redirect()->to('/admin/frais')->with('success', 'Barème supprimé !');
    }


}