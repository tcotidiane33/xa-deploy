@extends('layouts.admin')

@section('title', 'Modifier le Rôle')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Modifier le Rôle : {{ $role->name }}</h1>
                <p class="text-sm text-gray-600 mt-1">
                    <i class="fas fa-clock mr-1"></i>
                    Créé le {{ $role->created_at->format('d/m/Y à H:i') }}
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
            <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

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
                               value="{{ old('name', $role->name) }}"
                               class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 @enderror"
                               {{ $role->name === 'Admin' ? 'readonly' : '' }}
                               required>
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                              placeholder="Décrivez le rôle et ses responsabilités">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Permissions -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Permissions associées
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($permissions->chunk(ceil($permissions->count() / 3)) as $chunk)
                            <div class="space-y-2">
                                @foreach($chunk as $permission)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               id="permission_{{ $permission->id }}" 
                                               value="{{ $permission->name }}"
                                               {{ $role->permissions->contains($permission) ? 'checked' : '' }}
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

                <!-- Statistiques du rôle -->
                <div class="bg-gray-50 rounded-lg p-4 mt-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Statistiques d'utilisation</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white p-3 rounded-md shadow-sm">
                            <p class="text-sm text-gray-600">Utilisateurs avec ce rôle</p>
                            <p class="text-2xl font-semibold text-indigo-600">{{ $role->users->count() }}</p>
                        </div>
                        <div class="bg-white p-3 rounded-md shadow-sm">
                            <p class="text-sm text-gray-600">Permissions attribuées</p>
                            <p class="text-2xl font-semibold text-green-600">{{ $role->permissions->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-between pt-6 border-t">
                    @if($role->name !== 'Admin')
                        <button type="button" 
                                onclick="confirmDelete()"
                                class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer
                        </button>
                    @else
                        <div></div>
                    @endif

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

            @if($role->name !== 'Admin')
                <form id="deleteForm" action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce rôle ? Cette action est irréversible et supprimera toutes les associations avec les utilisateurs.')) {
        document.getElementById('deleteForm').submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la sélection des permissions
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const form = document.querySelector('form');

    form.addEventListener('reset', () => {
        setTimeout(() => {
            checkboxes.forEach(checkbox => {
                checkbox.checked = checkbox.hasAttribute('checked');
            });
        }, 0);
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