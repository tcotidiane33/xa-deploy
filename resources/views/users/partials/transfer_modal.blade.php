<div id="transferModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Transférer le client</h3>
            <button onclick="closeTransferModal()" class="text-gray-400 hover:text-gray-500">
                <span class="sr-only">Fermer</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="transferForm" action="{{ route('users.transfer_client', $user) }}" method="POST">
            @csrf
            <input type="hidden" name="client_id" id="transfer_client_id">
            <input type="hidden" name="current_role" id="transfer_role">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Transférer à</label>
                    <select name="to_user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($availableUsers as $availableUser)
                            <option value="{{ $availableUser->id }}">{{ $availableUser->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Période de paie</label>
                    <select name="periode_paie_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($periodesPaie as $periode)
                            <option value="{{ $periode->id }}">{{ $periode->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Notes (optionnel)</label>
                    <textarea name="notes" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Ajoutez des notes concernant ce transfert..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeTransferModal()" 
                            class="bg-white px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="bg-indigo-600 px-4 py-2 rounded-md text-sm font-medium text-white hover:bg-indigo-700">
                        Confirmer le transfert
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openTransferModal(clientId, role) {
    document.getElementById('transfer_client_id').value = clientId;
    document.getElementById('transfer_role').value = role;
    document.getElementById('transferModal').classList.remove('hidden');
}

function closeTransferModal() {
    document.getElementById('transferModal').classList.add('hidden');
}

// Fermer le modal en cliquant en dehors
document.getElementById('transferModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTransferModal();
    }
});
</script>
@endpush 