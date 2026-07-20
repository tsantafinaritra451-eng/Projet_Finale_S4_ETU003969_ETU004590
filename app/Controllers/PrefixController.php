<?php

namespace App\Controllers;

use App\Models\PrefixModel;

class PrefixController extends BaseController
{
    protected $prefixModel;
    protected $db;

    public function __construct()
    {
        $this->prefixModel = new PrefixModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $prefixes = $this->db->table('prefix')
                             ->select('prefix.*, operateur.nom as operateur_nom, operateur.est_interne')
                             ->join('operateur', 'prefix.idOperateur = operateur.id')
                             ->get()->getResultArray();

        $data = [
            'prefixes' => $prefixes
        ];

        return view('admin/listPrefix', $data);
    }

    public function form($id = null)
    {
        // On récupère tous les opérateurs pour remplir le <select> de la vue
        $operateurs = $this->db->table('operateur')->get()->getResultArray();

        $data = [
            'prefix' => null,
            'operateurs' => $operateurs
        ];

        if ($id !== null) {
            $prefix = $this->prefixModel->find($id);
            if (!$prefix) {
                return redirect()->to('/prefix')->with('error', 'Préfixe introuvable.');
            }
            $data['prefix'] = $prefix;
        }

        return view('admin/formPrefix', $data);
    }

    // SAUVEGARDE
    public function save()
    {
        $id = $this->request->getPost('id');
        $valeur = trim($this->request->getPost('valeur'));
        $idOperateur = $this->request->getPost('idOperateur');

        // Validation
        if (empty($valeur) || empty($idOperateur)) {
            return redirect()->back()->with('error', 'Tous les champs sont requis.')->withInput();
        }

        $data = [
            'valeur'      => $valeur,
            'idOperateur' => $idOperateur
        ];

        if (!empty($id)) {
            $this->prefixModel->update($id, $data);
            $message = 'Préfixe modifié avec succès.';
        } else {
            $this->prefixModel->insert($data);
            $message = 'Préfixe ajouté avec succès.';
        }

        return redirect()->to('/prefix')->with('success', $message);
    }

    // SUPPRESSION
    public function delete($id)
    {
        if ($this->prefixModel->find($id)) {
            $this->prefixModel->delete($id);
            return redirect()->to('/prefix')->with('success', 'Préfixe supprimé avec succès.');
        }

        return redirect()->to('/prefix')->with('error', 'Préfixe introuvable.');
    }
}