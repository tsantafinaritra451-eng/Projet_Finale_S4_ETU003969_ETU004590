<?php

namespace App\Models;

use CodeIgniter\Model;

class FraisModel extends Model
{
    protected $table      = 'frais';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'idType',
        'baremeMin',
        'baremeMax',
        'valeur_frais'
    ];

    public function getFraisAliquer($idType, $montant)
    {
        $frais = $this->where('idType', $idType)
                      ->where('baremeMin <=', $montant)
                      ->where('baremeMax >=', $montant)
                      ->first();

        return $frais ? (float)$frais['valeur_frais'] : 0.0;
    }
    public function getFraisPourMontant($idType, $montant)
    {
        return $this->where('idType', $idType)
                    ->where('baremeMin <=', $montant)
                    ->where('baremeMax >=', $montant)
                    ->first();
    }
}