<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        // Si c'est la première fois, on initialise avec tes anciennes listes complètes
        if (!$setting) {
            $defaultServices = [
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
            $defaultTechs = ['Hajar', 'Wafae', 'Ikram', 'Khaoula', 'Najoua', 'Latifa', 'Hanae'];

            $setting = Setting::create([
                'services' => $defaultServices,
                'techniciennes' => $defaultTechs
            ]);
        }

        return view('settings.index', compact('setting'));
    }

    public function addService(Request $request)
    {
        $request->validate(['nom' => 'required|string']);
        $setting = Setting::first();
        $services = $setting->services ?? [];
        $services[] = $request->nom;
        $setting->update(['services' => array_values(array_unique($services))]);
        return back()->with('success', 'Service ajouté.');
    }

    public function deleteService(Request $request)
    {
        $setting = Setting::first();
        if ($setting) {
            $services = collect($setting->services)->reject(fn($s) => $s === $request->nom)->toArray();
            $setting->update(['services' => array_values($services)]);
        }
        return back()->with('success', 'Service supprimé.');
    }

    public function addTechnicienne(Request $request)
    {
        $request->validate(['nom' => 'required|string']);
        $setting = Setting::first();
        $techs = $setting->techniciennes ?? [];
        $techs[] = $request->nom;
        $setting->update(['techniciennes' => array_values(array_unique($techs))]);
        return back()->with('success', 'Technicienne ajoutée.');
    }

    public function deleteTechnicienne(Request $request)
    {
        $setting = Setting::first();
        if ($setting) {
            $techs = collect($setting->techniciennes)->reject(fn($t) => $t === $request->nom)->toArray();
            $setting->update(['techniciennes' => array_values($techs)]);
        }
        return back()->with('success', 'Technicienne supprimée.');
    }
}
