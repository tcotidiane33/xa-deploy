@extends('layouts.admin')

@section('title', 'Créer un utilisateur')

@section('content')
<div class="main-content">
    <div class="container mx-auto px-4 py-8">
        <div class="user-form-container bg-gray-50 rounded-xl shadow-lg p-6 relative">
            <style>
                .user-form-container::before {
                    content: 'UTILISATEUR';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%) rotate(-45deg);
                    font-size: 8rem;
                    font-weight: bold;
                    color: rgba(229, 231, 235, 0.2);
                    white-space: nowrap;
                    pointer-events: none;
                    z-index: 0;
                }

                .form-input {
                    @apply shadow-sm border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200;
                }

                .form-label {
                    @apply block text-gray-700 text-sm font-medium mb-2;
                }

                .form-group {
                    @apply mb-6 relative z-10;
                }

                .btn-action {
                    @apply px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 flex items-center relative overflow-hidden shadow-md hover:shadow-lg;
                }

                .btn-action:hover {
                    @apply transform scale-105 font-bold;
                    text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
                }

                .btn-action:active {
                    @apply transform scale-95;
                }

                .btn-submit {
                    @apply bg-gradient-to-r from-green-400 to-blue-500 text-white hover:from-green-500 hover:to-blue-600 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
                }

                .btn-cancel {
                    @apply bg-gradient-to-r from-gray-400 to-gray-500 text-white hover:from-gray-500 hover:to-gray-600 focus:ring-2 focus:ring-offset-2 focus:ring-gray-400;
                }

                .role-checkbox {
                    @apply rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200;
                }
            </style>

            <h2 class="text-2xl font-bold text-gray-800 mb-6 relative z-10">
                <i class="fas fa-user-plus text-blue-500 mr-2"></i>Créer un Utilisateur
            </h2>

            <form method="POST" action="{{ route('users.store') }}" class="relative z-10">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <!-- Informations de base -->
                    <div class="space-y-4">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user text-blue-500 mr-1"></i>Nom complet *
                            </label>
                            <input type="text" name="name" id="name" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope text-green-500 mr-1"></i>Email *
                            </label>
                            <input type="email" name="email" id="email" class="form-input" required>
                        </div>
                    </div>

                    <!-- Mot de passe -->
                    <div class="space-y-4">
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock text-red-500 mr-1"></i>Mot de passe *
                            </label>
                            <input type="password" name="password" id="password" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock text-orange-500 mr-1"></i>Confirmer le mot de passe *
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
                        </div>
                    </div>
                </div>

                <!-- Rôles -->
                <div class="form-group mt-6">
                    <label class="form-label">
                        <i class="fas fa-user-tag text-purple-500 mr-1"></i>Rôles *
                    </label>
                    <div class="grid grid-cols-3 gap-4 mt-2">
                        @foreach($roles as $role)
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="role-checkbox">
                                <label class="text-sm text-gray-700">{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4 mt-8 pt-4 border-t border-gray-200">
                    <a href="{{ route('users.index') }}" class="btn-action btn-cancel">
                        <i class="fas fa-times mr-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn-action btn-submit">
                        <i class="fas fa-save mr-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
