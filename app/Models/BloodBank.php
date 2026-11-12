<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class BloodBank extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'blood_banks';

    protected $fillable = [
        'date_demande',
        'heure_demande',
        'nom_prenom',
        'service',
        'medecin_demandeur',
        'dossier',
        'ipp',
        'groupage',
        'phenotype',
        'rai',
        'psl',
        'quantite_demandee',
        'statut_demande',
        'motif_non_conformite',
        'quantite_recue',
        'date_reception',
        'heure_reception',
        'receptionne_par',
        'recupere_par',
        'code_psl',
        'date_expiration',
        'information_service_receveur',
        'date_heure_recuperation',
        'user_id'
    ];

    protected $casts = [
        'date_demande' => 'date',
        'date_reception' => 'date',
        'date_expiration' => 'date',
        'date_heure_recuperation' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
