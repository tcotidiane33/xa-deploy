@extends('layouts.admin')

@section('title', 'Détails de l\'utilisateur')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- En-tête avec breadcrumbs --}}
    <!-- <nav class="text-sm mb-4">
        <ol class="list-none p-0 inline-flex">
            @foreach($breadcrumbs as $breadcrumb)
                <li class="flex items-center">
                    <a href="{{ $breadcrumb['url'] }}" class="text-blue-600 hover:text-blue-800">{{ $breadcrumb['name'] }}</a>
                    @if(!$loop->last)
                        <svg class="w-3 h-3 mx-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav> -->

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Informations de l'utilisateur --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                {{ $user->name }}
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Rôles</label>
                    <div class="mt-1 flex flex-wrap gap-2">
                        @foreach($user->roles as $role)
                            <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Date d'inscription</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Clients gérés --}}
        <div class="bg-white rounded-lg shadow-md p-6 md:col-span-2">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Clients rattachés
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($user->getAllClients() as $client)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $client->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $client->reference }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($client->gestionnaire_principal_id === $user->id)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Gestionnaire
                                        </span>
                                    @elseif($client->responsable_paie_id === $user->id)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Responsable
                                        </span>
                                    @elseif($client->binome_id === $user->id)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Binôme
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('clients.show', $client) }}" class="text-indigo-600 hover:text-indigo-900">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
