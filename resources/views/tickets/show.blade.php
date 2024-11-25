@extends('layouts.admin')

@section('title', 'Admin Traitements des paies')

@section('content')
    <div class="main-content">
        <div class="main-page">
            <div class="row">
                <br>
                <br>
            </div>
            <div class="row">
                <div class="container">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-semibold mb-4">{{ $ticket->titre }}</h3>
                                <p><strong>Statut:</strong> {{ $ticket->statut }}</p>
                                <p><strong>Priorité:</strong> {{ $ticket->priorite }}</p>
                                <p><strong>Créé par:</strong> {{ $ticket->createur->name }}</p>
                                <p><strong>Assigné à:</strong> {{ $ticket->assigneA ? $ticket->assigneA->name : 'Non assigné' }}</p>
                                <p><strong>Créé le:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                                <p><strong>Description:</strong></p>
                                <p class="mt-2">{{ $ticket->description }}</p>

                                <div class="mt-4">
                                    <a href="{{ route('tickets.edit', $ticket) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Modifier</a>
                                    <a href="{{ route('tickets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Retour à la liste</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
