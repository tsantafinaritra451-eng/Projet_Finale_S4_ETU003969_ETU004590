<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table = 'operation';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useTimestamps = false;

    // AJOUT DE TOUS LES CHAMPS MANQUANTS ICI
    protected $allowedFields = [
        'idUser',
        'idType',
        'montant',
        'numero_destinataire',   // Manquant
        'frais_notre_gain',      // Manquant (Ton gain Telmo)
        'commission_operateur',  // Manquant (Gain de l'autre opérateur)
        'idOperationParent',     // Manquant (Regroupement envois multiples)
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

    public function getHistoriqueParUser($idUser)
    {
        return $this->select('operation.*, type.libelle as type_libelle')
            ->join('type', 'type.id = operation.idType')
            ->where('operation.idUser', $idUser)
            ->orderBy('operation.date_operation', 'DESC')
            ->findAll();
    }

    public function getNosGainsParType()
    {
        return $this->db->table('operation')
            ->select('type.libelle, SUM(operation.frais_notre_gain) as totalGains')
            ->join('type', 'type.id = operation.idType')
            ->join('user', 'operation.idUser = user.id', 'inner')
            ->join('prefix', 'SUBSTR(COALESCE(operation.numero_destinataire, user.numero), 1, 3) = prefix.valeur', 'inner')
            ->join('operateur', 'prefix.idOperateur = operateur.id', 'inner')
            ->where('operateur.est_interne', 1) // 1 = Uniquement Telmo !
            ->groupBy('operation.idType')
            ->get()->getResultArray();
    }

    public function getGainsAutresOperateurs()
    {
        return $this->db->table('operation')
            ->select('operateur.nom as libelle, SUM(operation.commission_operateur) as totalGains')
            ->from('prefix')
            ->join('operateur', 'prefix.idOperateur = operateur.id')
            ->where('operateur.est_interne', 0)
            ->join('user', 'operation.idUser = user.id', 'inner')
            ->where('SUBSTR(COALESCE(operation.numero_destinataire, user.numero), 1, 3) = prefix.valeur', null, false)
            ->groupBy('operateur.id')
            ->get()->getResultArray();
    }


    public function getCommissionsAEnvoyer()
    {
        return $this->db->table('operation')
            ->select('
            operateur.nom as operateur_nom, 
            COUNT(operation.id) as nombre_transactions, 
            SUM(operation.commission_operateur) as total_commissions
        ')
            ->from('prefix')
            ->join('operateur', 'prefix.idOperateur = operateur.id')
            ->join('type', 'operation.idType = type.id')
            ->where('LOWER(type.libelle)', 'transfert')
            ->where('operateur.est_interne', 0) // Uniquement les opérateurs externes
            ->where('SUBSTR(operation.numero_destinataire, 1, 3) = prefix.valeur', null, false)
            ->groupBy('operateur.id')
            ->get()->getResultArray();
    }
}