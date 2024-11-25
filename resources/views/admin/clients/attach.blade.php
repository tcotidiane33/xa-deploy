@extends('layouts.admin')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">Rattacher un Gestionnaire à un Client</h1>
                    <form action="{{ route('admin.gestionnaire-client.attach') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="client_id">Client</label>
                            <select name="client_id" id="client_id" class="form-control">
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gestionnaire_id">Gestionnaire</label>
                            <select name="gestionnaire_id" id="gestionnaire_id" class="form-control">
                                @foreach($gestionnaires as $gestionnaire)
                                    <option value="{{ $gestionnaire->id }}">{{ $gestionnaire->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="is_principal">Est Principal</label>
                            <input type="checkbox" name="is_principal" id="is_principal" value="1">
                        </div>
                        <button type="submit" class="btn btn-primary">Rattacher</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection