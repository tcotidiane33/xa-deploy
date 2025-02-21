@extends('layouts.admin')

@section('title', 'Modifier la Permission')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Modifier la Permission</h1>
                <p class="text-sm text-gray-600 mt-1">
                    <i class="fas fa-clock mr-1"></i>
                    Créée le {{ $permission->created_at->format('d/m/Y à H:i') }}
                </p>
            </div>
            <a href="{{ route('admin.permissions.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>

        <!-- Card principale -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

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
                               value="{{ old('name', $permission->name) }}"
                               class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 @enderror"
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
                              placeholder="Décrivez brièvement l'utilité de cette permission">{{ old('description', $permission->description) }}</textarea>
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
                        <option value="web" @if(old('guard_name', $permission->guard_name) == 'web') selected @endif>Web</option>
                        <option value="api" @if(old('guard_name', $permission->guard_name) == 'api') selected @endif>API</option>
                    </select>
                    @error('guard_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informations sur l'utilisation -->
                <div class="bg-gray-50 rounded-lg p-4 mt-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Utilisation actuelle</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Utilisateurs avec cette permission :</p>
                            <p class="font-medium">{{ $permission->users->count() }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Rôles utilisant cette permission :</p>
                            <p class="font-medium">{{ $permission->roles->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-between pt-4 border-t">
                    <button type="button" 
                            onclick="confirmDelete()"
                            class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i>
                        Supprimer
                    </button>

                    <div class="flex space-x-3">
                        <button type="reset" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-undo mr-2"></i>
                            Réinitialiser
                        </button>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-save mr-2"></i>
                            Mettre à jour
                        </button>
                    </div>
                </div>
            </form>

            <!-- Formulaire de suppression -->
            <form id="deleteForm" action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette permission ? Cette action est irréversible.')) {
        document.getElementById('deleteForm').submit();
    }
}

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

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg" 
         x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)">
        {{ session('success') }}
    </div>
@endif

@endsection
