<div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form wire:submit.prevent="updateFicheClient">
        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
                <div class="mt-2">
                    <label for="reception_variables" class="block text-sm font-medium text-gray-700">Réception variables</label>
                    <input type="date" wire:model="reception_variables" id="reception_variables" class="form-control">
                </div>
                <div class="mt-2">
                    <label for="preparation_bp" class="block text-sm font-medium text-gray-700">Préparation BP</label>
                    <input type="date" wire:model="preparation_bp" id="preparation_bp" class="form-control">
                </div>
                <div class="mt-2">
                    <label for="validation_bp_client" class="block text-sm font-medium text-gray-700">Validation BP client</label>
                    <input type="date" wire:model="validation_bp_client" id="validation_bp_client" class="form-control">
                </div>
            </div>
            <div>
                <div class="mt-2">
                    <label for="preparation_envoie_dsn" class="block text-sm font-medium text-gray-700">Préparation et envoie DSN</label>
                    <input type="date" wire:model="preparation_envoie_dsn" id="preparation_envoie_dsn" class="form-control">
                </div>
                <div class="mt-2">
                    <label for="accuses_dsn" class="block text-sm font-medium text-gray-700">Accusés DSN</label>
                    <input type="date" wire:model="accuses_dsn" id="accuses_dsn" class="form-control">
                </div>
                <div class="mt-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea wire:model="notes" id="notes" class="form-control"></textarea>
                </div>
            </div>
        </div>
        <div class="mt-4 flex justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Enregistrer
            </button>
            <button type="button" onclick="closePopup()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Annuler
            </button>
        </div>
    </form>
</div>