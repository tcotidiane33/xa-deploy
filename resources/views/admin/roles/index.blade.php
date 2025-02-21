@extends('layouts.admin')

@section('title', 'Gestion des Rôles')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Gestion des Rôles</h1>
                <p class="text-sm text-gray-600 mt-1">Gérez les rôles et leurs permissions associées</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" 
                           id="searchRole" 
                           placeholder="Rechercher un rôle..." 
                           class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <a href="{{ route('admin.roles.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-150 ease-in-out">
                    <i class="fas fa-plus mr-2"></i>
                    Nouveau Rôle
                </a>
            </div>
        </div>

        <!-- Tableau des rôles -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1 cursor-pointer hover:text-gray-700">
                                <span>Nom du Rôle</span>
                                <i class="fas fa-sort"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Permissions
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utilisateurs
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($roles as $role)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user-shield text-indigo-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $role->guard_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach ($role->permissions as $permission)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                                @if($role->permissions->isEmpty())
                                    <span class="text-sm text-gray-500 italic">Aucune permission</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $role->users->count() }} utilisateur(s)
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('admin.roles.edit', $role) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($role->name !== 'Admin')
                                    <button onclick="confirmDelete('{{ $role->id }}')" 
                                            class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $role->id }}" 
                                          action="{{ route('admin.roles.destroy', $role) }}" 
                                          method="POST" 
                                          class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Section des statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 bg-gray-50 p-6 rounded-lg">
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-indigo-100 rounded-full">
                        <i class="fas fa-users-cog text-indigo-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total des Rôles</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $roles->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-key text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total des Permissions</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $permissions->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-user-lock text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Rôles Actifs</h3>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $roles->filter(function($role) { return $role->users->count() > 0; })->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(roleId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce rôle ? Cette action est irréversible.')) {
        document.getElementById('delete-form-' + roleId).submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchRole');
    const rows = document.querySelectorAll('tbody tr');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        rows.forEach(row => {
            const roleName = row.querySelector('td:first-child').textContent.toLowerCase();
            row.style.display = roleName.includes(searchTerm) ? '' : 'none';
        });
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

@push('styles')
    <style>
        .mb-3 {
            margin-bottom: 1rem;
        }

        .label {
            display: inline-block;
            padding: .2em .6em .3em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
        }

        .label-info {
            background-color: #5bc0de;
        }
    </style>
@endpush
