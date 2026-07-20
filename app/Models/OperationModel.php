<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table          = 'operation';
    protected $primaryKey     = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';

    protected $useTimestamps  = false;

    protected $allowedFields  = [
        'idUser',
        'idType',
        'montant',
        'date_operation'
    ];

  
    public function getTotauxParType($userId)
    {
        return $this->select('type.libelle, SUM(operation.montant) as total')
                    ->join('type', 'type.id = operation.idType')
                    ->where('operation.idUser', $userId)
                    ->groupBy('type.libelle')
                    ->findAll();
    }
}