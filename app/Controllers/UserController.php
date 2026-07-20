<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PrefixModel;
use App\Models\FraisObtenuModel;


class UserController extends BaseController
{
    public function index()
    {
        $prefixModel = new PrefixModel();

        return view('login', [
            'prefix' => $prefixModel->findAll()
        ]);
    }

    public function login()
    {
        $num = trim($this->request->getPost('num'));
        $userModel = new UserModel();


        $adminNum = '0000000000';

        if ($num === $adminNum) {
            $adminUser = $userModel->where('numero', $num)->first();

            if ($adminUser) {
                session()->set([
                    'user_id' => $adminUser['id'],
                    'numero' => $adminUser['numero'],
                    'is_admin' => true
                ]);
                return redirect()->to('/admin/dashboard');
            }
        }

        $prefixModel = new PrefixModel();
        $prefixesList = $prefixModel->findAll();
        $valeursPrefixes = array_column($prefixesList, 'valeur');

        if (empty($valeursPrefixes)) {
            return redirect()->back()->with('error', 'Aucun préfixe configuré en base.');
        }

        // Construit le pattern dynamique : /^((032|037))[0-9]{7}$/
        $regexPattern = '/^(' . implode('|', $valeursPrefixes) . ')[0-9]{7}$/';

        if (!preg_match($regexPattern, $num)) {
            return redirect()->back()->with('error', 'Numéro invalide.');
        }

        $user = $userModel->where('numero', $num)->first();

        if (!$user) {
            $userModel->insert([
                'numero' => $num
            ]);
            $user = $userModel->where('numero', $num)->first();
        }

        session()->set([
            'user_id' => $user['id'],
            'numero' => $user['numero'],
            'is_admin' => false
        ]);

        return redirect()->to('/client/dashboard');
    }


    public function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }


    public function dashboardAdmin()
    {
        return view('admin/dashboard');
    }

    public function dashboardClient()
    {
        $idUser = session()->get('user_id'); 

        return view('client/dashboard', [
            'idUser' => $idUser
        ]);
    }


    public function gains()
    {
        $fraisObtenuModel = new FraisObtenuModel();
        
        $gains = $fraisObtenuModel->getGainsAdmin();

        $totalGeneral = 0;
        foreach ($gains as $gain) {
            $totalGeneral += (float) $gain['totalGains'];
        }

        return view('admin/gains', [
            'gains'        => $gains,
            'totalGeneral' => $totalGeneral
        ]);
    }
}