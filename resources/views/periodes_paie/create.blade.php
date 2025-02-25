@extends('layouts.admin')

@section('title', 'Créer une nouvelle période de paie')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Créer une nouvelle période de paie
        </h1>
        <a href="{{ route('periodes-paie.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 ease-in-out flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
            Retour
        </a>
    </div>

    <!-- Formulaire de création -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('periodes-paie.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Référence -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700 mb-2">
                        Référence de la période
                    </label>
                    <input type="text" 
                           name="reference" 
                           id="reference" 
                           class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('reference') border-red-500 @enderror"
                           value="{{ old('reference') }}"
                           placeholder="ex: Paie Janvier 2024"
                           required>
                    @error('reference')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="debut" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de début
                    </label>
                    <input type="date" 
                           name="debut" 
                           id="debut" 
                           class="datepicker form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('debut') border-red-500 @enderror"
                           value="{{ old('debut') }}"
                           required>
                    @error('debut')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fin" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de fin
                    </label>
                    <input type="date" 
                           name="fin" 
                           id="fin" 
                           class="datepicker form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('fin') border-red-500 @enderror"
                           value="{{ old('fin') }}"
                           required>
                    @error('fin')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sélection des clients -->
            <div>
                <label for="clients" class="block text-sm font-medium text-gray-700 mb-2">
                    Sélectionner les clients
                </label>
                <select name="clients[]" 
                        id="clients" 
                        class="select2 form-multiselect w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        multiple>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" 
                                {{ in_array($client->id, old('clients', [])) ? 'selected' : '' }}>
                            {{ $client->name }} ({{ $client->gestionnairePrincipal->name ?? 'Sans gestionnaire' }})
                        </option>
                    @endforeach
                </select>
                @error('clients')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Échéances -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Échéances par défaut</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Réception variables
                        </label>
                        <input type="number" 
                               name="delais[reception_variables]" 
                               class="form-input w-full rounded-md"
                               value="{{ old('delais.reception_variables', -3) }}"
                               placeholder="Jours avant la fin">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Préparation BP
                        </label>
                        <input type="number" 
                               name="delais[preparation_bp]" 
                               class="form-input w-full rounded-md"
                               value="{{ old('delais.preparation_bp', -2) }}"
                               placeholder="Jours avant la fin">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Validation BP
                        </label>
                        <input type="number" 
                               name="delais[validation_bp]" 
                               class="form-input w-full rounded-md"
                               value="{{ old('delais.validation_bp', -1) }}"
                               placeholder="Jours avant la fin">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Envoi DSN
                        </label>
                        <input type="number" 
                               name="delais[preparation_dsn]" 
                               class="form-input w-full rounded-md"
                               value="{{ old('delais.preparation_dsn', 3) }}"
                               placeholder="Jours après la fin">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Accusés DSN
                        </label>
                        <input type="number" 
                               name="delais[accuses_dsn]" 
                               class="form-input w-full rounded-md"
                               value="{{ old('delais.accuses_dsn', 5) }}"
                               placeholder="Jours après la fin">
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-4">
                <button type="button" 
                        onclick="window.location='{{ route('periodes-paie.index') }}'"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 ease-in-out">
                    Annuler
                </button>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 ease-in-out">
                    Créer la période
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Configuration de Flatpickr
        flatpickr(".datepicker", {
            locale: "fr",
            dateFormat: "Y-m-d",
            allowInput: true,
            minDate: "today"
        });

        // Configuration de Select2
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Sélectionnez les clients",
                allowClear: true,
                language: "fr"
            });

            // Configuration des notifications
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
            };

            // Affichage des messages de notification
            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>
@endpush
