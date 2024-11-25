@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Créer une Fiche Client</h1>
    <form action="{{ route('fiches-clients.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                <select name="client_id" id="client_id" class="form-control mt-1 block w-full">
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
                @error('client_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="periode_paie_id" class="block text-sm font-medium text-gray-700">Période de Paie</label>
                <select name="periode_paie_id" id="periode_paie_id" class="form-control mt-1 block w-full">
                    @foreach ($periodesPaie as $periode)
                        <option value="{{ $periode->id }}">{{ $periode->reference }}</option>
                    @endforeach
                </select>
                @error('periode_paie_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="reception_variables" class="block text-sm font-medium text-gray-700">Réception variables</label>
                <input type="date" name="reception_variables" id="reception_variables" class="form-control mt-1 block w-full">
                @error('reception_variables')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="preparation_bp" class="block text-sm font-medium text-gray-700">Préparation BP</label>
                <input type="date" name="preparation_bp" id="preparation_bp" class="form-control mt-1 block w-full">
                @error('preparation_bp')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="validation_bp_client" class="block text-sm font-medium text-gray-700">Validation BP client</label>
                <input type="date" name="validation_bp_client" id="validation_bp_client" class="form-control mt-1 block w-full">
                @error('validation_bp_client')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="preparation_envoie_dsn" class="block text-sm font-medium text-gray-700">Préparation et envoie DSN</label>
                <input type="date" name="preparation_envoie_dsn" id="preparation_envoie_dsn" class="form-control mt-1 block w-full">
                @error('preparation_envoie_dsn')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="accuses_dsn" class="block text-sm font-medium text-gray-700">Accusés DSN</label>
                <input type="date" name="accuses_dsn" id="accuses_dsn" class="form-control mt-1 block w-full">
                @error('accuses_dsn')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            {{-- <div class="form-group">
                <label for="teledec_urssaf" class="block text-sm font-medium text-gray-700">TELEDEC URSSAF</label>
                <input type="date" name="teledec_urssaf" id="teledec_urssaf" class="form-control mt-1 block w-full">
                @error('teledec_urssaf')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div> --}}
            <div class="form-group col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" id="notes" class="form-control mt-1 block w-full"></textarea>
                @error('notes')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <button type="submit" class="mt-4 btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection