@extends('layouts.admin')

@section('title', 'Créer une Permission')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Créer une Permission</h1>
                <p class="text-sm text-gray-600 mt-1">Ajoutez une nouvelle permission au système</p>
            </div>
            <a href="{{ route('admin.permissions.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>

        <!-- Card principale -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('admin.permissions.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nom de la permission -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nom de la permission <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 @enderror"
                               placeholder="ex: edit articles"
                               required>
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        Le nom doit être unique et en minuscules, utilisez des tirets pour séparer les mots
                    </p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="3" 
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-300 @enderror"
                              placeholder="Décrivez brièvement l'utilité de cette permission">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Guard Name -->
                <div>
                    <label for="guard_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Guard Name
                    </label>
                    <select name="guard_name" 
                            id="guard_name" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="web" @if(old('guard_name', 'web') == 'web') selected @endif>Web</option>
                        <option value="api" @if(old('guard_name') == 'api') selected @endif>API</option>
                    </select>
                    @error('guard_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <button type="reset" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-undo mr-2"></i>
                        Réinitialiser
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>
                        Créer la permission
                    </button>
                </div>
            </form>
        </div>

        <!-- Carte d'aide -->
        <div class="bg-blue-50 rounded-lg shadow-sm p-6 mt-6">
            <h2 class="text-lg font-medium text-blue-800 mb-4">
                <i class="fas fa-info-circle mr-2"></i>
                Conseils pour la création de permissions
            </h2>
            <ul class="list-disc list-inside text-sm text-blue-700 space-y-2">
                <li>Utilisez des noms descriptifs et concis</li>
                <li>Suivez une convention de nommage cohérente (ex: 'edit-posts', 'delete-users')</li>
                <li>Évitez les caractères spéciaux et les espaces</li>
                <li>Pensez à la granularité des permissions</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-formatage du nom de la permission
    const nameInput = document.getElementById('name');
    nameInput.addEventListener('input', function(e) {
        // Convertit en minuscules et remplace les espaces par des tirets
        this.value = this.value.toLowerCase().replace(/\s+/g, '-');
    });
});
</script>
@endpush

@endsection
