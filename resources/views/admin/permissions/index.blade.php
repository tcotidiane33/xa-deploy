@extends('layouts.admin')

@section('title', 'Gestion des Permissions')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Permissions</h2>
                <p class="text-gray-600 mt-1">Gérez les permissions du système</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" 
                           id="searchPermission" 
                           placeholder="Rechercher une permission..." 
                           class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <a href="{{ route('admin.permissions.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-150 ease-in-out">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvelle Permission
                </a>
            </div>
        </div>

        <!-- Tableau des permissions -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1 cursor-pointer hover:text-gray-700">
                                <span>Nom</span>
                                <i class="fas fa-sort"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <span>Créée le</span>
                                <i class="fas fa-sort"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utilisée par
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($permissions as $permission)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-key text-indigo-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $permission->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($permission->description ?? 'Aucune description', 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $permission->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $permission->users_count ?? 0 }} utilisateurs
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('admin.permissions.edit', $permission->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete('{{ $permission->id }}')" 
                                        class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $permission->id }}" 
                                      action="{{ route('admin.permissions.destroy', $permission->id) }}" 
                                      method="POST" 
                                      class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $permissions->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(permissionId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette permission ?')) {
        document.getElementById('delete-form-' + permissionId).submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchPermission');
    let timeoutId;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            // Implémentez ici la logique de recherche
            console.log('Recherche:', this.value);
        }, 300);
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

@if(session('error'))
<div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg" 
     x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 3000)">
    {{ session('error') }}
</div>
@endif

@endsection
