<?php

namespace App\Controllers;

use App\Models\EparneModel;



class EparneController extends BaseController
{
    protected $EparneModel;
  

    public function __construct()
    {
        $this->EparneModel = new EparneModel();
  
    }

     public function index()
    {
        

        return view('client/eparne');
    }



    public function ajouterEparne()
    {
        $idUser = session()->get('user_id');

        $this->EparneModel->insert([
            'idUser' => $idUser,
            'pourcentage' => $this->request->getPost('pourcentage'),
           
        ]);

        return redirect()->to('client/eparne')->with('success', 'Barème ajouté avec succès');

    }

   
}