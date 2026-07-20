<?php
namespace App\Models;
use CodeIgniter\Model;

class FraisModel extends Model
{
    protected $table            = 'frais';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['idType', 'baremeMin', 'baremeMax', 'valeur_frais'];
}