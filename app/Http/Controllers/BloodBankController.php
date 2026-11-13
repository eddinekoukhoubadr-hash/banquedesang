<?php

namespace App\Http\Controllers;

use App\Models\BloodBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BloodBankExport;
use App\Models\Setting;
use MongoDB\BSON\Regex;

class BloodBankController extends Controller
{
    private function getServices()
    {
        $setting = Setting::first();
        if ($setting && !empty($setting->services)) {
            return $setting->services;
        }
        return [
            'Hématologie clinique', 'Pédiatrie', 'Neurochirurgie', 'Chirurgie thoracique',
            'Chirurgie générale', 'Néphrologie', 'Chirurgie vasculaire périphérique',
            'Neurologie', 'Endocrinologie', 'Gynécologie-Obstétrique', 'Médecine Interne',
            'Traumatologie Orthopédie pédiatrique', 'Chirurgie réparatrice et plastique',
            'Chirurgie pédiatrique Urologie', 'Oncologie Médicale', 'Dermatologie',
            'Pneumologie', 'Urologie', 'Anesthésie-Réanimation Pédiatrique',
            'Chirurgie Cardio-Vasculaire', 'Gastro-Entérologie', 'Rhumatologie', 'Cardiologie',
            'Oto-Rhino-Laryngologie (ORL)', 'Réanimation Adultes REA A',
            'Traumatologie Orthopédie-Adultes', 'Unité des soins intensifs', 'Néonatologie',
            'Réanimation Adultes REA B', 'Urgences Adultes', 'UHTCD Pédiatrique',
            'Chirurgie viscérale', 'Réanimation néonatale', 'Déchoquage ADULTE',
            'Déchoquage pédiatrique', 'Oncologie pédiatrique', 'Radiologie',
            'Chirurgie pédiatrique', 'DEMANDES SANS SERVICE'
        ];
    }

     private function getReceptionnesPar()
    {
        $setting = Setting::first();
        if ($setting && !empty($setting->techniciennes)) {
            return $setting->techniciennes;
        }
        return ['Hajar', 'Wafae', 'Ikram', 'Khaoula', 'Najoua', 'Latifa', 'Hanae'];
    }

    private function getStatuts()
    {
        return [
            'En cours d\'envoie', 'En cours de traitement', 'Distribuée', 'Non Conforme',
            'Périmé', 'Annulé', 'En attente à la BS'
        ];
    }

    /** ✅ Tableau principal accessible à tous */
    public function dashboard(Request $request)
    {
        $query = BloodBank::query();

        // Recherche startsWith insensible à la casse
        if ($request->filled('nom_prenom')) {
            $term = trim($request->nom_prenom);
            if ($term !== '') {
                $query->where('nom_prenom', 'regex', new Regex('^' . preg_quote($term, '/'), 'i'));
            }
        }

        if ($request->filled('groupage')) {
            $query->where('groupage', $request->groupage);
        }
        if ($request->filled('statut_demande')) {
            $query->where('statut_demande', $request->statut_demande);
        }
        if ($request->filled('service')) {
            $query->where('service', $request->service);
        }
        if ($request->filled('psl')) {
            $query->where('psl', $request->psl);
        }

        $demandes = $query->orderBy('created_at', 'desc')->paginate(25);
        $services = $this->getServices();

        return view('dashboard', compact('demandes', 'services'));
    }

    /** ✅ Page index admin (même tableau) */
    public function index(Request $request)
    {
        return $this->dashboard($request);
    }

    /** ✅ Créer une nouvelle demande */
    public function create()
    {
        $services = $this->getServices();
        $receptionnesPar = $this->getReceptionnesPar();
        $statuts = $this->getStatuts();

        return view('bloodbank.create', compact('services', 'receptionnesPar', 'statuts'));
    }

