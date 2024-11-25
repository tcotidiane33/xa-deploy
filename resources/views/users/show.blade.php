@extends('layouts.admin')

@section('title', 'Détails de l\'utilisateur')

@section('content')
<div class="container mx-auto p-4 pt-6 md:p-6">
    <h1 class="text-2xl font-bold mb-4">Détails de l'utilisateur</h1>

    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Nom</label>
        <p>{{ $user->name }}</p>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
        <p>{{ $user->email }}</p>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Rôles</label>
        <ul>
            @foreach ($user->roles as $role)
                <li>{{ $role->name }}</li>
            @endforeach
        </ul>
    </div>

    <hr class="my-6">

    <h2 class="text-xl font-bold mb-4">Clients rattachés</h2>
    <form action="{{ route('admin.users.attachClient', $user) }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="client_id">
                Ajouter un client
            </label>
            <select name="client_id" id="client_id" class="form-control" required>
                <option value="">Sélectionner un client</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                Rôle
            </label>
            <select name="role" id="role" class="form-control" required>
                <option value="gestionnaire">Gestionnaire</option>
                <option value="binome">Binôme</option>
                <option value="responsable">Responsable</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>

    <table class="table-auto w-full mt-4">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($user->clients as $client)
                <tr>
                    <td class="border px-4 py-2">{{ $client->name }}</td>
                    <td>
                        @if ($client->gestionnaire_principal_id == $user->id)
                            Gestionnaire
                        @elseif ($client->binome_id == $user->id)
                            Binôme
                        @elseif ($client->responsable_paie_id == $user->id)
                            Responsable
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.users.detachClient', $user) }}" method="POST">
                            @csrf
                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            <input type="hidden" name="role" value="@if ($client->gestionnaire_principal_id == $user->id) gestionnaire @elseif ($client->binome_id == $user->id) binome @elseif ($client->responsable_paie_id == $user->id) responsable @endif">
                            <button type="submit" class="btn btn-danger">Retirer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr class="my-6">

    <h2 class="text-xl font-bold mb-4">Transférer des clients</h2>
    <form action="{{ route('admin.users.transferClients', $user) }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="new_user_id">
                Transférer à
            </label>
            <select name="new_user_id" id="new_user_id" class="form-control" required>
                <option value="">Sélectionner un utilisateur</option>
                @foreach ($users as $otherUser)
                    @if ($otherUser->id !== $user->id)
                        <option value="{{ $otherUser->id }}">{{ $otherUser->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="client_ids">
                Clients à transférer
            </label>
            <select name="client_ids[]" id="client_ids" class="form-control" multiple required>
                @foreach ($user->clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                Rôle
            </label>
            <select name="role" id="role" class="form-control" required>
                <option value="gestionnaire">Gestionnaire</option>
                <option value="binome">Binôme</option>
                <option value="responsable">Responsable</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Transférer</button>
    </form>
</div>
@endsection
