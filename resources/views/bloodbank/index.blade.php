@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Gestion des Demandes de Sang</h2>
                    <div class="flex gap-3">
                        <a href="{{ route('bloodbank.export') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            📤 Exporter Excel
                        </a>
                        <a href="{{ route('bloodbank.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            ➕ Nouvelle Demande
                        </a>
                    </div>
                </div>

                <!-- 🔍 Filtres -->
                <form method="GET" action="{{ route('bloodbank.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-3">
                    <input type="text" name="nom_prenom" value="{{ request('nom_prenom') }}" placeholder="Nom du patient"
                           class="border rounded px-3 py-2 w-full">

                    <select name="statut_demande" class="border rounded px-3 py-2 w-full">
                        <option value="">-- Statut --</option>
                        @foreach($statuts as $statut)
                            <option value="{{ $statut }}" {{ request('statut_demande') == $statut ? 'selected' : '' }}>
                                {{ $statut }}
                            </option>
                        @endforeach
                    </select>

                    <select name="groupage" class="border rounded px-3 py-2 w-full">
                        <option value="">-- Groupage --</option>
                        <option value="A+" {{ request('groupage') == "A+" ? 'selected' : '' }}>A+</option>
                        <option value="A-" {{ request('groupage') == "A-" ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ request('groupage') == "B+" ? 'selected' : '' }}>B+</option>
                        <option value="B-" {{ request('groupage') == "B-" ? 'selected' : '' }}>B-</option>
                        <option value="O+" {{ request('groupage') == "O+" ? 'selected' : '' }}>O+</option>
                        <option value="O-" {{ request('groupage') == "O-" ? 'selected' : '' }}>O-</option>
                        <option value="AB+" {{ request('groupage') == "AB+" ? 'selected' : '' }}>AB+</option>
                        <option value="AB-" {{ request('groupage') == "AB-" ? 'selected' : '' }}>AB-</option>
                    </select>

                    <select name="service" class="border rounded px-3 py-2 w-full">
                        <option value="">-- Service --</option>
                        @foreach($services as $service)
                            <option value="{{ $service }}" {{ request('service') == $service ? 'selected' : '' }}>
                                {{ $service }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded flex-1">
                            🔎 Rechercher
                        </button>
                        <a href="{{ route('bloodbank.index') }}" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                            ♻️
                        </a>
                    </div>
                </form>

                <!-- Tableau -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Date/Heure</th>
                                <th class="py-3 px-6 text-left">Patient</th>
                                <th class="py-3 px-6 text-left">Service</th>
                                <th class="py-3 px-6 text-left">Médecin</th>
                                <th class="py-3 px-6 text-left">Dossier</th>
                                <th class="py-3 px-6 text-left">Groupage</th>
                                <th class="py-3 px-6 text-left">Phénotype</th>
                                <th class="py-3 px-6 text-left">Quantité</th>
                                <th class="py-3 px-6 text-left">Statut</th>
                                <th class="py-3 px-6 text-left">Réception</th>
                                <th class="py-3 px-6 text-left">Récupéré par</th>
                                <th class="py-3 px-6 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @forelse($demandes as $demande)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left">
                                    {{ $demande->date_demande->format('d/m/Y') }}<br>
                                    <small class="text-gray-500">{{ $demande->heure_demande }}</small>
                                </td>
                                <td class="py-3 px-6 text-left">{{ $demande->nom_prenom }}</td>
                                <td class="py-3 px-6 text-left">{{ $demande->service }}</td>
                                <td class="py-3 px-6 text-left">{{ $demande->medecin_demandeur }}</td>
                                <td class="py-3 px-6 text-left">{{ $demande->dossier }}</td>
                                <td class="py-3 px-6 text-left font-bold">{{ $demande->groupage }}</td>
                                <td class="py-3 px-6 text-left">{{ $demande->phenotype }}</td>
                                <td class="py-3 px-6 text-left">{{ $demande->quantite_demandee }}</td>
                                <td class="py-3 px-6 text-left">
                                    @php
                                        $statusColors = [
                                            "En cours d'envoie" => 'blue',
                                            "En cours de traitement" => 'yellow',
                                            "Distribuée" => 'green',
                                            "Non Conforme" => 'red',
                                            "Périmé" => 'gray',
                                            "Annulé" => 'red',
                                            "En attente à la BS" => 'orange'
                                        ];
                                        $color = $statusColors[$demande->statut_demande] ?? 'gray';
                                    @endphp
                                    <span class="bg-{{ $color }}-200 text-{{ $color }}-800 py-1 px-3 rounded-full text-xs">
                                        {{ $demande->statut_demande }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    @if($demande->date_reception)
                                        {{ $demande->date_reception->format('d/m/Y') }}<br>
                                        <small class="text-gray-500">{{ $demande->heure_reception }}</small>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-left">
                                    {{ $demande->recupere_par ?: '-' }}
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <div class="flex flex-col gap-1">
                                        <a href="{{ route('bloodbank.show', $demande->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-1 px-2 rounded text-xs text-center">Voir</a>
                                        <a href="{{ route('bloodbank.edit', $demande->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded text-xs text-center">Modifier</a>
                                        @if(Auth::user()->role === 'admin')
                                        <form action="{{ route('bloodbank.destroy', $demande->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded text-xs w-full" onclick="return confirm('Supprimer cette demande ?')">Supprimer</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-6 text-gray-500">
                                        Aucune demande trouvée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $demandes->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
