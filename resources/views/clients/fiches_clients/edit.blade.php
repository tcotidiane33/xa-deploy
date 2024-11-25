@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Modifier une Fiche Client</h1>
        <form action="{{ route('fiches-clients.update', ['fiches_client' => $fiches_client->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid gap-6 mb-6 md:grid-cols-4">
                <div>
                    <div class="form-group">
                        <label for="client_id">Client</label>
                        <select name="client_id" id="client_id" class="form-control" disabled>
                            <option value="{{ $fiches_client->client->id }}">{{ $fiches_client->client->name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="periode_paie_id">Période de Paie</label>
                        <select name="periode_paie_id" id="periode_paie_id" class="form-control" disabled>
                            <option value="{{ $fiches_client->periodePaie->id }}">
                                {{ $fiches_client->periodePaie->reference }}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label for="reception_variables">Réception variables</label>
                        <input type="date" name="reception_variables" id="reception_variables" class="form-control"
                            value="{{ $fiches_client->reception_variables }}">
                        @error('reception_variables')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="reception_variables_file">Fichier Réception variables</label>
                        <input type="file" name="reception_variables_file" id="reception_variables_file" class="form-control">
                        @error('reception_variables_file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="preparation_bp">Préparation BP</label>
                        <input type="date" name="preparation_bp" id="preparation_bp" class="form-control"
                            value="{{ $fiches_client->preparation_bp }}">
                        @error('preparation_bp')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="preparation_bp_file">Fichier Préparation BP</label>
                        <input type="file" name="preparation_bp_file" id="preparation_bp_file" class="form-control">
                        @error('preparation_bp_file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label for="validation_bp_client">Validation BP client</label>
                        <input type="date" name="validation_bp_client" id="validation_bp_client" class="form-control"
                            value="{{ $fiches_client->validation_bp_client }}">
                        @error('validation_bp_client')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="validation_bp_client_file">Fichier Validation BP client</label>
                        <input type="file" name="validation_bp_client_file" id="validation_bp_client_file" class="form-control">
                        @error('validation_bp_client_file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="preparation_envoie_dsn">Préparation et envoie DSN</label>
                        <input type="date" name="preparation_envoie_dsn" id="preparation_envoie_dsn" class="form-control"
                            value="{{ $fiches_client->preparation_envoie_dsn }}">
                        @error('preparation_envoie_dsn')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="preparation_envoie_dsn_file">Fichier Préparation et envoie DSN</label>
                        <input type="file" name="preparation_envoie_dsn_file" id="preparation_envoie_dsn_file" class="form-control">
                        @error('preparation_envoie_dsn_file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label for="accuses_dsn">Accusés DSN</label>
                        <input type="date" name="accuses_dsn" id="accuses_dsn" class="form-control"
                            value="{{ $fiches_client->accuses_dsn }}">
                        @error('accuses_dsn')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="accuses_dsn_file">Fichier Accusés DSN</label>
                        <input type="file" name="accuses_dsn_file" id="accuses_dsn_file" class="form-control">
                        @error('accuses_dsn_file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="notes">NOTES</label>
                <textarea name="notes" id="notes" class="form-control">{{ $fiches_client->notes }}</textarea>
                @error('notes')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
@endsection