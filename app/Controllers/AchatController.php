<?php

namespace App\Controllers;

use App\Models\ProduitModel;
use App\Models\AchatModel;
use App\Models\DetailAchatModel;

class AchatController extends BaseController
{
    public function index()
    {
        $produitModel = new ProduitModel();

        return view('Achats', [
            'produits' => $produitModel->findAll()
        ]);
    }

    public function valider()
    {
        $session = session();

        $id_client = $session->get('id_client');
        $caisse = $session->get('caisse_active');

        if (!$id_client || !$caisse) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Client ou caisse introuvable'
            ]);
        }

        $id_caisse = $caisse['id'];

        $data = $this->request->getJSON(true);
        $panier = $data['panier'] ?? [];

        if (empty($panier)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Panier vide'
            ]);
        }

        $achatModel = new AchatModel();
        $detailModel = new DetailAchatModel();
        $produitModel = new ProduitModel();

        $id_achat = $achatModel->insert([
            'id_client' => $id_client,
            'id_caisse' => $id_caisse,
            'est_cloture' => 0
        ]);

        foreach ($panier as $item) {

            $produit = $produitModel->find($item['id_produit']);

            if (!$produit) {
                continue;
            }

            if ($produit['quantite_stock'] < $item['quantite']) {
                continue;
            }

            $detailModel->insert([
                'id_achat' => $id_achat,
                'id_produit' => $item['id_produit'],
                'quantite' => $item['quantite'],
                'prix_unitaire_facture' => $item['prix']
            ]);

            $produitModel->update($item['id_produit'], [
                'quantite_stock' => $produit['quantite_stock'] - $item['quantite']
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Achat validé avec succès'
        ]);
    }
}