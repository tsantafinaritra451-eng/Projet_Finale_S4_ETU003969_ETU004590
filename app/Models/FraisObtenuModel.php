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

   
}