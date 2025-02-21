@extends('layouts.admin')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">Créer un Client</h1>
                    <form action="{{ route('admin.clients.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nom du Client</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="responsable_paie_id">Responsable Paie</label>
                            <select name="responsable_paie_id" id="responsable_paie_id" class="form-control">
                                <option value="">Sélectionnez un responsable paie</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gestionnaire_principal_id">Gestionnaire Principal</label>
                            <select name="gestionnaire_principal_id" id="gestionnaire_principal_id" class="form-control">
                                <option value="">Sélectionnez un gestionnaire principal</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gestionnaires_secondaires">Gestionnaires Secondaires</label>
                            <select name="gestionnaires_secondaires[]" id="gestionnaires_secondaires" class="form-control" multiple>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection