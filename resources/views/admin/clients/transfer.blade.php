@extends('layouts.admin')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">Transférer des Clients</h1>
                    <form action="{{ route('admin.clients.transfer') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="client_ids">Clients</label>
                            <select name="client_ids[]" id="client_ids" class="form-control" multiple required>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="new_gestionnaire_id">Nouveau Gestionnaire</label>
                            <select name="new_gestionnaire_id" id="new_gestionnaire_id" class="form-control" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Transférer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection