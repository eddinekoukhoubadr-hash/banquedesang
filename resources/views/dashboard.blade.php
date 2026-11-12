@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">

                <!-- Header -->
                <div class="flex justify-between items-center mb-6 flex-wrap gap-2">
                    <h2 class="text-2xl font-bold text-gray-700 text-center w-full md:w-auto">
                        Tableau de Bord - Banque de Sang
                    </h2>

                    <div class="flex flex-wrap justify-center gap-2 md:gap-3">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('bloodbank.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-[48%] md:w-auto text-center">
                                Gestion Admin
                            </a>
                        @endif
                        <a href="{{ route('bloodbank.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-[48%] md:w-auto text-center">
                            Nouvelle Demande
                        </a>
                        <button id="exportExcel" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded w-[48%] md:w-auto text-center">
                            Exporter en Excel
                        </button>

                        @if(auth()->user()->role === 'admin')
                            <form action="{{ route('bloodbank.clear') }}" method="POST" onsubmit="return confirmClearTable(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded w-[48%] md:w-auto text-center">
                                    🗑️ Vider le tableau
                                </button>
                            </form>
                        @endif

                        <button id="toggleFullScreen" class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded w-[48%] md:w-auto text-center">
                            ⛶ Plein écran
                        </button>
                    </div>
                </div>

                <!-- Filtres (AUTO-SUBMIT sur input et select) -->
                <form id="filterForm" method="GET" action="{{ route('dashboard') }}" class="mb-6 grid grid-cols-1 md:grid-cols-6 gap-3">
                    <!-- Nom / Prénom -->
                    <input id="nom_prenom_input" type="text" name="nom_prenom" value="{{ request('nom_prenom') }}" placeholder="Nom du patient" class="border rounded px-3 py-2 w-full" autocomplete="off">

                    <!-- Statut -->
                    <select name="statut_demande" class="border rounded px-3 py-2 w-full">
                        <option value="">-- Statut --</option>
                        @php
                            $statuts = ['En cours d\'envoie', 'En cours de traitement', 'Distribuée', 'Non Conforme', 'Périmé', 'Annulé', 'En attente à la BS'];
                        @endphp
                        @foreach($statuts as $statut)
                            <option value="{{ $statut }}" {{ request('statut_demande') == $statut ? 'selected' : '' }}>{{ $statut }}</option>
                        @endforeach
                    </select>

                    <!-- Groupage -->
                    <select name="groupage" class="border rounded px-3 py-2 w-full">
                        <option value="">-- Groupage --</option>
                        @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $group)
                            <option value="{{ $group }}" {{ request('groupage') == $group ? 'selected' : '' }}>{{ $group }}</option>
                        @endforeach
                    </select>

                    <!-- Service -->
                    <select name="service" class="border rounded px-3 py-2 w-full">
                        <option value="">-- Service --</option>
                        @php
                            $services = [
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
                        @endphp
                        @foreach($services as $service)
                            <option value="{{ $service }}" {{ request('service') == $service ? 'selected' : '' }}>{{ $service }}</option>
                        @endforeach
                    </select>

                    <!-- PSL -->
                    <select name="psl" class="border rounded px-3 py-2 w-full">
                        <option value="">-- PSL --</option>
                        @php
                            $pslList = $demandes->pluck('psl')->unique()->sort()->toArray();
                        @endphp
                        @foreach($pslList as $psl)
                            <option value="{{ $psl }}" {{ request('psl') == $psl ? 'selected' : '' }}>{{ $psl }}</option>
                        @endforeach
                    </select>

                    <!-- Boutons -->
                    <div class="flex gap-2 col-span-1 md:col-span-1">
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded flex-1">🔎 Rechercher</button>
                        <a href="{{ route('dashboard') }}" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">♻️</a>
                    </div>
                </form>

                <!-- Tableau -->
                <div id="tableContainer" class="overflow-x-auto w-full transition-all duration-300 border rounded-md">
                    <table class="w-full bg-white border-collapse min-w-[1200px]">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-4 text-left">Date/Heure Demande</th>
                                <th class="py-3 px-4 text-left">Patient</th>
                                <th class="py-3 px-4 text-left">Service</th>
                                <th class="py-3 px-4 text-left">Médecin</th>
                                <th class="py-3 px-4 text-left">Dossier</th>
                                <th class="py-3 px-4 text-left">IPP</th>
                                <th class="py-3 px-4 text-left">Groupage</th>
                                <th class="py-3 px-4 text-left">Phénotype</th>
                                <th class="py-3 px-4 text-left">RAI</th>
                                <th class="py-3 px-4 text-left">PSL</th>
                                <th class="py-3 px-4 text-left">Q. Demandée</th>
                                <th class="py-3 px-4 text-left">Statut</th>
                                <th class="py-3 px-4 text-left">Motif Non Conformité</th>
                                <th class="py-3 px-4 text-left">Q. Reçue</th>
                                <th class="py-3 px-4 text-left">Date/Heure Réception</th>
                                <th class="py-3 px-4 text-left">Réceptionné par</th>
                                <th class="py-3 px-4 text-left">Récupéré par</th>
                                <th class="py-3 px-4 text-left">Code PSL</th>
                                <th class="py-3 px-4 text-left">Date Expiration</th>
                                <th class="py-3 px-4 text-left">Info Service</th>
                                <th class="py-3 px-4 text-left">Date/Heure Récupération</th>
                                <th class="py-3 px-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach($demandes as $demande)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-4 text-xs">
                                        {{ $demande->date_demande->format('d/m/Y') }}<br>
                                        <span class="text-gray-500">{{ $demande->heure_demande }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->nom_prenom }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->service }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->medecin_demandeur }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->dossier }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->ipp }}</td>
                                    <td class="py-3 px-4 text-xs font-bold">{{ $demande->groupage }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->phenotype }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->rai }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->psl }}</td>
                                    <td class="py-3 px-4 text-xs font-bold text-red-600">{{ $demande->quantite_demandee }}</td>
                                    <td class="py-3 px-4 text-xs">
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
                                        <span class="bg-{{ $color }}-200 text-{{ $color }}-800 py-1 px-2 rounded-full text-xs font-bold">
                                            {{ $demande->statut_demande }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->motif_non_conformite ?: '-' }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->quantite_recue ?: '-' }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->date_reception?->format('d/m/Y') ?: '-' }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->receptionne_par ?: '-' }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->recupere_par ?: '-' }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->code_psl ?: '-' }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->date_expiration?->format('d/m/Y') ?: '-' }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->information_service_receveur ?: '-' }}</td>
                                    <td class="py-3 px-4 text-xs">{{ $demande->date_heure_recuperation?->format('d/m/Y H:i') ?: '-' }}</td>
                                    <td class="py-3 px-4 text-xs">
                                        <div class="flex flex-col space-y-1">
                                            <a href="{{ route('bloodbank.show', $demande->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-1 px-2 rounded text-xs text-center transition">Voir</a>
                                            <a href="{{ route('bloodbank.edit', $demande->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded text-xs text-center transition">Modifier</a>
                                            @if(auth()->user()->role === 'admin')
                                            <form action="{{ route('bloodbank.destroy', $demande->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded text-xs w-full" onclick="return confirm('Supprimer cette demande ?')">Supprimer</button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($demandes->hasPages())
                <div class="mt-4">
                    {{ $demandes->withQueryString()->links() }}
                </div>
                @endif

                <div class="mt-4 text-center text-xs text-gray-500">
                    💡 Le tableau est défilable horizontalement pour voir toutes les colonnes
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ✅ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ✅ Export Excel -->
<script>
document.getElementById('exportExcel').addEventListener('click', () => {
    window.location.href = "{{ route('bloodbank.export') }}";
});
</script>

<!-- 🔲 Plein écran -->
<script>
document.getElementById('toggleFullScreen').addEventListener('click', () => {
    const container = document.getElementById('tableContainer');
    if (!document.fullscreenElement) container.requestFullscreen();
    else document.exitFullscreen();
});
</script>

<!-- 🧨 Confirmation stylisée -->
<script>
function confirmClearTable(e) {
    e.preventDefault();

    Swal.fire({
        title: '⚠️ Confirmation requise',
        html: '<p>Cette action supprimera <b>TOUTES</b> les demandes.</p><br>' +
              '<input id="confirmInput" class="swal2-input" placeholder="Tapez: vider le tableau">',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Confirmer',
        cancelButtonText: 'Annuler',
        preConfirm: () => {
            const inputValue = document.getElementById('confirmInput').value;
            if (inputValue !== 'vider le tableau') {
                Swal.showValidationMessage('❌ Vous devez taper exactement : "vider le tableau"');
                return false;
            }
            return inputValue;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = e.target.closest('form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'confirmation';
            input.value = 'vider le tableau';
            form.appendChild(input);
            form.submit();
        }
    });

    return false;
}
</script>

<!-- Script : auto-submit sur input (debounce) et tous les selects -->
<script>
(function() {
    const input = document.getElementById('nom_prenom_input');
    const form = document.getElementById('filterForm');
    if (!input || !form) return;

    // Debounce pour input nom/prenom
    let timer = null;
    const DEBOUNCE_MS = 450;

    function submitFormNow() {
        form.submit();
    }

    input.addEventListener('input', (e) => {
        clearTimeout(timer);
        timer = setTimeout(submitFormNow, DEBOUNCE_MS);
    });

    input.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') {
            clearTimeout(timer);
            submitFormNow();
        }
    });

    // Auto-submit sur tous les select
    const selects = form.querySelectorAll('select');
    selects.forEach(sel => {
        sel.addEventListener('change', submitFormNow);
    });
})();
</script>

<style>
#tableContainer::-webkit-scrollbar {
    height: 8px;
}
#tableContainer::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 4px;
}
#tableContainer::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* ✅ Responsive */
@media (max-width: 768px) {
  table th, table td { 
    white-space: nowrap; 
    font-size: 11px; 
    padding: 8px 6px;
  }
  h2 { font-size: 18px; text-align: center; }
  .flex.gap-3, .flex.flex-wrap { justify-content: center; }
}
</style>
@endsection
