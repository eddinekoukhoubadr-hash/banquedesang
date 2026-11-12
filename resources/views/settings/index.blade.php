@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- GESTION DES SERVICES -->
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <h3 class="text-xl font-bold mb-4 text-blue-600">🏥 Gestion des Services</h3>

                <form action="{{ route('settings.service.add') }}" method="POST" class="flex gap-2 mb-4">
                    @csrf
                    <input type="text" name="nom" placeholder="Nouveau service" class="border rounded w-full px-3 py-2" required>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Ajouter</button>
                </form>

                <ul class="divide-y divide-gray-200">
                    @forelse($setting->services ?? [] as $service)
                        <li class="py-2 flex justify-between items-center">
                            <span>{{ $service }}</span>
                            <form action="{{ route('settings.service.delete') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="nom" value="{{ $service }}">
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Supprimer</button>
                            </form>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Aucun service dans la base.</li>
                    @endforelse
                </ul>
            </div>

            <!-- GESTION DES TECHNICIENNES -->
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <h3 class="text-xl font-bold mb-4 text-green-600">👩‍⚕️ Gestion des Techniciennes</h3>

                <form action="{{ route('settings.tech.add') }}" method="POST" class="flex gap-2 mb-4">
                    @csrf
                    <input type="text" name="nom" placeholder="Nouvelle technicienne" class="border rounded w-full px-3 py-2" required>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Ajouter</button>
                </form>

                <ul class="divide-y divide-gray-200">
                    @forelse($setting->techniciennes ?? [] as $tech)
                        <li class="py-2 flex justify-between items-center">
                            <span>{{ $tech }}</span>
                            <form action="{{ route('settings.tech.delete') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="nom" value="{{ $tech }}">
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Supprimer</button>
                            </form>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Aucune technicienne dans la base.</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection
