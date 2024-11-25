@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <br>
    </div>
    <h2>Gestion des clients pour {{ $user->name }}</h2>

    <h3>Clients rattachés</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Nom du client</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->name }}</td>
                <td>
                    <form action="{{ route('users.detach_client', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                        <button type="submit" class="btn btn-danger btn-sm">Détacher</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Rattacher un nouveau client</h3>
    <form action="{{ route('users.attach_client', $user) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="client_id">Sélectionner un client</label>
            <select name="client_id" id="client_id" class="form-control">
                @foreach(App\Models\Client::whereDoesntHave('gestionnaires', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->get() as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Rattacher</button>
    </form>

    <h3>Transférer des clients</h3>
    <form action="{{ route('users.transfer_clients', $user) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="to_user_id">Transférer vers</label>
            <select name="to_user_id" id="to_user_id" class="form-control">
                @foreach(App\Models\User::where('id', '!=', $user->id)->get() as $otherUser)
                    <option value="{{ $otherUser->id }}">{{ $otherUser->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Sélectionner les clients à transférer</label>
            @foreach($clients as $client)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="client_ids[]" value="{{ $client->id }}" id="client_{{ $client->id }}">
                    <label class="form-check-label" for="client_{{ $client->id }}">
                        {{ $client->name }}
                    </label>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-warning">Transférer les clients sélectionnés</button>
    </form>
</div>
@endsection