@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Breadcrumbs --}}
    <nav class="text-sm mb-6">
        <ol class="list-none p-0 inline-flex">
            @foreach($breadcrumbs as $breadcrumb)
                <li class="flex items-center">
                    <a href="{{ $breadcrumb['url'] }}" class="text-blue-600 hover:text-blue-800">
                        {{ $breadcrumb['name'] }}
                    </a>
                    @if(!$loop->last)
                        <svg class="w-3 h-3 mx-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Clients gérés par {{ $user->name }}</h2>
        <p class="mt-2 text-gray-600">Gérez les relations avec vos clients et leurs transferts</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filtres --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('users.manage_clients', $user) }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="role_filter" class="block text-sm font-medium text-gray-700">Filtrer par rôle</label>
                <select name="role_filter" id="role_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tous les rôles</option>
                    <option value="gestionnaire" {{ request('role_filter') === 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                    <option value="responsable" {{ request('role_filter') === 'responsable' ? 'selected' : '' }}>Responsable</option>
                    <option value="binome" {{ request('role_filter') === 'binome' ? 'selected' : '' }}>Binôme</option>
                </select>
            </div>
            <div>
                <label for="periode_filter" class="block text-sm font-medium text-gray-700">Filtrer par période</label>
                <select name="periode_filter" id="periode_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Toutes les périodes</option>
                    @foreach($periodesPaie as $periode)
                        <option value="{{ $periode->id }}" {{ request('periode_filter') == $periode->id ? 'selected' : '' }}>
                            {{ $periode->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    {{-- Table des clients --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle actuel</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière fiche</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($managedClients as $relation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $relation['client']->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $relation['client']->reference }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($relation['role'] === 'gestionnaire') bg-green-100 text-green-800
                                @elseif($relation['role'] === 'responsable') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($relation['role']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($relation['fiche_client'])
                                <div class="text-sm text-gray-900">
                                    Période : {{ $relation['fiche_client']->periodePaie->nom }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Mise à jour : {{ $relation['fiche_client']->updated_at->format('d/m/Y H:i') }}
                                </div>
                            @else
                                <span class="text-sm text-gray-500">Aucune fiche</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="openTransferModal('{{ $relation['client']->id }}', '{{ $relation['role'] }}')" 
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                Transférer
                            </button>
                            <a href="{{ route('clients.show', $relation['client']) }}" 
                               class="text-gray-600 hover:text-gray-900">
                                Voir détails
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Aucun client géré pour le moment
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('users.partials.transfer_modal')

@endsection