@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">Nouvelle Demande de Sang</h2>

                <form method="POST" action="{{ route('bloodbank.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Informations de base -->
                        <div class="md:col-span-2 lg:col-span-3">
                            <h3 class="text-lg font-semibold mb-4 text-blue-600 border-b pb-2">Informations Sur la Demande</h3>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="date_demande">
                                Date de demande *
                            </label>
                            <input type="date" name="date_demande" id="date_demande"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('date_demande', date('Y-m-d')) }}" required>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="heure_demande">
                                Heure de demande *
                            </label>
                            <input type="time" name="heure_demande" id="heure_demande"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('heure_demande', date('H:i')) }}" required>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="nom_prenom">
                                Nom et Prénom du patient *
                            </label>
                            <input type="text" name="nom_prenom" id="nom_prenom"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('nom_prenom') }}" required>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="service">
                                Service *
                            </label>
                            <select name="service" id="service"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Sélectionner un service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service }}" {{ old('service') == $service ? 'selected' : '' }}>
                                        {{ $service }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="medecin_demandeur">
                                Médecin demandeur *
                            </label>
                            <input type="text" name="medecin_demandeur" id="medecin_demandeur"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('medecin_demandeur') }}" required>
                        </div>

                        <!-- Informations médicales -->


                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="dossier">
                                N° Dossier *
                            </label>
                            <input type="text" name="dossier" id="dossier"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('dossier') }}" required>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="ipp">
                                IPP *
                            </label>
                            <input type="text" name="ipp" id="ipp"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('ipp') }}" required>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="groupage">
                                Groupage sanguin *
                            </label>
                            <select name="groupage" id="groupage"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Sélectionner</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $grp)
                                    <option value="{{ $grp }}" {{ old('groupage') == $grp ? 'selected' : '' }}>{{ $grp }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Phénotype -->
                        <div class="md:col-span-3">
    <label class="block text-gray-700 text-sm font-bold mb-2">Phénotype *</label>
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 p-4 bg-gray-50 rounded">

        @foreach(['C','c','E','e','K'] as $item)
        <div>
            <label class="block text-gray-600 text-sm mb-1">{{ $item }}</label>
            <select name="phenotype_{{ $item }}" class="w-full border rounded py-1 px-2 phenotype-select" required>
                <option value="{{ $item }}+">{{ $item }}+</option>
                <option value="{{ $item }}-">{{ $item }}-</option>
            </select>
        </div>
        @endforeach
    </div>
</div>


                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="rai">
                                RAI *
                            </label>
                            <select name="rai" id="rai"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Sélectionner</option>
                                <option value="Positive" {{ old('rai') == 'Positive' ? 'selected' : '' }}>Positive</option>
                                <option value="Négative" {{ old('rai') == 'Négative' ? 'selected' : '' }}>Négative</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="psl">
                                PSL *
                            </label>
                            <select name="psl" id="psl"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Sélectionner</option>
                                <option value="CGR" {{ old('psl') == 'CGR' ? 'selected' : '' }}>CGR</option>
                                <option value="CP" {{ old('psl') == 'CP' ? 'selected' : '' }}>CP</option>
                                <option value="PFC" {{ old('psl') == 'PFC' ? 'selected' : '' }}>PFC</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="quantite_demandee">
                                Quantité demandée *
                            </label>
                            <input type="number" name="quantite_demandee" id="quantite_demandee"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('quantite_demandee') }}" min="1" required>
                        </div>

                        <!-- Suivi de la Demande -->
                        <div class="md:col-span-2 lg:col-span-3 mt-4">
                            <h3 class="text-lg font-semibold mb-4 text-purple-600 border-b pb-2">Suivi de la Demande</h3>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="statut_demande">
                                Statut de la demande *
                            </label>
                            <select name="statut_demande" id="statut_demande"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                @foreach($statuts as $statut)
                                    <option value="{{ $statut }}" {{ old('statut_demande', 'En cours d\'envoie') == $statut ? 'selected' : '' }}>
                                        {{ $statut }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="motif_non_conformite">
                                Motif de non conformité
                            </label>
                            <textarea name="motif_non_conformite" id="motif_non_conformite"
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                      rows="3">{{ old('motif_non_conformite') }}</textarea>
                        </div>
                        <div class="md:col-span-2 lg:col-span-3 mt-4">
                            <h3 class="text-lg font-semibold mb-4 text-green-600 border-b pb-2">Réception De La Demande</h3>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="quantite_recue">
                                Quantité reçue
                            </label>
                            <input type="number" name="quantite_recue" id="quantite_recue"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('quantite_recue') }}" min="0">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="date_reception">
                                Date de réception
                            </label>
                            <input type="date" name="date_reception" id="date_reception"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('date_reception') }}">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="heure_reception">
                                Heure de réception
                            </label>
                            <input type="time" name="heure_reception" id="heure_reception"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('heure_reception') }}">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="receptionne_par">
                                Réceptionné par
                            </label>
                            <select name="receptionne_par" id="receptionne_par"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Sélectionner</option>
                                @foreach($receptionnesPar as $personne)
                                    <option value="{{ $personne }}" {{ old('receptionne_par') == $personne ? 'selected' : '' }}>
                                        {{ $personne }}
                                    </option>
                                @endforeach
                            </select>
                        </div>



                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="code_psl">
                                Code PSL
                            </label>
                            <input type="text" name="code_psl" id="code_psl"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('code_psl') }}">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="date_expiration">
                                Date d'expiration
                            </label>
                            <input type="date" name="date_expiration" id="date_expiration"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('date_expiration') }}">
                        </div>

                        <div class="md:col-span-2">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="information_service_receveur">
        Information du service receveur
    </label>
    <select name="information_service_receveur" id="information_service_receveur"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            >
        <option value="">-- Sélectionner --</option>
        <option value="Oui" {{ old('information_service_receveur') == 'Oui' ? 'selected' : '' }}>Oui</option>
        <option value="Non" {{ old('information_service_receveur') == 'Non' ? 'selected' : '' }}>Non</option>
    </select>
</div>

<div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="recupere_par">
                                Récupéré par
                            </label>
                            <input type="text" name="recupere_par" id="recupere_par"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('recupere_par') }}">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="date_heure_recuperation">
                                Date et heure de récupération
                            </label>
                            <input type="datetime-local" name="date_heure_recuperation" id="date_heure_recuperation"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('date_heure_recuperation') }}">
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-8">
                        <a href="{{ route('bloodbank.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Annuler
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Créer la demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.phenotype-select').forEach(select => {
    select.addEventListener('change', updatePhenotypeDisplay);
});

function updatePhenotypeDisplay() {
    const C = document.querySelector('select[name="phenotype_C"]').value;
    const c = document.querySelector('select[name="phenotype_c"]').value;
    const E = document.querySelector('select[name="phenotype_E"]').value;
    const e = document.querySelector('select[name="phenotype_e"]').value;
    const K = document.querySelector('select[name="phenotype_K"]').value;

    const phenotypeComplet =  C + c + E + e + K;
    document.getElementById('phenotype-display').textContent = phenotypeComplet;
}

// Initialiser l'affichage
updatePhenotypeDisplay();
</script>
@endsection
