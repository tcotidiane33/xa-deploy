@extends('layouts.admin')

@section('title', 'Créer un utilisateur')

@section('content')
    <div class="main-content p-1">
        <!-- Formulaire de création d'utilisateur -->
        <div class="bg-white shadow-md rounded-lg p-3">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Créer un utilisateur</h1>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <!-- Nom & Prénom -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom & Prénom</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('email') border-red-500 @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('password') border-red-500 @enderror" required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmation du mot de passe -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>

                <!-- Rôles -->
                <div class="mb-6">
                    <label for="roles" class="block text-sm font-medium text-gray-700 mb-2">Rôles</label>
                    <select id="roles" name="roles[]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('roles') border-red-500 @enderror" multiple required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('roles')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bouton de soumission -->
                <div class="flex justify-end">
                    <button type="submit" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Ajouter un nouveau membre
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
