<div class="bg-gray-50 min-h-screen p-6">
    @if (session()->has('message'))
        <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-700 shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-700 shadow-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit.prevent="submitForm" class="space-y-6">
        @if ($isAdminOrResponsable)
            <div class="rounded-lg bg-white p-6 shadow-sm">
                <h4 class="mb-4 text-xl font-semibold text-gray-800">
                    <i class="fas fa-calendar-alt mr-2"></i>Étape 1 : Dates de la période de paie
                </h4>
                <div class="grid gap-6 md:grid-cols-4">
                    <div class="form-group">
                        <label for="debut" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                               id="debut" 
                               wire:model="debut">
                        @error('debut')
                            <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                               id="fin" 
                               wire:model="fin">
                        @error('fin')
                            <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex items-end">
                        <button type="submit" 
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i>Créer
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if ($isGestionnaire)
            @if ($currentStep == 1)
                <div class="rounded-lg bg-white p-6 shadow-sm">
                    <h4 class="mb-4 text-xl font-semibold text-gray-800">
                        <i class="fas fa-clock mr-2"></i>Étape 1 : Sélection de la période de paie
                    </h4>
                    <div class="grid gap-6 md:grid-cols-4">
                        <div class="form-group">
                            <label for="periodePaieId" class="block text-sm font-medium text-gray-700">Période de paie</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                    id="periodePaieId" 
                                    wire:model="periodePaieId">
                                <option value="">Sélectionnez une période de paie</option>
                                @foreach ($periodesPaieNonCloturees as $periode)
                                    <option value="{{ $periode->id }}">{{ $periode->reference }}</option>
                                @endforeach
                            </select>
                            @error('periodePaieId')
                                <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="button" 
                                wire:click="nextStep"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <i class="fas fa-arrow-right mr-2"></i>Suivant
                        </button>
                    </div>
                </div>
                <div id="calendar" class="mt-6 rounded-lg bg-white p-6 shadow-sm"></div>
            @elseif ($currentStep == 2)
                <div class="bg-white rounded-lg shadow p-6" wire:poll.60000ms>
                    <!-- Filtres -->
                    <div class="mb-6 bg-white rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Filtres</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Période</label>
                                <select wire:model="selectedPeriode" class="mt-1 block w-full rounded-md border-gray-300">
                                    @foreach($periodes as $periode)
                                        <option value="{{ $periode->id }}" {{ $periode->validee ? '' : 'selected' }}>
                                            {{ $periode->reference }} {{ $periode->validee ? '(Clôturée)' : '(En cours)' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if($isAdminOrResponsable)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gestionnaire</label>
                                <select wire:model="selectedGestionnaire" class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="">Tous les gestionnaires</option>
                                    @foreach($gestionnaires as $gestionnaire)
                                        <option value="{{ $gestionnaire->id }}">{{ $gestionnaire->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Client</label>
                                <select wire:model="selectedClient" class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="">Tous les clients</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Calendrier des événements -->
                    <div class="mb-6 bg-white rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Calendrier des événements</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Liste des événements -->
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-yellow-400 mr-2"></div>
                                    <span>Réception variables : J-3 avant fin de mois</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-blue-400 mr-2"></div>
                                    <span>Préparation BP : J-2 avant fin de mois</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-green-400 mr-2"></div>
                                    <span>Validation BP client : J-1 avant fin de mois</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-purple-400 mr-2"></div>
                                    <span>Préparation et envoi DSN : 1er au 3 du mois</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-red-400 mr-2"></div>
                                    <span>Accusés DSN : 4 au 5 du mois</span>
                                </div>
                            </div>

                            <!-- Timeline visuelle -->
                            <div class="relative">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-200"></div>
                                @foreach($eventTimeline as $event)
                                    <div class="relative pl-6 pb-4">
                                        <div class="absolute left-0 w-3 h-3 rounded-full {{ $event['color'] }} -ml-1"></div>
                                        <div class="text-sm">
                                            <span class="font-medium">{{ $event['date'] }}</span>
                                            <p class="text-gray-600">{{ $event['description'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-between mb-6">
                        <div class="flex space-x-4">
                            @if($isAdminOrResponsable)
                                <button 
                                    wire:click="migrateAllClients"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    <span>Migration complète</span>
                                </button>
                                
                                <button 
                                    wire:click="migrateSelectedClients"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                    <span>Migrer clients actifs</span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Messages de notification -->
                    @if (session()->has('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-4 rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tableau -->
                    <div class="overflow-x-auto">
                        <table id="periodesPaieTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Client</th>
                                    <th scope="col" class="py-2 px-6">Gestionnaire</th>
                                    <th scope="col" class="py-2 px-3">NB Bulletins</th>
                                    <th scope="col" class="py-2 px-4">Maj fiche para</th>
                                    <th scope="col" class="px-6 py-3">Réception variables</th>
                                    <th scope="col" class="px-6 py-3">Préparation BP</th>
                                    <th scope="col" class="px-6 py-3">Validation BP client</th>
                                    <th scope="col" class="px-6 py-3">Préparation et envoie DSN</th>
                                    <th scope="col" class="px-6 py-3">Accusés DSN</th>
                                    <th scope="col" class="px-6 py-3">NOTES</th>
                                    <th scope="col" class="px-6 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fichesClients as $fiche)
                                    <tr class="bg-white justify-center border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="text-sm font-extrabold tracking-tight leading-none text-gray-900 dark:text-white p-0 m-0 text-center">
                                            {{ $fiche->client->name }}
                                        </td>
                                        <td class="text-sm font-extrabold tracking-tight leading-none text-gray-900 dark:text-white p-0 m-0 text-center">
                                            {{ $fiche->client->gestionnairePrincipal->name ?? 'N/A' }}
                                        </td>
                                        <td class="text-xl font-extrabold tracking-tight leading-none text-gray-900 md:text-xl lg:text-1xl dark:text-white p-0 m-0 text-center {{ $fiche->client->nb_bulletins ? 'bg-blue-500 text-white' : '' }}">
                                            {{ $fiche->client->nb_bulletins ?? 'N/A' }}
                                        </td>
                                        <td class="text-xl font-extrabold tracking-tight leading-none text-gray-900 md:text-xl lg:text-1xl dark:text-white p-0 m-0 text-center {{ !$fiche->client->maj_fiche_para || $fiche->client->maj_fiche_para->year < now()->year ? 'bg-red-500 text-white' : ($fiche->client->maj_fiche_para ? 'bg-purple-500 text-white' : 'bg-yellow-500 text-black') }}">
                                            {{ $fiche->client->maj_fiche_para ? \Carbon\Carbon::parse($fiche->client->maj_fiche_para)->format('d/m') : 'N/A' }}
                                        </td>
                                        
                                        <!-- Champs de dates avec conditions de couleur -->
                                        @foreach(['reception_variables', 'preparation_bp', 'validation_bp_client', 'preparation_envoie_dsn', 'accuses_dsn'] as $field)
                                            <td class="text-xl font-extrabold tracking-tight leading-none text-gray-900 md:text-xl lg:text-1xl dark:text-white p-0 m-0 text-center 
                                                {{ !$fiche->$field || $fiche->$field->year < now()->year ? 'bg-red-500 text-white' : 
                                                   ($fiche->$field ? 'bg-purple-500 text-white' : 'bg-yellow-500 text-black') }}">
                                                {{ $fiche->$field ? $fiche->$field->format('d/m') : 'N/A' }}
                                            </td>
                                        @endforeach

                                        <!-- Notes avec affichage amélioré -->
                                        <td class="text-xl font-extrabold tracking-tight leading-none text-gray-900 md:text-xl lg:text-1xl dark:text-white p-3 m-0 text-center">
                                            @if($fiche->notes)
                                                @php
                                                    $notes = explode("\n", $fiche->notes);
                                                    $recentNotes = array_slice($notes, -3);
                                                @endphp
                                                <div id="notes-{{ $fiche->id }}" class="inline-flex items-center justify-center w-full">
                                                    @foreach($recentNotes as $note)
                                                        <div class="flex items-start m-0 justify-start">
                                                            <div class="bg-pink-100 text-pink-800 text-xs font-medium px-4 py-2 rounded-lg shadow-md dark:bg-pink-900 dark:text-pink-300">
                                                                {{ $note }}
                                                            </div>
                                                        </div>
                                                        <div class="inline-flex items-center justify-center w-full">
                                                            <hr class="w-8 h-1 my-8 bg-gray-200 border-0 rounded dark:bg-gray-700">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td class="flex text-center items-center justify-center">
                                            <button wire:click="editFicheClient({{ $fiche->id }})"
                                                    class="bg-blue-500 hover:bg-cyan-700 text-white font-bold m-1 p-1 rounded mx-auto">
                                                Mettre à jour
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Historique style terminal -->
                    <div class="mt-8 bg-black rounded-lg p-4 font-mono text-sm">
                        <div class="text-green-400 mb-2">== Historique des actions ==</div>
                        <div class="h-64 overflow-y-auto">
                            @foreach($historique as $log)
                                <div class="flex space-x-2 text-xs">
                                    <span class="text-gray-500">[{{ $log['timestamp'] }}]</span>
                                    <span class="text-yellow-400">{{ $log['user'] }}</span>
                                    <span class="text-blue-400">{{ $log['action'] }}</span>
                                    <span class="text-white">{{ $log['details'] }}</span>
                                    <span class="text-purple-400">({{ $log['periode'] }})</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </form>

    <!-- Modal d'édition -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="editModal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Modifier la fiche</h3>
                    <div class="mt-2 px-7 py-3">
                        <!-- Formulaire d'édition -->
                        <form wire:submit.prevent="updateFicheClient">
                            @foreach(['reception_variables', 'preparation_bp', 'validation_bp_client', 'preparation_envoie_dsn', 'accuses_dsn'] as $field)
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ ucfirst(str_replace('_', ' ', $field)) }}</label>
                                    <input type="date" 
                                           wire:model="editingFiche.{{ $field }}"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           {{ !$this->canEditField($field) ? 'disabled' : '' }}>
                                </div>
                            @endforeach

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Notes</label>
                                <textarea wire:model="editingFiche.notes"
                                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                          rows="3"></textarea>
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <button type="button" wire:click="closeEditModal"
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Annuler
                                </button>
                                <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Échéances par Client -->
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-4">Échéances par Client</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($fichesClients as $fiche)
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $fiche->client->name }}</h3>
                        <span class="px-2 py-1 text-sm rounded-full {{ $this->getStatusColor($fiche) }}">
                            {{ $this->getStatus($fiche) }}
                        </span>
                    </div>

                    <!-- Timeline des échéances -->
                    <div class="relative pl-8 space-y-4">
                        <!-- Réception variables -->
                        <div class="relative">
                            <div class="absolute left-0 w-3 h-3 {{ $fiche->reception_variables ? 'bg-green-500' : 'bg-gray-300' }} rounded-full -ml-6"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Réception variables</span>
                                <span class="text-sm {{ $fiche->reception_variables ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $fiche->reception_variables ? $fiche->reception_variables->format('d/m/Y') : 'En attente' }}
                                </span>
                            </div>
                        </div>

                        <!-- Préparation BP -->
                        <div class="relative">
                            <div class="absolute left-0 w-3 h-3 {{ $fiche->preparation_bp ? 'bg-green-500' : ($fiche->reception_variables ? 'bg-yellow-500' : 'bg-gray-300') }} rounded-full -ml-6"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Préparation BP</span>
                                <span class="text-sm {{ $this->getDateStatusColor($fiche->preparation_bp) }}">
                                    {{ $fiche->preparation_bp ? $fiche->preparation_bp->format('d/m/Y') : ($fiche->reception_variables ? 'À faire' : 'En attente') }}
                                </span>
                            </div>
                        </div>

                        <!-- Validation BP client -->
                        <div class="relative">
                            <div class="absolute left-0 w-3 h-3 {{ $fiche->validation_bp_client ? 'bg-green-500' : ($fiche->preparation_bp ? 'bg-yellow-500' : 'bg-gray-300') }} rounded-full -ml-6"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Validation BP client</span>
                                <span class="text-sm {{ $this->getDateStatusColor($fiche->validation_bp_client) }}">
                                    {{ $fiche->validation_bp_client ? $fiche->validation_bp_client->format('d/m/Y') : ($fiche->preparation_bp ? 'À faire' : 'En attente') }}
                                </span>
                            </div>
                        </div>

                        <!-- Préparation et envoi DSN -->
                        <div class="relative">
                            <div class="absolute left-0 w-3 h-3 {{ $fiche->preparation_envoie_dsn ? 'bg-green-500' : ($fiche->validation_bp_client ? 'bg-yellow-500' : 'bg-gray-300') }} rounded-full -ml-6"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Préparation et envoi DSN</span>
                                <span class="text-sm {{ $this->getDateStatusColor($fiche->preparation_envoie_dsn) }}">
                                    {{ $fiche->preparation_envoie_dsn ? $fiche->preparation_envoie_dsn->format('d/m/Y') : ($fiche->validation_bp_client ? 'À faire' : 'En attente') }}
                                </span>
                            </div>
                        </div>

                        <!-- Accusés DSN -->
                        <div class="relative">
                            <div class="absolute left-0 w-3 h-3 {{ $fiche->accuses_dsn ? 'bg-green-500' : ($fiche->preparation_envoie_dsn ? 'bg-yellow-500' : 'bg-gray-300') }} rounded-full -ml-6"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Accusés DSN</span>
                                <span class="text-sm {{ $this->getDateStatusColor($fiche->accuses_dsn) }}">
                                    {{ $fiche->accuses_dsn ? $fiche->accuses_dsn->format('d/m/Y') : ($fiche->preparation_envoie_dsn ? 'À faire' : 'En attente') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Prochaine échéance -->
                    @if($nextDeadline = $this->getNextDeadline($fiche))
                        <div class="mt-4 p-2 bg-blue-50 rounded-md">
                            <p class="text-sm text-blue-800">
                                <span class="font-medium">Prochaine échéance:</span>
                                {{ $nextDeadline['label'] }} - 
                                @if($nextDeadline['date'])
                                    {{ $nextDeadline['date']->format('d/m/Y') }}
                                @else
                                    À planifier
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        padding-top: 50px;
    }

    .modal-content {
        background-color: #fefefe;
        margin: auto;
        width: 90%;
        max-width: 600px;
        position: relative;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-100px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Style pour l'historique */
    .font-mono {
        font-family: 'Courier New', Courier, monospace;
    }
    
    /* Animation de clignotement pour le curseur */
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }
    
    .cursor::after {
        content: '_';
        animation: blink 1s infinite;
    }
</style>
@endpush

@push('scripts')
<script>
    function showAllNotes(event, ficheClientId, notes) {
        event.preventDefault();
        
        var notesDiv = document.getElementById('notes-' + ficheClientId);
        notesDiv.innerHTML = '';

        notes.forEach(function(note) {
            var noteContainer = document.createElement('div');
            noteContainer.className = 'rounded-md bg-pink-100 p-2 text-pink-800 shadow-sm mb-2';
            noteContainer.textContent = note;
            notesDiv.appendChild(noteContainer);
        });

        event.target.remove();
    }

    function openEditPopup(ficheClientId) {
        fetch(`/fiches-clients/${ficheClientId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editForm').action = `{{ url('fiches-clients') }}/${ficheClientId}`;
                document.getElementById('fiche_client_id').value = ficheClientId;
                document.getElementById('reception_variables').value = data.reception_variables;
                document.getElementById('preparation_bp').value = data.preparation_bp;
                document.getElementById('validation_bp_client').value = data.validation_bp_client;
                document.getElementById('preparation_envoie_dsn').value = data.preparation_envoie_dsn;
                document.getElementById('accuses_dsn').value = data.accuses_dsn;
                document.getElementById('notes').value = data.notes;
                
                document.getElementById('editPopup').style.display = 'block';
            });
    }

    function closeEditPopup() {
        document.getElementById('editPopup').style.display = 'none';
    }

    // Fermer le popup en cliquant en dehors
    window.onclick = function(event) {
        var modal = document.getElementById('editPopup');
        if (event.target == modal) {
            closeEditPopup();
        }
    }

    Livewire.on('startAutoRefresh', (event) => {
        setInterval(() => {
            Livewire.emit('refreshComponent');
        }, event.interval);
    });
</script>
@endpush
