@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Créer un Traitement de Paie</h1>
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @livewire('traitement-paie-form')

    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Informations du Client</h2>
        <div class="grid grid-cols-2 gap-4">
            @forelse($clients as $client)
                <div class="border p-4 rounded">
                    <h3 class="text-xl font-bold">{{ $client->name }}</h3>
                    <p>Saisie des variables: 
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                            {{ $client->saisie_variables ? 'Oui' : 'Non' }}
                        </span>
                    </p>
                    <p>Date de saisie des variables: 
                        <span class="bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                            {{ $client->saisie_variables instanceof \Carbon\Carbon ? $client->saisie_variables->format('d/m/Y') : 'N/A' }}
                        </span>
                    </p>
                    <p>Client formé à la saisie: 
                        <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                            {{ $client->client_forme_saisie ? 'Oui' : 'Non' }}
                        </span>
                    </p>
                    <p>Date de début de prestation: 
                        <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                            {{ $client->date_debut_prestation instanceof \Carbon\Carbon ? $client->date_debut_prestation->format('d/m/Y') : 'N/A' }}
                        </span>
                    </p>
                    <p>Date de signature du contrat: 
                        <span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                            {{ $client->date_signature_contrat instanceof \Carbon\Carbon ? $client->date_signature_contrat->format('d/m/Y') : 'N/A' }}
                        </span>
                    </p>
                    <p>Date de rappel par mail: 
                        <span class="bg-indigo-100 text-indigo-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">
                            {{ $client->date_rappel_mail instanceof \Carbon\Carbon ? $client->date_rappel_mail->format('d/m/Y') : 'N/A' }}
                        </span>
                    </p>
                    <p>Taux AT: 
                        <span class="bg-purple-100 text-purple-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">
                            {{ $client->taux_at }}
                        </span>
                    </p>
                    <p>Adhésion MyDRH: 
                        <span class="bg-pink-100 text-pink-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-pink-900 dark:text-pink-300">
                            {{ $client->adhesion_mydrh ? 'Oui' : 'Non' }}
                        </span>
                    </p>
                    <p>Est un cabinet: 
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                            {{ $client->is_cabinet ? 'Oui' : 'Non' }}
                        </span>
                    </p>
                    <p>Portefeuille cabinet: 
                        <span class="bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                            {{ $client->portfolioCabinet ? $client->portfolioCabinet->name : 'N/A' }}
                        </span>
                    </p>
                </div>
            @empty
                @livewire('skeleton-loader')
            @endforelse
        </div>
    </div>
</div>
@endsection

