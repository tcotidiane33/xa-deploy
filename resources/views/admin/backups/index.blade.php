@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Sauvegardes du système</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.backups.create') }}" 
               class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Nouvelle sauvegarde
            </a>
            <a href="{{ route('admin.backups.clean') }}" 
               class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
               onclick="return confirm('Êtes-vous sûr de vouloir nettoyer les anciennes sauvegardes ?')">
                Nettoyer les anciennes sauvegardes
            </a>
        </div>
    </div>

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

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fichier
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Taille
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date de création
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($backups as $backup)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $backup['file_name'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ round($backup['file_size'] / 1048576, 2) }} MB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::createFromTimestamp($backup['last_modified'])->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                            <a href="{{ route('admin.backups.download', $backup['file_name']) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                Télécharger
                            </a>
                            <a href="{{ route('admin.backups.delete', $backup['file_name']) }}" 
                               class="text-red-600 hover:text-red-900"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette sauvegarde ?')">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Aucune sauvegarde disponible
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 