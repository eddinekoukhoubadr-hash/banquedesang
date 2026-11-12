@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-center text-red-700 mb-8">📊 Statistiques de la Banque de Sang</h1>

    <!-- 🔹 Statistiques globales par statut -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-10">
        @foreach ($stats as $statut => $nombre)
        <a href="{{ route('statistics.index', ['filtre' => $statut]) }}" 
           class="block bg-white p-6 rounded-lg shadow hover:shadow-xl border-t-4 
           {{ $statut == 'Distribuée' ? 'border-green-600' : 
              ($statut == 'Annulé' ? 'border-gray-500' : 
              ($statut == 'Non Conforme' ? 'border-orange-500' : 'border-red-600')) }}">
            <h3 class="text-gray-600 font-semibold text-center mb-2">{{ $statut }}</h3>
            <p class="text-4xl font-bold text-center text-red-700">{{ $nombre }}</p>
        </a>
        @endforeach
    </div>

    <!-- 🔹 Détails filtrés -->
    @if ($filtre)
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4 text-red-700">
            📦 Détails des demandes : 
            <span class="text-black">{{ $filtre }}</span>
        </h2>

        @if ($details->isEmpty())
            <p class="text-gray-500 text-center">Aucune demande trouvée pour ce statut.</p>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm text-gray-700">
                <thead class="bg-red-600 text-white">
                    <tr>
                        <th class="py-2 px-4 text-left">Nom du Patient</th>
                        <th class="py-2 px-4 text-left">Service</th>
                        <th class="py-2 px-4 text-left">Groupe</th>
                        <th class="py-2 px-4 text-left">Quantité Demandée</th>
                        <th class="py-2 px-4 text-left">Quantité Reçue</th>
                        @if ($filtre === 'Distribuée')
                            <th class="py-2 px-4 text-left">Date de Récupération</th>
                        @else
                            <th class="py-2 px-4 text-left">Date de la Demande</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($details as $demande)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-2 px-4">{{ $demande->nom_prenom }}</td>
                            <td class="py-2 px-4">{{ $demande->service }}</td>
                            <td class="py-2 px-4 font-semibold">{{ $demande->groupage }}</td>
                            <td class="py-2 px-4">{{ $demande->quantite_demandee }}</td>
                            <td class="py-2 px-4">{{ $demande->quantite_recue ?? '-' }}</td>

                            @if ($filtre === 'Distribuée')
                                <td class="py-2 px-4">
                                    {{ $demande->date_heure_recuperation ?? '-' }}
                                </td>
                            @else
                                <td class="py-2 px-4">
                                    {{ $demande->date_demande ? $demande->date_demande->format('d/m/Y') : '-' }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
