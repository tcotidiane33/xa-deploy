@extends('layouts.admin')

@section('title', 'Paramètres de l\'Application')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Paramètres du système</h1>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Paramètres généraux --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Paramètres généraux</h2>
                <p class="text-gray-600 mb-4">Configuration générale de l'application et informations de l'entreprise.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative group">
                        <x-form.input 
                            name="site_name" 
                            label="Nom du site" 
                            :value="$settings['site_name'] ?? ''" 
                            required 
                        />
                        <div class="hidden group-hover:block absolute z-10 bg-black text-white text-sm rounded p-2 mt-1">
                            Nom qui apparaîtra dans l'en-tête et le titre des pages
                        </div>
                    </div>

                    <div class="relative group">
                        <x-form.input 
                            name="company_name" 
                            label="Nom de l'entreprise" 
                            :value="$settings['company_name'] ?? ''" 
                            required 
                        />
                        <div class="hidden group-hover:block absolute z-10 bg-black text-white text-sm rounded p-2 mt-1">
                            Raison sociale de l'entreprise utilisée dans les documents officiels
                        </div>
                    </div>

                    <div class="relative group">
                        <x-form.textarea 
                            name="site_description" 
                            label="Description du site" 
                            :value="$settings['site_description'] ?? ''" 
                        />
                        <div class="hidden group-hover:block absolute z-10 bg-black text-white text-sm rounded p-2 mt-1">
                            Description utilisée pour le référencement et la page d'accueil
                        </div>
                    </div>

                    <x-form.input name="company_email" type="email" label="Email de l'entreprise" :value="$settings['company_email'] ?? ''" required />
                </div>
            </div>

            {{-- Paramètres système --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Paramètres système</h2>
                <p class="text-gray-600 mb-4">Configuration technique et comportement général de l'application.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative group">
                        <x-form.toggle 
                            name="maintenance_mode" 
                            label="Mode maintenance" 
                            :checked="$settings['maintenance_mode'] ?? false"
                        />
                        <div class="hidden group-hover:block absolute z-10 bg-black text-white text-sm rounded p-2 mt-1">
                            Active/désactive l'accès au site pour les utilisateurs non-administrateurs
                        </div>
                    </div>

                    <div class="relative group">
                        <x-form.select 
                            name="default_user_role" 
                            label="Rôle utilisateur par défaut" 
                            :options="$roles" 
                            :value="$settings['default_user_role'] ?? ''" 
                            required 
                        />
                        <div class="hidden group-hover:block absolute z-10 bg-black text-white text-sm rounded p-2 mt-1">
                            Rôle attribué automatiquement aux nouveaux utilisateurs
                        </div>
                    </div>

                    <x-form.input name="items_per_page" type="number" label="Éléments par page" :value="$settings['items_per_page'] ?? 10" min="5" max="100" required />
                    <x-form.input name="session_lifetime" type="number" label="Durée de session (minutes)" :value="$settings['session_lifetime'] ?? 120" min="1" max="1440" required />
                </div>
            </div>

            {{-- Paramètres de sécurité --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Paramètres de sécurité</h2>
                <p class="text-gray-600 mb-4">Configuration des options de sécurité et d'authentification.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative group">
                        <x-form.toggle 
                            name="enable_2fa" 
                            label="Authentification à deux facteurs" 
                            :checked="$settings['enable_2fa'] ?? false"
                        />
                        <div class="hidden group-hover:block absolute z-10 bg-black text-white text-sm rounded p-2 mt-1">
                            Active la 2FA pour tous les utilisateurs lors de la connexion
                        </div>
                    </div>

                    <div class="relative group">
                        <x-form.input 
                            name="password_min_length" 
                            type="number" 
                            label="Longueur minimale du mot de passe" 
                            :value="$settings['password_min_length'] ?? 8" 
                            min="8" 
                            required 
                        />
                        <div class="hidden group-hover:block absolute z-10 bg-black text-white text-sm rounded p-2 mt-1">
                            Nombre minimum de caractères requis pour les mots de passe
                        </div>
                    </div>

                    <x-form.input name="max_login_attempts" type="number" label="Tentatives de connexion maximales" :value="$settings['max_login_attempts'] ?? 5" min="1" required />
                    <x-form.toggle name="require_password_special_chars" label="Caractères spéciaux requis" :checked="$settings['require_password_special_chars'] ?? true" />
                </div>
            </div>

            {{-- Paramètres métier --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Paramètres métier</h2>
                <p class="text-gray-600 mb-4">Configuration spécifique aux processus métier de l'application.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative group">
                        <x-form.toggle 
                            name="enable_client_portal" 
                            label="Portail client" 
                            :checked="$settings['enable_client_portal'] ?? false"
                        />
                        <div class="hidden group-hover:block absolute z-10 bg-black text-white text-sm rounded p-2 mt-1">
                            Active l'accès au portail client pour les clients externes
                        </div>
                    </div>

                    <div class="relative group">
                        <x-form.toggle 
                            name="enable_document_validation" 
                            label="Validation des documents" 
                            :checked="$settings['enable_document_validation'] ?? true"
                        />
                        <div class="hidden group-hover:block absolute z-10 bg-black text-white text-sm rounded p-2 mt-1">
                            Active la validation des documents avant archivage
                        </div>
                    </div>

                    <div class="relative group">
                        <x-form.input 
                            name="default_document_retention_days" 
                            type="number" 
                            label="Durée de conservation des documents (jours)" 
                            :value="$settings['default_document_retention_days'] ?? 365" 
                            min="1" 
                            required 
                        />
                        <div class="hidden group-hover:block absolute z-10 bg-black text-white text-sm rounded p-2 mt-1">
                            Durée de conservation par défaut des documents avant archivage
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    @push('styles')
    <style>
        .tooltip {
            @apply invisible absolute;
        }
        
        .has-tooltip:hover .tooltip {
            @apply visible z-50;
        }
    </style>
    @endpush
@endsection
