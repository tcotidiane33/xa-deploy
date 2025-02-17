@extends('layouts.admin')

@section('title', 'Créer un Backup')

@push('styles')
<style>
    .backup-form-container {
        position: relative;
        overflow: hidden;
    }
    
    .backup-form-container::before {
        content: 'BACKUP';
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
        @apply shadow-sm border-gray-300 rounded-md w-full py-1.5 px-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200;
    }

    .form-label {
        @apply block text-gray-700 text-xs font-medium mb-1;
    }

    .form-group {
        @apply bg-white p-3 rounded-lg shadow-sm border border-gray-100;
    }
</style>
@endpush

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-6">
            <div class="backup-form-container bg-gray-50 rounded-xl shadow-lg p-6 relative">
                <div class="border-b border-gray-200 pb-3 mb-4">
                    <h2 class="text-xl font-bold text-gray-800">{{ __('Créer un Backup') }}</h2>
                    <p class="text-xs text-gray-500 mt-1">Remplissez les informations requises (*)</p>
                </div>
                
                <form method="POST" action="{{ route('materials.store') }}" class="grid grid-cols-2 gap-4 relative z-10">
                    @csrf
                    
                    <!-- Colonne gauche -->
                    <div class="space-y-4">
                        <div class="form-group">
                            <label for="client_id" class="form-label">
                                <i class="fas fa-user-circle text-blue-500"></i> Client *
                            </label>
                            <select name="client_id" id="client_id" class="form-input" required>
                                <option value="">Sélectionner un client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="type" class="form-label">
                                <i class="fas fa-tag text-green-500"></i> Type *
                            </label>
                            <select name="type" id="type" class="form-input" required>
                                <option value="">Sélectionner un type</option>
                                <option value="document">Document</option>
                                <option value="image">Image</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="content_url" class="form-label">
                                <i class="fas fa-link text-purple-500"></i> URL du contenu
                            </label>
                            <input type="url" name="content_url" id="content_url" class="form-input" placeholder="https://">
                        </div>
                    </div>

                    <!-- Colonne droite -->
                    <div class="space-y-4">
                        <div class="form-group">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading text-red-500"></i> Titre *
                            </label>
                            <input type="text" name="title" id="title" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="field_name" class="form-label">
                                <i class="fas fa-font text-yellow-500"></i> Nom du champ
                            </label>
                            <input type="text" name="field_name" id="field_name" class="form-input">
                        </div>

                        <div class="form-group">
                            <label for="content" class="form-label">
                                <i class="fas fa-file-alt text-indigo-500"></i> Contenu
                            </label>
                            <textarea name="content" id="content" rows="1" class="form-input"></textarea>
                        </div>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="col-span-2 flex justify-end mt-4 pt-4 border-t border-gray-200">
                        <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="fas fa-save mr-2"></i>Sauvegarder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
