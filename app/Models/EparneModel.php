<?php
namespace App\Models;
use CodeIgniter\Model;

class EparneModel extends Model
{
    protected $table            = 'eparne';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['pourcentage', 'montant_eparne', 'idUser'];
}