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
    <form wire:submit.prevent="save">
        <div class="grid gap-6 mb-6 md:grid-cols-3">
            <div>
                <div class="form-group">
                    <label for="client_id">Client</label>
                    <select wire:model="client_id" id="client_id" class="form-control">
                        <option value="">Sélectionnez un client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="gestionnaire_id">Gestionnaire</label>
                    <select wire:model="gestionnaire_id" id="gestionnaire_id" class="form-control">
                        <option value="">Sélectionnez un gestionnaire</option>
                        @foreach ($gestionnaires as $gestionnaire)
                            <option value="{{ $gestionnaire->id }}">{{ $gestionnaire->name }}</option>
                        @endforeach
                    </select>
                    @error('gestionnaire_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="periode_paie_id">Période de Paie</label>
                    <select wire:model="periode_paie_id" id="periode_paie_id" class="form-control">
                        <option value="">Sélectionnez une période de paie</option>
                        @foreach ($periodesPaie as $periode)
                            <option value="{{ $periode->id }}">{{ $periode->reference }}</option>
                        @endforeach
                    </select>
                    @error('periode_paie_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div>

                <div class="form-group">
                    <label for="reception_variables_file">Fichier Réception Variables</label>
                    <input type="file" wire:model="reception_variables_file" id="reception_variables_file"
                        class="form-control">
                    @error('reception_variables_file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="preparation_bp_file">Fichier Préparation BP</label>
                    <input type="file" wire:model="preparation_bp_file" id="preparation_bp_file"
                        class="form-control">
                    @error('preparation_bp_file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="validation_bp_client_file">Fichier Validation BP Client</label>
                    <input type="file" wire:model="validation_bp_client_file" id="validation_bp_client_file"
                        class="form-control">
                    @error('validation_bp_client_file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="preparation_envoi_dsn_file">Fichier Préparation Envoi DSN</label>
                    <input type="file" wire:model="preparation_envoi_dsn_file" id="preparation_envoi_dsn_file"
                        class="form-control">
                    @error('preparation_envoi_dsn_file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label for="accuses_dsn_file">Fichier Accusés DSN</label>
                    <input type="file" wire:model="accuses_dsn_file" id="accuses_dsn_file" class="form-control">
                    @error('accuses_dsn_file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nb_bulletins_file">Fichier Nombre de Bulletins</label>
                    <input type="file" wire:model="nb_bulletins_file" id="nb_bulletins_file" class="form-control">
                    @error('nb_bulletins_file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="maj_fiche_para_file">Fichier Mise à Jour Fiche Paramètres</label>
                    <input type="file" wire:model="maj_fiche_para_file" id="maj_fiche_para_file"
                        class="form-control">
                    @error('maj_fiche_para_file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>


        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>

    <hr>
    <h1
        class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
        L'option Drag and Drop</h1>
    <p class="mb-6 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">glissez déposer
        les fichers ici, c'est plus simple</p>
    <form wire:submit.prevent="save">
        <div class="form-group">
            <label for="client_id">Client</label>
            <select wire:model="client_id" id="client_id" class="form-control">
                <option value="">Sélectionnez un client</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
            @error('client_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="gestionnaire_id">Gestionnaire</label>
            <select wire:model="gestionnaire_id" id="gestionnaire_id" class="form-control">
                <option value="">Sélectionnez un gestionnaire</option>
                @foreach ($gestionnaires as $gestionnaire)
                    <option value="{{ $gestionnaire->id }}">{{ $gestionnaire->name }}</option>
                @endforeach
            </select>
            @error('gestionnaire_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="periode_paie_id">Période de Paie</label>
            <select wire:model="periode_paie_id" id="periode_paie_id" class="form-control">
                <option value="">Sélectionnez une période de paie</option>
                @foreach ($periodesPaie as $periode)
                    <option value="{{ $periode->id }}">{{ $periode->reference }}</option>
                @endforeach
            </select>
            @error('periode_paie_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="reception_variables_file">Fichier Réception Variables</label>
            <div class="drop-zone" id="drop-zone-reception">
                <input type="file" wire:model="reception_variables_file" id="reception_variables_file"
                    class="hidden">
                <p>Glissez et déposez un fichier ici ou cliquez pour sélectionner un fichier</p>
            </div>
            @error('reception_variables_file')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="preparation_bp_file">Fichier Préparation BP</label>
            <div class="drop-zone" id="drop-zone-preparation">
                <input type="file" wire:model="preparation_bp_file" id="preparation_bp_file" class="hidden">
                <p>Glissez et déposez un fichier ici ou cliquez pour sélectionner un fichier</p>
            </div>
            @error('preparation_bp_file')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="validation_bp_client_file">Fichier Validation BP Client</label>
            <div class="drop-zone" id="drop-zone-validation">
                <input type="file" wire:model="validation_bp_client_file" id="validation_bp_client_file"
                    class="hidden">
                <p>Glissez et déposez un fichier ici ou cliquez pour sélectionner un fichier</p>
            </div>
            @error('validation_bp_client_file')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="preparation_envoi_dsn_file">Fichier Préparation Envoi DSN</label>
            <div class="drop-zone" id="drop-zone-preparation-dsn">
                <input type="file" wire:model="preparation_envoi_dsn_file" id="preparation_envoi_dsn_file"
                    class="hidden">
                <p>Glissez et déposez un fichier ici ou cliquez pour sélectionner un fichier</p>
            </div>
            @error('preparation_envoi_dsn_file')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="accuses_dsn_file">Fichier Accusés DSN</label>
            <div class="drop-zone" id="drop-zone-accuses">
                <input type="file" wire:model="accuses_dsn_file" id="accuses_dsn_file" class="hidden">
                <p>Glissez et déposez un fichier ici ou cliquez pour sélectionner un fichier</p>
            </div>
            @error('accuses_dsn_file')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="nb_bulletins_file">Fichier Nombre de Bulletins</label>
            <div class="drop-zone" id="drop-zone-accuses">

                <input type="file" wire:model="nb_bulletins_file" id="nb_bulletins_file" class="hidden">
                <p>Glissez et déposez un fichier ici ou cliquez pour sélectionner un fichier</p>
            </div>
            @error('nb_bulletins_file')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="maj_fiche_para_file">Fichier Mise à Jour Fiche Paramètres</label>
            <div class="drop-zone" id="drop-zone-accuses">

                <input type="file" wire:model="maj_fiche_para_file" id="maj_fiche_para_file" class="hidden">
                <p>Glissez et déposez un fichier ici ou cliquez pour sélectionner un fichier</p>
            </div>
            @error('maj_fiche_para_file')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>

    <script>
        document.querySelectorAll('.drop-zone').forEach(zone => {
            zone.addEventListener('click', () => {
                zone.querySelector('input[type="file"]').click();
            });

            zone.addEventListener('dragover', (e) => {
                e.preventDefault();
                zone.classList.add('dragover');
            });

            zone.addEventListener('dragleave', () => {
                zone.classList.remove('dragover');
            });

            zone.addEventListener('drop', (e) => {
                e.preventDefault();
                zone.classList.remove('dragover');
                const input = zone.querySelector('input[type="file"]');
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            });
        });
    </script>

    <style>
        .drop-zone {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
        }

        .drop-zone.dragover {
            border-color: #000;
        }
    </style>
</div>
