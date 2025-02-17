<div class="client-form-container bg-gray-50 rounded-xl shadow-lg p-6 relative">
    <style>
        .client-form-container::before {
            content: 'CLIENT';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 8rem;
            font-weight: bold;
            color: rgba(229, 231, 235, 0.2);
            white-space: nowrap;
            pointer-events: none;
            z-index: 0;
        }

        .form-input {
            @apply shadow-sm border-gray-300 rounded-lg w-full py-1.5 px-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200;
        }

        .form-select {
            @apply shadow-sm border-gray-300 rounded-lg w-full py-1.5 px-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200;
        }

        .form-checkbox {
            @apply rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200;
        }

        .form-label {
            @apply block text-gray-700 text-xs font-medium mb-1;
        }

        .form-group {
            @apply bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-4;
        }

        .step-header {
            @apply text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200;
        }

        .step-container {
            @apply relative z-10;
        }

        .btn-nav {
            @apply px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center relative overflow-hidden;
        }

        .btn-prev {
            @apply bg-gray-500 hover:bg-gray-600 text-white focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 rounded-lg;
        }

        .btn-next {
            @apply bg-blue-500 hover:bg-blue-600 text-white focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-lg;
        }

        .btn-submit {
            @apply bg-green-500 hover:bg-green-600 text-white focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg;
        }

        .btn-nav {
            transform: translateY(0);
        }

        .btn-nav:hover {
            @apply font-bold;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-nav:active {
            transform: translateY(2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-nav::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            background-image: radial-gradient(circle, rgba(255, 255, 255, 0.3) 10%, transparent 10.01%);
            background-repeat: no-repeat;
            background-position: 50%;
            transform: scale(10, 10);
            opacity: 0;
            transition: transform 0.3s, opacity 0.5s;
        }

        .btn-nav:active::after {
            transform: scale(0, 0);
            opacity: 0.3;
            transition: 1s;
            border: 3px solidrgb(0, 106, 255);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
        }

        .btn-prev:hover {
            @apply text-white;
            background: linear-gradient(to right, #4B5563, #6B7280);
            padding: 10px;
            transition: all 0.3s ease;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
            border: 3px solidrgb(0, 106, 255);
        }

        .btn-next:hover {
            @apply text-white;
            background: linear-gradient(to right,rgb(235, 37, 235),rgba(246, 59, 237, 0.81));
            padding: 10px;
            transition: all 0.3s ease;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
            border: 3px solidrgb(247, 0, 255);
        }

        .btn-submit:hover {
            @apply text-white;
            background: linear-gradient(to right, #059669, #10B981);
            padding: 10px;
            transition: all 0.3s ease;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
            border: 3px solidrgb(109, 138, 6);
        }

        .error-message {
            @apply text-red-500 text-xs mt-1 rounded bg-red-50 px-2 py-1;
        }

        .success-message {
            @apply text-green-500 text-xs mt-1 rounded bg-green-50 px-2 py-1;
        }
    </style>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-r-lg">
            {{ session('message') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit.prevent="submitForm" class="step-container">
        @if ($currentStep == 1)
            <div class="form-step">
                <h4 class="step-header mb-4">
                    <i class="fas fa-building text-blue-500 mr-2"></i>Société
                </h4>
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-signature text-blue-500 mr-1"></i>Nom de la société *
                            </label>
                            <input type="text" class="form-input rounded-lg" id="name" wire:model="name">
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type_societe" class="form-label">
                                <i class="fas fa-building text-green-500 mr-1"></i>Type de société *
                            </label>
                            <input type="text" class="form-input rounded-lg" id="type_societe" wire:model="type_societe">
                            @error('type_societe')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="ville" class="form-label">
                                <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>Ville *
                            </label>
                            <input type="text" class="form-input rounded-lg" id="ville" wire:model="ville">
                            @error('ville')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="form-group">
                            <label for="dirigeant_nom" class="form-label">
                                <i class="fas fa-user-tie text-purple-500 mr-1"></i>Nom du dirigeant *
                            </label>
                            <input type="text" class="form-input rounded-lg" id="dirigeant_nom" wire:model="dirigeant_nom">
                            @error('dirigeant_nom')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="dirigeant_telephone" class="form-label">
                                <i class="fas fa-phone text-indigo-500 mr-1"></i>Téléphone du dirigeant
                            </label>
                            <input type="text" class="form-input rounded-lg" id="dirigeant_telephone" wire:model="dirigeant_telephone">
                            @error('dirigeant_telephone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="dirigeant_email" class="form-label">
                                <i class="fas fa-envelope text-yellow-500 mr-1"></i>Email du dirigeant *
                            </label>
                            <input type="email" class="form-input rounded-lg" id="dirigeant_email" wire:model="dirigeant_email" 
                                placeholder="email@exemple.com">
                            @error('dirigeant_email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6 pt-4 border-t border-gray-200">
                    <button type="button" wire:click="nextStep" 
                        class="btn-nav btn-next">
                        <span>Étape suivante</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        @endif

        @if ($currentStep == 2)
            <div class="form-step">
                <h4 class="step-header mb-4">
                    <i class="fas fa-address-card text-blue-500 mr-2"></i>Contacts
                </h4>

                <!-- Contact Paie -->
                <div class="mb-6">
                    <h5 class="text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-money-check text-green-500 mr-1"></i>Contact Paie
                    </h5>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="form-group">
                                <label for="contact_paie_nom" class="form-label">
                                    <i class="fas fa-user text-blue-500 mr-1"></i>Nom *
                                </label>
                                <input type="text" class="form-input rounded-lg" id="contact_paie_nom"
                                    wire:model="contact_paie_nom">
                                @error('contact_paie_nom')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="contact_paie_prenom" class="form-label">
                                    <i class="fas fa-user text-green-500 mr-1"></i>Prénom *
                                </label>
                                <input type="text" class="form-input rounded-lg" id="contact_paie_prenom"
                                    wire:model="contact_paie_prenom">
                                @error('contact_paie_prenom')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="form-group">
                                <label for="contact_paie_telephone" class="form-label">
                                    <i class="fas fa-phone text-indigo-500 mr-1"></i>Téléphone
                                </label>
                                <input type="text" class="form-input rounded-lg" id="contact_paie_telephone"
                                    wire:model="contact_paie_telephone">
                                @error('contact_paie_telephone')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="contact_paie_email" class="form-label">
                                    <i class="fas fa-envelope text-yellow-500 mr-1"></i>Email *
                                </label>
                                <input type="email" class="form-input rounded-lg" id="contact_paie_email"
                                    wire:model="contact_paie_email" 
                                    placeholder="email@exemple.com">
                                @error('contact_paie_email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Comptabilité -->
                <div class="mb-6">
                    <h5 class="text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-calculator text-purple-500 mr-1"></i>Contact Comptabilité
                    </h5>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="form-group">
                                <label for="contact_compta_nom" class="form-label">
                                    <i class="fas fa-user text-blue-500 mr-1"></i>Nom *
                                </label>
                                <input type="text" class="form-input rounded-lg" id="contact_compta_nom"
                                    wire:model="contact_compta_nom">
                                @error('contact_compta_nom')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="contact_compta_prenom" class="form-label">
                                    <i class="fas fa-user text-green-500 mr-1"></i>Prénom *
                                </label>
                                <input type="text" class="form-input rounded-lg" id="contact_compta_prenom"
                                    wire:model="contact_compta_prenom">
                                @error('contact_compta_prenom')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="form-group">
                                <label for="contact_compta_telephone" class="form-label">
                                    <i class="fas fa-phone text-indigo-500 mr-1"></i>Téléphone
                                </label>
                                <input type="text" class="form-input rounded-lg" id="contact_compta_telephone"
                                    wire:model="contact_compta_telephone">
                                @error('contact_compta_telephone')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="contact_compta_email" class="form-label">
                                    <i class="fas fa-envelope text-yellow-500 mr-1"></i>Email *
                                </label>
                                <input type="email" class="form-input rounded-lg" id="contact_compta_email"
                                    wire:model="contact_compta_email"
                                    placeholder="email@exemple.com">
                                @error('contact_compta_email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons de navigation -->
                <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                    <button type="button" wire:click="previousStep" 
                        class="btn-nav btn-prev">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Étape précédente</span>
                    </button>
                    <button type="button" wire:click="nextStep" 
                        class="btn-nav btn-next">
                        <span>Étape suivante</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        @endif

        @if ($currentStep == 3)
            <div class="form-step">
                <h4>Informations Internes</h4>
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div class="left">
                        <div class="form-group">
                            <label for="responsable_paie_id">Responsable paie</label>
                            <select class="form-control" id="responsable_paie_id" wire:model="responsable_paie_id">
                                <option value="">Sélectionner un responsable</option>
                                @foreach ($responsables as $responsable)
                                    <option value="{{ $responsable->id }}">{{ $responsable->name }}</option>
                                @endforeach
                            </select>
                            @error('responsable_paie_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="responsable_telephone_ld">Téléphone ligne directe responsable</label>
                            <input type="text" class="form-control" id="responsable_telephone_ld"
                                wire:model="responsable_telephone_ld">
                            @error('responsable_telephone_ld')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="gestionnaire_principal_id">Gestionnaire principal</label>
                            <select class="form-control" id="gestionnaire_principal_id"
                                wire:model="gestionnaire_principal_id">
                                <option value="">Sélectionner un gestionnaire</option>
                                @foreach ($gestionnaires as $gestionnaire)
                                    <option value="{{ $gestionnaire->id }}">{{ $gestionnaire->name }}</option>
                                @endforeach
                            </select>
                            @error('gestionnaire_principal_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="right">
                        <div class="form-group">
                            <label for="binome_id">Binôme</label>
                            <select class="form-control" id="binome_id" wire:model="binome_id">
                                <option value="">Sélectionner un binôme</option>
                                @foreach ($gestionnaires as $gestionnaire)
                                    <option value="{{ $gestionnaire->id }}">{{ $gestionnaire->name }}</option>
                                @endforeach
                            </select>
                            @error('binome_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="convention_collective_id">Convention collective</label>
                            <select class="form-control" id="convention_collective_id"
                                wire:model="convention_collective_id">
                                <option value="">Sélectionner une convention collective</option>
                                @foreach ($conventions as $convention)
                                    <option value="{{ $convention->id }}">{{ $convention->name }}</option>
                                @endforeach
                            </select>
                            @error('convention_collective_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="maj_fiche_para">Mise à jour fiche paramétrage</label>
                            <input type="date" class="form-control" id="maj_fiche_para"
                                wire:model="maj_fiche_para">
                            @error('maj_fiche_para')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- Boutons de navigation -->
                <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                    <button type="button" wire:click="previousStep" 
                        class="btn-nav btn-prev">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Étape précédente</span>
                    </button>
                    <button type="button" wire:click="nextStep" 
                        class="btn-nav btn-next">
                        <span>Étape suivante</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        @endif

        @if ($currentStep == 4)
            <div class="form-step">
                <h4>Informations Supplémentaires</h4>
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div class="left">
                        <div class="form-group">
                            <label for="saisie_variables">Saisie des variables</label>
                            <input type="checkbox" id="saisie_variables" wire:model="saisie_variables">
                            @error('saisie_variables')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- @if ($saisie_variables) --}}
                            <div class="form-group">
                                <label for="date_saisie_variables">Date de saisie des variables</label>
                                <input type="date" class="form-control" id="date_saisie_variables"
                                    wire:model="date_saisie_variables">
                                @error('date_saisie_variables')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        {{-- @endif --}}
                        <div class="form-group">
                            <label for="client_forme_saisie">Client formé à la saisie</label>
                            <input type="checkbox" id="client_forme_saisie" wire:model="client_forme_saisie">
                            <input type="date" class="form-control" id="date_formation_saisie"
                                wire:model="date_formation_saisie">
                            @error('client_forme_saisie')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            @error('date_formation_saisie')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="date_debut_prestation">Date de début de prestation</label>
                            <input type="date" class="form-control" id="date_debut_prestation"
                                wire:model="date_debut_prestation">
                            @error('date_debut_prestation')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="right">
                        <div class="form-group">
                            <label for="date_fin_prestation">Date de fin de prestation</label>
                            <input type="date" class="form-control" id="date_fin_prestation"
                                wire:model="date_fin_prestation">
                            @error('date_fin_prestation')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="date_signature_contrat">Date de signature du contrat</label>
                            <input type="date" class="form-control" id="date_signature_contrat"
                                wire:model="date_signature_contrat">
                            @error('date_signature_contrat')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="date_rappel_mail">Date de rappel par mail</label>
                            <input type="date" class="form-control" id="date_rappel_mail"
                                wire:model="date_rappel_mail">
                            @error('date_rappel_mail')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div class="left">
                        <div class="form-group">
                            <label for="taux_at">Taux AT</label>
                            <input type="text" class="form-control" id="taux_at" wire:model="taux_at">
                            @error('taux_at')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="adhesion_mydrh">Adhésion MyDRH</label>
                            <input type="checkbox" id="adhesion_mydrh" wire:model="adhesion_mydrh">
                            <input type="date" class="form-control" id="date_adhesion_mydrh"
                                wire:model="date_adhesion_mydrh">
                            @error('adhesion_mydrh')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            @error('date_adhesion_mydrh')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="right">
                        <div class="form-group">
                            <label for="is_cabinet">Est un cabinet</label>
                            <input type="checkbox" id="is_cabinet" wire:model="is_cabinet">
                            @error('is_cabinet')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="portfolio_cabinet_id">Portefeuille cabinet</label>
                            <select class="form-control" id="portfolio_cabinet_id" wire:model="portfolio_cabinet_id">
                                <option value="">Sélectionner un portefeuille cabinet</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                            @error('portfolio_cabinet_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                    <button type="button" wire:click="previousStep" 
                        class="btn-nav btn-prev">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Étape précédente</span>
                    </button>
                    <button type="submit" class="btn btn-success">Soumettre</button>
                </div>

            </div>
        @endif
    </form>
</div>
