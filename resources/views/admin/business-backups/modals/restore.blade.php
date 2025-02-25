<div x-data="modal" x-show="open" x-cloak>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50" @click="toggle"></div>
    <div class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Restaurer la sauvegarde</h3>
            <form :action="`{{ route('admin.business-backups.restore', '') }}/${fileName}`" method="POST">
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
                        @click="toggle"
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