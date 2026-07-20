<?php

namespace App\Controllers;

use App\Models\PrefixModel;

class PrefixController extends BaseController
{
    protected $prefixModel;

    public function __construct()
    {
        $this->prefixModel = new PrefixModel();
    }

    public function index()
    {
        $data = [
            'prefixes' => $this->prefixModel->findAll()
        ];

        return view('admin/listPrefix', $data);
    }

    public function form($id = null)
    {
        $data = [
            'prefix' => null
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

    // SAUVEGARDE (INSERTION OU MISE À JOUR)
    public function save()
    {
        $id = $this->request->getPost('id');
        $valeur = trim($this->request->getPost('valeur'));

        // Validation simple
        if (empty($valeur)) {
            return redirect()->back()->with('error', 'La valeur du préfixe est requise.')->withInput();
        }

        $data = [
            'valeur' => $valeur
        ];

        if (!empty($id)) {
            // Modification
            $this->prefixModel->update($id, $data);
            $message = 'Préfixe modifié avec succès.';
        } else {
            // Ajout
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