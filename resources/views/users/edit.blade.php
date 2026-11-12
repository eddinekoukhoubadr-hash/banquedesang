@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">

                <h2 class="text-2xl font-bold mb-6 text-gray-700">
                    👤 Modifier l'utilisateur
                </h2>

                <!-- ✅ Informations utilisateur non modifiables -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nom complet</label>
                        <input type="text" value="{{ $user->name }}" readonly
                            class="w-full bg-gray-100 border border-gray-300 rounded-md p-2 text-gray-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Adresse email</label>
                        <input type="text" value="{{ $user->email }}" readonly
                            class="w-full bg-gray-100 border border-gray-300 rounded-md p-2 text-gray-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Rôle</label>
                        <input type="text" value="{{ ucfirst($user->role) }}" readonly
                            class="w-full bg-gray-100 border border-gray-300 rounded-md p-2 text-gray-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Date d'inscription</label>
                        <input type="text" value="{{ $user->created_at->format('d/m/Y à H:i') }}" readonly
                            class="w-full bg-gray-100 border border-gray-300 rounded-md p-2 text-gray-500 cursor-not-allowed">
                    </div>
                </div>

                <hr class="my-8 border-gray-300">

                <!-- ✅ Formulaire de modification du mot de passe -->
                <h3 class="text-xl font-bold text-gray-700 mb-4">🔒 Modifier le mot de passe de l'utilisateur</h3>

                @if(session('success'))
                    <div class="bg-green-100 text-green-700 border border-green-300 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 border border-red-300 px-4 py-3 rounded mb-4">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Nouveau mot de passe
                        </label>
                        <input id="password" type="password" name="password"
                            placeholder="Laisser vide pour conserver l'actuel"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirmer le nouveau mot de passe
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            placeholder="Confirmer le nouveau mot de passe"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('users.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-semibold px-6 py-2 rounded-md transition">
                            Annuler
                        </a>
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-md transition">
                            💾 Enregistrer le mot de passe
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection