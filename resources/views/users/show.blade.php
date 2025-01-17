@extends('layouts.admin')

@section('title', 'Détails de l\'utilisateur')

@section('content')
<div class=" text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Première colonne -->
        <div class="col-span-1 p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg ">
            <h1 class="text-xl font-bold">Détails de l'utilisateur</h1>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nom</label>
                <p class="bg-gray-100 text-gray-900 text-sm font-medium px-4 py-2 rounded-lg">{{ $user->name }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <p class="bg-gray-100 text-gray-900 text-sm font-medium px-4 py-2 rounded-lg">{{ $user->email }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Rôles</label>
                <ul class="bg-gray-100 text-gray-900 text-sm font-medium px-4 py-2 rounded-lg">
                    @foreach ($user->roles as $role)
                        <li>{{ $role->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Deuxième colonne -->
        <div class="col-span-1 p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg ">
            <h2 class="text-xl font-bold ">Clients rattachés</h2>
            <form action="{{ route('admin.users.attachClient', $user) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="client_id">
                        Ajouter un client
                    </label>
                    <select name="client_id" id="client_id" class="form-control bg-gray-100 text-gray-900 text-sm font-medium px-4 py-2 rounded-lg w-full" required>
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
                    <select name="role" id="role" class="form-control bg-gray-100 text-gray-900 text-sm font-medium px-4 py-2 rounded-lg w-full" required>
                        <option value="gestionnaire">Gestionnaire</option>
                        <option value="binome">Binôme</option>
                        <option value="responsable">Responsable</option>
                    </select>
                </div>
                <div class="mb-4 flex items-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">Ajouter</button>
                </div>
            </form>

            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mt-4">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="py-3 px-6">Nom</th>
                        <th scope="col" class="py-3 px-6">Rôle</th>
                        <th scope="col" class="py-3 px-6">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->clients as $client)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-2 px-6">{{ $client->name }}</td>
                            <td class="py-2 px-6">
                                @if ($client->gestionnaire_principal_id == $user->id)
                                    Gestionnaire
                                @elseif ($client->binome_id == $user->id)
                                    Binôme
                                @elseif ($client->responsable_paie_id == $user->id)
                                    Responsable
                                @endif
                            </td>
                            <td class="py-2 px-6">
                                <form action="{{ route('admin.users.detachClient', $user) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                                    <input type="hidden" name="role" value="@if ($client->gestionnaire_principal_id == $user->id) gestionnaire @elseif ($client->binome_id == $user->id) binome @elseif ($client->responsable_paie_id == $user->id) responsable @endif">
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Retirer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Troisième colonne -->
        <div class="col-span-1 p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg ">
            <h2 class="text-xl font-bold ">Transférer des clients</h2>
            <form action="{{ route('admin.users.transferClients', $user) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="new_user_id">
                        Transférer à
                    </label>
                    <select name="new_user_id" id="new_user_id" class="form-control bg-gray-100 text-gray-900 text-sm font-medium px-4 py-2 rounded-lg w-full" required>
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
                    <select name="client_ids[]" id="client_ids" class="form-control bg-gray-100 text-gray-900 text-sm font-medium px-4 py-2 rounded-lg w-full" multiple required>
                        @foreach ($user->clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                        Rôle
                    </label>
                    <select name="role" id="role" class="form-control bg-gray-100 text-gray-900 text-sm font-medium px-4 py-2 rounded-lg w-full" required>
                        <option value="gestionnaire">Gestionnaire</option>
                        <option value="binome">Binôme</option>
                        <option value="responsable">Responsable</option>
                    </select>
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2 inline-block">Transférer</button>
            </form>
        </div>
    </div>
</div>
@endsection
