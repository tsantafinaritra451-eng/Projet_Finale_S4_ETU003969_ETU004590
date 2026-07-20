<?php

namespace App\Controllers;

use App\Models\OperateurModel;

class OperateurController extends BaseController
{
    protected $operateurModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurModel();
    }

    public function index()
    {
        $data = [
            'operateurs' => $this->operateurModel->where('est_interne', 0)->findAll()
        ];

        return view('admin/listCommissions', $data);
    }

    public function saveCommission()
    {
        $id = $this->request->getPost('id');
        $commissionPct = $this->request->getPost('commission_pct');

        if (empty($id) || $commissionPct === null) {
            return redirect()->back()->with('error', 'Tous les champs sont requis.')->withInput();
        }

        $operateur = $this->operateurModel->find($id);
        if (!$operateur || $operateur['est_interne'] == 1) {
            return redirect()->to('/admin/commissions')->with('error', 'Opérateur introuvable ou non modifiable.');
        }

        $this->operateurModel->update($id, [
            'commission_pct' => (float)$commissionPct
        ]);

        return redirect()->to('/admin/commissions')->with('success', 'La commission de ' . $operateur['nom'] . ' a été mise à jour.');
    }
}