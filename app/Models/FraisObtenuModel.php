<?php

namespace App\Models;

use CodeIgniter\Model;

class FraisObtenuModel extends Model
{
    protected $table          = 'fraisObtenu';
    protected $primaryKey     = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';

    protected $allowedFields  = [
        'idType',
        'montant'
    ];

    public function getGainsAdmin()
    {
        return $this->select('type.libelle, SUM(fraisObtenu.montant) as totalGains')
                    ->join('type', 'type.id = fraisObtenu.idType')
                    ->whereIn('LOWER(type.libelle)', ['retrait', 'transfert'])
                    ->groupBy('type.libelle')
                    ->findAll();
    }
}