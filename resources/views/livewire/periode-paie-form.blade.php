<div>
    @if (session()->has('message'))
        <div class="row"><br><br></div>
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit.prevent="submitForm">
        {{-- // if user hav role Admin --}}
        @if ($isAdminOrResponsable)
            <h4>Étape 1 : Dates de la période de paie</h4>
            <div class="form-step flex">
                <div class="grid gap-6 mb-6 md:grid-cols-4">
                    <div class="form-group">
                        <label for="debut">Date de début</label>
                        <input type="date" class="form-control" id="debut" wire:model="debut">
                        @error('debut')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="fin">Date de fin</label>
                        <input type="date" class="form-control" id="fin" wire:model="fin">
                        @error('fin')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit"
                        class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Créer</button>
                </div>
            </div>
        @endif
        {{-- if user have rôle Gestionnaire --}}
        @if ($isGestionnaire)
            @if ($currentStep == 1)
                <div class="form-step">
                    <h4>Étape 1 : Sélection de la période de paie</h4>
                    <div class="grid gap-6 mb-6 md:grid-cols-4">
                    <div class="form-group">
                        <label for="periodePaieId">Période de paie</label>
                        <select class="form-control" id="periodePaieId" wire:model="periodePaieId">
                            <option value="">Sélectionnez une période de paie</option>
                            @foreach ($periodesPaieNonCloturees as $periode)
                                <option value="{{ $periode->id }}">{{ $periode->reference }}</option>
                            @endforeach
                        </select>
                        @error('periodePaieId')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <button type="button" class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" wire:click="nextStep">Suivant</button>
            </div>
            <div id="calendar"></div> <!-- Ajout du calendrier ici -->
            @elseif ($currentStep == 2)
            <div class="form-step">
                <h4>Étape 2 : Informations des clients</h4>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Client</th>
                                <th scope="col" class="px-6 py-3">Gestionnaire</th>
                                <th scope="col" class="px-6 py-3">NB Bulletins</th>
                                <th scope="col" class="px-6 py-3">Maj fiche para</th>
                                <th scope="col" class="px-6 py-3 modifiable">Réception variables</th>
                                <th scope="col" class="px-6 py-3 modifiable">Préparation BP</th>
                                <th scope="col" class="px-6 py-3 modifiable">Validation BP client</th>
                                <th scope="col" class="px-6 py-3 modifiable">Préparation et envoie DSN</th>
                                <th scope="col" class="px-6 py-3 modifiable">Accusés DSN</th>
                                {{-- <th scope="col" class="px-6 py-3 modifiable">TELEDEC URSSAF</th> --}}
                                <th scope="col" class="px-6 py-3 modifiable">NOTES</th>
                                <th scope="col" class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                                @php
                                    $ficheClient = $client->fichesClients()->where('periode_paie_id', $periodePaieId)->first();
                                @endphp
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $client->name }}</td>
                                    <td class="px-6 py-4">{{ $client->gestionnairePrincipal->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $client->nb_bulletins ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $client->maj_fiche_para ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 modifiable">{{ $ficheClient->reception_variables ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 modifiable">{{ $ficheClient->preparation_bp ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 modifiable">{{ $ficheClient->validation_bp_client ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 modifiable">{{ $ficheClient->preparation_envoie_dsn ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 modifiable">{{ $ficheClient->accuses_dsn ?? 'N/A' }}</td>
                                    {{-- <td class="px-6 py-4 modifiable">{{ $ficheClient->teledec_urssaf ?? 'N/A' }}</td> --}}
                                    <td class="px-6 py-4 modifiable">{{ $ficheClient->notes ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        @if ($ficheClient)
                                            <button class="btn btn-primary" onclick="openEditPopup({{ $ficheClient->id }})">Modifier</button>
                                        @else
                                            <button class="btn btn-secondary" onclick="openCreatePopup({{ $client->id }})">Créer</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        @endif
    </form>

    <!-- Popup d'édition -->
    <div id="editPopup" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditPopup()">&times;</span>
            <h2>Modifier Fiche Client</h2>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="fiche_client_id" id="fiche_client_id">
                <div class="form-group">
                    <label for="reception_variables">Réception variables</label>
                    <input type="date" name="reception_variables" id="reception_variables" class="form-control">
                </div>
                <div class="form-group">
                    <label for="preparation_bp">Préparation BP</label>
                    <input type="date" name="preparation_bp" id="preparation_bp" class="form-control">
                </div>
                <div class="form-group">
                    <label for="validation_bp_client">Validation BP client</label>
                    <input type="date" name="validation_bp_client" id="validation_bp_client" class="form-control">
                </div>
                <div class="form-group">
                    <label for="preparation_envoie_dsn">Préparation et envoie DSN</label>
                    <input type="date" name="preparation_envoie_dsn" id="preparation_envoie_dsn"
                        class="form-control">
                </div>
                <div class="form-group">
                    <label for="accuses_dsn">Accusés DSN</label>
                    <input type="date" name="accuses_dsn" id="accuses_dsn" class="form-control">
                </div>
                <div class="form-group">
                    <label for="teledec_urssaf">TELEDEC URSSAF</label>
                    <input type="date" name="teledec_urssaf" id="teledec_urssaf" class="form-control">
                </div>
                <div class="form-group">
                    <label for="notes">NOTES</label>
                    <textarea name="notes" id="notes" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>

    <script>
        function openEditPopup(ficheClientId) {
            // Remplir le formulaire avec les données de la fiche client
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
                    // document.getElementById('teledec_urssaf').value = data.teledec_urssaf;
                    document.getElementById('notes').value = data.notes;

                    // Afficher le popup
                    document.getElementById('editPopup').style.display = 'block';
                });
        }

        function closeEditPopup() {
            document.getElementById('editPopup').style.display = 'none';
        }
        
        function openCreatePopup(clientId) {
            document.getElementById('createForm').action = `{{ url('fiches-clients') }}`;
            document.getElementById('client_id').value = clientId;

            // Afficher le popup
            document.getElementById('createPopup').style.display = 'block';
        }

        function closeCreatePopup() {
            document.getElementById('createPopup').style.display = 'none';
        }
    </script>
</div>

@push('scripts')
    <!-- CalendarJS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                events: [
                    @foreach ($periodesPaie as $periode)
                        {
                            title: '{{ $periode->reference }}',
                            start: '{{ $periode->debut }}',
                            end: '{{ $periode->fin }}',
                            color: '{{ $periode->validee ? '#ff0000' : '#00ff00' }}' // Rouge pour les périodes clôturées, vert pour les autres
                        },
                    @endforeach
                ]
            });
        });
    </script>
@endpush
