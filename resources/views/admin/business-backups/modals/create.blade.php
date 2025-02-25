<div x-data="modal" x-show="open" x-cloak>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50" @click="toggle"></div>
    <div class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
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
                        @click="toggle"
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