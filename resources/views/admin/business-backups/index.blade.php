@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Sauvegardes des données métier</h1>
        
        <button 
            onclick="document.getElementById('createBackupModal').classList.remove('hidden')"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
            Nouvelle sauvegarde
        </button>
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
                        Type de données
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
                        <td class="px-6 py-4">
                            {{ $backup['type'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ round($backup['file_size'] / 1024, 2) }} KB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::createFromTimestamp($backup['last_modified'])->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                            <button 
                                onclick="showRestoreModal('{{ $backup['file_name'] }}')"
                                class="text-blue-600 hover:text-blue-900"
                            >
                                Restaurer
                            </button>
                            <a 
                                href="{{ route('admin.business-backups.download', $backup['file_name']) }}"
                                class="text-green-600 hover:text-green-900"
                            >
                                Télécharger
                            </a>
                            <form 
                                action="{{ route('admin.business-backups.delete', $backup['file_name']) }}" 
                                method="POST" 
                                class="inline"
                            >
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette sauvegarde ?')"
                                >
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Aucune sauvegarde disponible
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de création de sauvegarde -->
<div id="createBackupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Nouvelle sauvegarde</h3>
            <form action="{{ route('admin.business-backups.create') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="backup_clients" id="backup_clients" class="mr-2" checked>
                        <label for="backup_clients">Clients</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="backup_fiches" id="backup_fiches" class="mr-2" checked>
                        <label for="backup_fiches">Fiches clients</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="backup_periodes" id="backup_periodes" class="mr-2" checked>
                        <label for="backup_periodes">Périodes de paie</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="backup_traitements" id="backup_traitements" class="mr-2" checked>
                        <label for="backup_traitements">Traitements de paie</label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-2">
                    <button 
                        type="button"
                        onclick="document.getElementById('createBackupModal').classList.add('hidden')"
                        class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    >
                        Créer la sauvegarde
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de restauration -->
<div id="restoreModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Restaurer la sauvegarde</h3>
            <form id="restoreForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="restore_clients" id="restore_clients" class="mr-2" checked>
                        <label for="restore_clients">Clients</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="restore_fiches" id="restore_fiches" class="mr-2" checked>
                        <label for="restore_fiches">Fiches clients</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="restore_periodes" id="restore_periodes" class="mr-2" checked>
                        <label for="restore_periodes">Périodes de paie</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="restore_traitements" id="restore_traitements" class="mr-2" checked>
                        <label for="restore_traitements">Traitements de paie</label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-2">
                    <button 
                        type="button"
                        onclick="document.getElementById('restoreModal').classList.add('hidden')"
                        class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                    >
                        Restaurer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showRestoreModal(fileName) {
    const modal = document.getElementById('restoreModal');
    const form = document.getElementById('restoreForm');
    form.action = `{{ route('admin.business-backups.restore', '') }}/${fileName}`;
    modal.classList.remove('hidden');
}
</script>
@endpush
@endsection 