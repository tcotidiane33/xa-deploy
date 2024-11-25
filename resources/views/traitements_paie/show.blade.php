@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Détails du Traitement de Paie</h1>
    <div class="bg-white w-full shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="grid gap-6 mb-6 md:grid-cols-4">
            <div>
                <div class="form-group">
                    <label for="reference">Référence</label>
                    <input type="text" id="reference" class="form-control" value="{{ $traitementPaie->reference }}" disabled>
                </div>
                <div class="form-group">
                    <label for="client">Client</label>
                    <input type="text" id="client" class="form-control" value="{{ $traitementPaie->client->name }}" disabled>
                </div>
                <div class="form-group">
                    <label for="gestionnaire">Gestionnaire</label>
                    <input type="text" id="gestionnaire" class="form-control" value="{{ $traitementPaie->gestionnaire->name }}" disabled>
                </div>
                <div class="form-group">
                    <label for="periode_paie">Période de Paie</label>
                    <input type="text" id="periode_paie" class="form-control" value="{{ $traitementPaie->periodePaie->reference }}" disabled>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label for="reception_variables">Réception variables</label>
                    <input type="date" id="reception_variables" class="form-control" value="{{ $traitementPaie->reception_variables }}" disabled>
                </div>
                <div class="form-group">
                    <label for="preparation_bp">Préparation BP</label>
                    <input type="date" id="preparation_bp" class="form-control" value="{{ $traitementPaie->preparation_bp }}" disabled>
                </div>
                <div class="form-group">
                    <label for="validation_bp_client">Validation BP client</label>
                    <input type="date" id="validation_bp_client" class="form-control" value="{{ $traitementPaie->validation_bp_client }}" disabled>
                </div>
                <div class="form-group">
                    <label for="preparation_envoie_dsn">Préparation et envoie DSN</label>
                    <input type="date" id="preparation_envoie_dsn" class="form-control" value="{{ $traitementPaie->preparation_envoie_dsn }}" disabled>
                </div>
                <div class="form-group">
                    <label for="accuses_dsn">Accusés DSN</label>
                    <input type="date" id="accuses_dsn" class="form-control" value="{{ $traitementPaie->accuses_dsn }}" disabled>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" class="form-control" disabled>{{ $traitementPaie->notes }}</textarea>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Fichiers associés</h2>
            <div class="grid gap-6 mb-6 md:grid-cols-4">
                @foreach ($traitementPaie->fichiers as $fichier)
                    <div class="form-group">
                        <label for="fichier_{{ $fichier->id }}">{{ $fichier->nom }}</label>
                        <a href="{{ route('fichiers.download', $fichier->id) }}" class="text-blue-600 hover:text-blue-900">Télécharger</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection