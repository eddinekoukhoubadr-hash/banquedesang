@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Détails de la Demande de Sang</h2>
                    <div class="space-x-2">
                        <a href="{{ route('bloodbank.edit', $demande->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Modifier
                        </a>
                        <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Retour
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Informations de la Demande -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-blue-600 border-b pb-2">Informations de la Demande</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="font-medium">Date de demande:</span>
                                <span>{{ $demande->date_demande->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Heure de demande:</span>
                                <span>{{ $demande->heure_demande }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Nom et Prénom:</span>
                                <span>{{ $demande->nom_prenom }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Service:</span>
                                <span>{{ $demande->service }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Médecin demandeur:</span>
                                <span>{{ $demande->medecin_demandeur }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Informations Médicales -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-green-600 border-b pb-2">Informations Médicales</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="font-medium">N° Dossier:</span>
                                <span>{{ $demande->dossier }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">IPP:</span>
                                <span>{{ $demande->ipp }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Groupage sanguin:</span>
                                <span class="font-bold">{{ $demande->groupage }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Phénotype:</span>
                                <span class="font-bold">{{ $demande->phenotype }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">RAI:</span>
                                <span>{{ $demande->rai }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">PSL:</span>
                                <span>{{ $demande->psl }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Quantité demandée:</span>
                                <span class="font-bold text-red-600">{{ $demande->quantite_demandee }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Suivi de la Demande -->
                    <div class="bg-purple-50 p-6 rounded-lg md:col-span-2">
                        <h3 class="text-lg font-semibold mb-4 text-purple-600 border-b pb-2">Suivi de la Demande</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium">Statut:</span>
                                    @php
                                        $statusColors = [
                                            'En cours d\'envoie' => 'blue',
                                            'En cours de traitement' => 'yellow',
                                            'Distribuée' => 'green',
                                            'Non Conforme' => 'red',
                                            'Périmé' => 'gray',
                                            'Annulé' => 'red',
                                            'En attente à la BS' => 'orange'
                                        ];
                                        $color = $statusColors[$demande->statut_demande] ?? 'gray';
                                    @endphp
                                    <span class="bg-{{ $color }}-200 text-{{ $color }}-800 py-1 px-3 rounded-full text-sm font-bold">
                                        {{ $demande->statut_demande }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Motif non conformité:</span>
                                    <span>{{ $demande->motif_non_conformite ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Quantité reçue:</span>
                                    <span>{{ $demande->quantite_recue ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Réceptionné par:</span>
                                    <span>{{ $demande->receptionne_par ?: '-' }}</span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium">Date réception:</span>
                                    <span>{{ $demande->date_reception ? $demande->date_reception->format('d/m/Y') : '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Heure réception:</span>
                                    <span>{{ $demande->heure_reception ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Récupéré par:</span>
                                    <span>{{ $demande->recupere_par ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Code PSL:</span>
                                    <span>{{ $demande->code_psl ?: '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="font-medium">Date expiration:</span>
                                <span>{{ $demande->date_expiration ? $demande->date_expiration->format('d/m/Y') : '-' }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Information service receveur:</span>
                                <p class="mt-1 text-gray-700">{{ $demande->information_service_receveur ?: '-' }}</p>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Date/heure récupération:</span>
                                <span>{{ $demande->date_heure_recuperation ? $demande->date_heure_recuperation->format('d/m/Y H:i') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <span class="text-sm text-gray-500">
                        Créé le: {{ $demande->created_at->format('d/m/Y à H:i') }}
                        @if($demande->created_at != $demande->updated_at)
                        <br>Modifié le: {{ $demande->updated_at->format('d/m/Y à H:i') }}
                        @endif
                    </span>
                    <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection