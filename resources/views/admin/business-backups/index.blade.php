@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header and New Backup Button -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Sauvegardes des données métier</h1>
        <button 
            x-data="{}"
            @click="$dispatch('open-modal', 'createBackupModal')"
            class="w-full sm:w-auto bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center justify-center gap-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle sauvegarde
        </button>
    </div>

    <!-- Success and Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Backups Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Fichier</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Taille</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($backups as $backup)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 truncate max-w-[200px]" title="{{ $backup['file_name'] }}">
                                {{ $backup['file_name'] }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $backup['type'] }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ round($backup['file_size'] / 1024, 2) }} KB
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::createFromTimestamp($backup['last_modified'])->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-wrap gap-2">
                                    <!-- Restore Button -->
                                    <button 
                                        x-data="{}"
                                        @click="$dispatch('open-modal', { id: 'restoreModal', fileName: '{{ $backup['file_name'] }}' })"
                                        class="text-blue-600 hover:text-blue-900 flex items-center gap-1"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Restaurer
                                    </button>

                                    <!-- Download Dropdown -->
                                    <div class="relative">
                                        <button class="text-green-600 hover:text-green-900 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Télécharger
                                        </button>
                                        <ul class="dropdown-menu absolute hidden bg-white rounded-lg shadow-lg mt-2 right-0">
                                            <li>
                                                <a href="{{ route('admin.business-backups.download', [$backup['file_name'], 'format' => 'json']) }}" 
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">JSON</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.business-backups.download', [$backup['file_name'], 'format' => 'csv']) }}" 
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">CSV</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.business-backups.download', [$backup['file_name'], 'format' => 'xlsx']) }}" 
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Excel</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.business-backups.download', [$backup['file_name'], 'format' => 'pdf']) }}" 
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">PDF</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Preview Button -->
                                    <button 
                                        onclick="window.location.href='{{ route('admin.business-backups.preview', $backup['file_name']) }}'"
                                        class="text-purple-600 hover:text-purple-900 flex items-center gap-1"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Prévisualiser
                                    </button>

                                    <!-- Delete Form -->
                                    <form 
                                        action="{{ route('admin.business-backups.delete', $backup['file_name']) }}" 
                                        method="POST" 
                                        class="inline"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette sauvegarde ?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="text-red-600 hover:text-red-900 flex items-center gap-1"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500 italic">
                                Aucune sauvegarde disponible
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.business-backups.modals.create')
@include('admin.business-backups.modals.restore')

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('modal', () => ({
        open: false,
        fileName: null,
        init() {
            this.$watch('open', (value) => {
                if (value) {
                    document.body.classList.add('overflow-hidden');
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            });
        },
        toggle() {
            this.open = !this.open;
        }
    }));
});
</script>
@endpush

@push('styles')
<style>
    .dropdown:hover .dropdown-menu {
        display: block;
    }
</style>
@endpush
@endsection
