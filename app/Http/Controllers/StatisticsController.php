<?php

namespace App\Http\Controllers;

use App\Models\BloodBank;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        // Liste complète des statuts
        $statuts = [
            'En cours d\'envoie',
            'En cours de traitement',
            'Distribuée',
            'Non Conforme',
            'Périmé',
            'Annulé',
            'En attente à la BS'
        ];

        // Statistiques globales
        $stats = [];
        foreach ($statuts as $statut) {
            $stats[$statut] = BloodBank::where('statut_demande', $statut)->count();
        }

        // Statistiques des groupes sanguins (quantité totale distribuée)
        $groupes = ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];
        $statsGroupes = [];
        foreach ($groupes as $groupe) {
            $statsGroupes[$groupe] = BloodBank::where('groupage', $groupe)
                ->sum('quantite_demandee');
        }

        // Filtre dynamique (clic sur un bloc)
        $filtre = $request->query('filtre');
        $details = collect();

        if ($filtre) {
            $details = BloodBank::where('statut_demande', $filtre)->orderBy('created_at', 'desc')->get();
        }

        return view('statistics.index', compact('statuts', 'stats', 'statsGroupes', 'details', 'filtre'));
    }
}
