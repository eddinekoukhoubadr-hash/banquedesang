<?php

namespace App\Exports;

use App\Models\BloodBank;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BloodBankExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data ?: BloodBank::all();
    }

    public function headings(): array
    {
        return [
            'Date Demande',
            'Heure Demande',
            'Nom et Prénom',
            'Service',
            'Médecin Demandeur',
            'Dossier',
            'IPP',
            'Groupage',
            'Phénotype',
            'RAI',
            'PSL',
            'Quantité Demandée',
            'Statut Demande',
            'Motif Non Conformité',
            'Quantité Reçue',
            'Date Réception',
            'Heure Réception',
            'Réceptionné Par',
            'Récupéré Par',
            'Code PSL',
            'Date Expiration',
            'Information Service Receveur',
            'Date/Heure Récupération'
        ];
    }

    public function map($demande): array
    {
        return [
            optional($demande->date_demande)->format('d/m/Y'),
            $demande->heure_demande,
            $demande->nom_prenom,
            $demande->service,
            $demande->medecin_demandeur,
            $demande->dossier,
            $demande->ipp,
            $demande->groupage,
            $demande->phenotype,
            $demande->rai,
            $demande->psl,
            $demande->quantite_demandee,
            $demande->statut_demande,
            $demande->motif_non_conformite,
            $demande->quantite_recue,
            optional($demande->date_reception)->format('d/m/Y'),
            $demande->heure_reception,
            $demande->receptionne_par,
            $demande->recupere_par,
            $demande->code_psl,
            optional($demande->date_expiration)->format('d/m/Y'),
            $demande->information_service_receveur,
            optional($demande->date_heure_recuperation)->format('d/m/Y H:i'),
        ];
    }
}