    /** ✅ Enregistrer la demande */
    public function store(Request $request)
    {
        $request->validate([
            'date_demande' => 'required|date',
            'heure_demande' => 'required',
            'nom_prenom' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'medecin_demandeur' => 'required|string|max:255',
            'dossier' => 'required|string|max:255',
            'ipp' => 'required|string|max:255',
            'groupage' => 'required|string|max:50',
            'phenotype_C' => 'required|string',
            'phenotype_c' => 'required|string',
            'phenotype_E' => 'required|string',
            'phenotype_e' => 'required|string',
            'phenotype_K' => 'required|string',
            'rai' => 'required|string|max:50',
            'psl' => 'required|string|max:50',
            'quantite_demandee' => 'required|integer|min:1',
            'statut_demande' => 'required|string'
        ]);

        $phenotype = $request->phenotype_C . $request->phenotype_c .
             $request->phenotype_E . $request->phenotype_e . $request->phenotype_K;

        BloodBank::create([
            'user_id' => Auth::id(),
            'date_demande' => $request->date_demande,
            'heure_demande' => $request->heure_demande,
            'nom_prenom' => $request->nom_prenom,
            'service' => $request->service,
            'medecin_demandeur' => $request->medecin_demandeur,
            'dossier' => $request->dossier,
            'ipp' => $request->ipp,
            'groupage' => $request->groupage,
            'phenotype' => $phenotype,
            'rai' => $request->rai,
            'psl' => $request->psl,
            'quantite_demandee' => $request->quantite_demandee,
            'statut_demande' => $request->statut_demande,
            'motif_non_conformite' => $request->motif_non_conformite,
            'quantite_recue' => $request->quantite_recue,
            'date_reception' => $request->date_reception,
            'heure_reception' => $request->heure_reception,
            'receptionne_par' => $request->receptionne_par,
            'recupere_par' => $request->recupere_par,
            'code_psl' => $request->code_psl,
            'date_expiration' => $request->date_expiration,
            'information_service_receveur' => $request->information_service_receveur,
            'date_heure_recuperation' => $request->date_heure_recuperation,
        ]);

        return redirect()->route('dashboard')->with('success', 'Demande ajoutée avec succès.');
    }

    /** ✅ Voir */
    public function show($id)
    {
        $demande = BloodBank::findOrFail($id);
        return view('bloodbank.show', compact('demande'));
    }

    /** ✅ Modifier */
    public function edit($id)
    {
        $demande = BloodBank::findOrFail($id);
        $services = $this->getServices();
        $receptionnesPar = $this->getReceptionnesPar();
        $statuts = $this->getStatuts();

        return view('bloodbank.edit', compact('demande', 'services', 'receptionnesPar', 'statuts'));
    }

    /** ✅ Enregistrer la modification */
    public function update(Request $request, $id)
    {
        $demande = BloodBank::findOrFail($id);

        $request->validate([
            'date_demande' => 'required|date',
            'heure_demande' => 'required',
            'nom_prenom' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'medecin_demandeur' => 'required|string|max:255',
            'dossier' => 'required|string|max:255',
            'ipp' => 'required|string|max:255',
            'groupage' => 'required|string|max:50',
            'phenotype_C' => 'required|string',
            'phenotype_c' => 'required|string',
            'phenotype_E' => 'required|string',
            'phenotype_e' => 'required|string',
            'phenotype_K' => 'required|string',
            'rai' => 'required|string|max:50',
            'psl' => 'required|string|max:50',
            'quantite_demandee' => 'required|integer|min:1',
            'statut_demande' => 'required|string',
        ]);

        $phenotype = $request->phenotype_C . $request->phenotype_c .
             $request->phenotype_E . $request->phenotype_e . $request->phenotype_K;

        $data = $request->all();
        $data['phenotype'] = $phenotype;

        $demande->update($data);

        return redirect()->route('dashboard')->with('success', 'Demande mise à jour avec succès.');
    }

    /** ✅ Supprimer (admin uniquement) */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès refusé.');
        }

        $demande = BloodBank::findOrFail($id);
        $demande->delete();

        return back()->with('success', 'Demande supprimée avec succès.');
    }

    /** ✅ Export Excel */
    public function exportExcel(Request $request)
    {
        $data = BloodBank::orderBy('created_at', 'desc')->get();
        return Excel::download(new BloodBankExport($data), 'demandes_sang_' . date('Y-m-d_H-i') . '.xlsx');
    }

    /** ✅ Vider le tableau (admin) */
    public function clearAll(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès refusé.');
        }

        $confirmation = $request->input('confirmation');
        if ($confirmation !== 'vider le tableau') {
            return back()->with('error', '❌ Confirmation invalide. Vous devez taper "vider le tableau".');
        }

        BloodBank::truncate();
        return back()->with('success', '✅ Toutes les demandes ont été supprimées avec succès.');
    }
}
