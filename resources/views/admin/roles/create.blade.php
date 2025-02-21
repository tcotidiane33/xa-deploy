@extends('layouts.admin')

@section('title', 'Créer un Nouveau Rôle')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Créer un Nouveau Rôle</h1>
                <p class="text-sm text-gray-600 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Définissez un nouveau rôle et ses permissions associées
                </p>
            </div>
            <a href="{{ route('admin.roles.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>

        <!-- Card principale -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nom du rôle -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nom du rôle <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user-shield text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 @enderror"
                               placeholder="ex: Gestionnaire"
                               required>
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        Le nom doit être unique et descriptif des responsabilités du rôle
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
                              placeholder="Décrivez les responsabilités et le niveau d'accès de ce rôle">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Permissions -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Permissions associées
                        <span class="text-xs text-gray-500 ml-1">(sélectionnez les permissions à attribuer)</span>
                    </label>
                    
                    <!-- Barre de recherche des permissions -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" 
                                   id="searchPermissions" 
                                   placeholder="Rechercher une permission..." 
                                   class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="flex space-x-4 mb-4">
                        <button type="button" 
                                onclick="selectAllPermissions()" 
                                class="text-sm text-indigo-600 hover:text-indigo-800">
                            <i class="fas fa-check-square mr-1"></i>
                            Tout sélectionner
                        </button>
                        <button type="button" 
                                onclick="deselectAllPermissions()" 
                                class="text-sm text-gray-600 hover:text-gray-800">
                            <i class="fas fa-square mr-1"></i>
                            Tout désélectionner
                        </button>
                    </div>

                    <!-- Grille des permissions -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-h-96 overflow-y-auto permission-grid">
                        @foreach($permissions->chunk(ceil($permissions->count() / 3)) as $chunk)
                            <div class="space-y-2">
                                @foreach($chunk as $permission)
                                    <div class="flex items-center permission-item">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               id="permission_{{ $permission->id }}" 
                                               value="{{ $permission->name }}"
                                               {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="permission_{{ $permission->id }}" 
                                               class="ml-2 block text-sm text-gray-700 cursor-pointer hover:text-gray-900">
                                            {{ $permission->name }}
                                            @if($permission->description)
                                                <span class="text-xs text-gray-500 block">{{ $permission->description }}</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    @error('permissions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                    <button type="reset" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-undo mr-2"></i>
                        Réinitialiser
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>
                        Créer le rôle
                    </button>
                </div>
            </form>
        </div>

        <!-- Carte d'aide -->
        <div class="bg-blue-50 rounded-lg shadow-sm p-6 mt-6">
            <h2 class="text-lg font-medium text-blue-800 mb-4">
                <i class="fas fa-info-circle mr-2"></i>
                Conseils pour la création de rôles
            </h2>
            <ul class="list-disc list-inside text-sm text-blue-700 space-y-2">
                <li>Choisissez un nom clair et descriptif pour le rôle</li>
                <li>Attribuez uniquement les permissions nécessaires (principe du moindre privilège)</li>
                <li>Groupez les permissions logiquement selon les responsabilités</li>
                <li>Évitez de créer des rôles avec des permissions redondantes</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Recherche de permissions
    const searchInput = document.getElementById('searchPermissions');
    const permissionItems = document.querySelectorAll('.permission-item');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        permissionItems.forEach(item => {
            const permissionName = item.querySelector('label').textContent.toLowerCase();
            item.style.display = permissionName.includes(searchTerm) ? '' : 'none';
        });
    });
});

function selectAllPermissions() {
    document.querySelectorAll('input[type="checkbox"][name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllPermissions() {
    document.querySelectorAll('input[type="checkbox"][name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}
</script>
@endpush

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg" 
         x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)">
        {{ session('success') }}
    </div>
@endif

@endsection