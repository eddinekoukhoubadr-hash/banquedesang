@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">

                <h2 class="text-2xl font-bold mb-6 text-gray-700">
                    👤 Mon profil
                </h2>

                <!-- Informations de l'utilisateur -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-600 mb-2">Nom complet :</h3>
                        <p class="text-gray-800">{{ $user->name }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-600 mb-2">Adresse email :</h3>
                        <p class="text-gray-800">{{ $user->email }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-600 mb-2">Rôle :</h3>
                        <span class="inline-block px-3 py-1 text-sm rounded-full 
                            {{ $user->role === 'admin' ? 'bg-purple-200 text-purple-800' : 'bg-blue-200 text-blue-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-600 mb-2">Date d'inscription :</h3>
                        <p class="text-gray-800">
                            {{ $user->created_at ? $user->created_at->format('d/m/Y à H:i') : '—' }}
                        </p>
                    </div>
                </div>

                <!-- Ligne de séparation -->
                <hr class="my-6 border-gray-300">

                <!-- Formulaire de modification du mot de passe -->
                <h3 class="text-xl font-bold text-gray-700 mb-4">🔒 Modifier mon mot de passe</h3>

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

                <form method="POST" action="{{ route('user.password.update') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">
                            Mot de passe actuel
                        </label>
                        <input id="current_password" type="password" name="current_password" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Nouveau mot de passe
                        </label>
                        <input id="password" type="password" name="password" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirmer le nouveau mot de passe
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-md transition">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
