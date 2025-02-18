@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Créer une Fiche Client</h1>
            <a href="{{ route('fiches-clients.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <form action="{{ route('fiches-clients.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Section Client et Période -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="form-group mb-4">
                        <label for="client_id" class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-user mr-1"></i>Client
                        </label>
                        <select name="client_id" id="client_id" 
                                class="form-control w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1">
                            <option value="">Sélectionnez un client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="periode_paie_id" class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-calendar mr-1"></i>Période de Paie
                        </label>
                        <select name="periode_paie_id" id="periode_paie_id" 
                                class="form-control w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1">
                            <option value="">Sélectionnez une période</option>
                            @foreach ($periodesPaie as $periode)
                                <option value="{{ $periode->id }}">{{ $periode->reference }}</option>
                            @endforeach
                        </select>
                        @error('periode_paie_id')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Section Dates -->
                <div class="col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="reception_variables" class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-inbox mr-1"></i>Réception variables
                        </label>
                        <input type="date" name="reception_variables" id="reception_variables" 
                               class="form-control w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1">
                        @error('reception_variables')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="preparation_bp" class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-tasks mr-1"></i>Préparation BP
                        </label>
                        <input type="date" name="preparation_bp" id="preparation_bp" 
                               class="form-control w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1">
                        @error('preparation_bp')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="validation_bp_client" class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-check-circle mr-1"></i>Validation BP client
                        </label>
                        <input type="date" name="validation_bp_client" id="validation_bp_client" 
                               class="form-control w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1">
                        @error('validation_bp_client')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="preparation_envoie_dsn" class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-paper-plane mr-1"></i>Préparation et envoi DSN
                        </label>
                        <input type="date" name="preparation_envoie_dsn" id="preparation_envoie_dsn" 
                               class="form-control w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1">
                        @error('preparation_envoie_dsn')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="accuses_dsn" class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-receipt mr-1"></i>Accusés DSN
                        </label>
                        <input type="date" name="accuses_dsn" id="accuses_dsn" 
                               class="form-control w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1">
                        @error('accuses_dsn')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Section Notes -->
                <div class="col-span-3">
                    <div class="form-group">
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-sticky-note mr-1"></i>Notes
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="form-control w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1"
                                  placeholder="Ajoutez vos notes ici..."></textarea>
                        @error('notes')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="reset" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-undo mr-1"></i>Réinitialiser
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-1"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation des select avec choix de recherche
        const selects = document.querySelectorAll('select');
        selects.forEach(select => {
            new Choices(select, {
                searchEnabled: true,
                itemSelectText: '',
                noResultsText: 'Aucun résultat trouvé',
                noChoicesText: 'Aucun choix disponible',
            });
        });

        // Validation en temps réel des dates
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.addEventListener('change', function() {
                validateDates();
            });
        });

        function validateDates() {
            const dates = Array.from(dateInputs).map(input => input.value ? new Date(input.value) : null);
            
            for (let i = 0; i < dates.length - 1; i++) {
                if (dates[i] && dates[i+1] && dates[i] > dates[i+1]) {
                    dateInputs[i+1].setCustomValidity('La date doit être postérieure à la précédente');
                } else {
                    dateInputs[i+1].setCustomValidity('');
                }
            }
        }
    });
</script>
@endpush
@endsection
