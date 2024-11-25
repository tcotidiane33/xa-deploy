@extends('layouts.admin')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.3.1/styles/default.min.css">
    <style>
        .form-container {
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .tab-content {
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
        }

        .form-actions {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .error-feedback {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .step-buttons {
            display: flex;
            justify-content: space-between;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="breadcrumb">
            <h1>Créer un nouveau client</h1>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="main-page">
            <div class="form-container">
                <!-- Onglets -->
                <ul class="nav nav-tabs" id="clientTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="societe-tab" data-bs-toggle="tab" href="#societe">Société</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" id="contacts-tab" data-bs-toggle="tab" href="#contacts">Contacts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" id="interne-tab" data-bs-toggle="tab" href="#interne">Informations
                            Internes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" id="supplementaires-tab" data-bs-toggle="tab"
                            href="#supplementaires">Informations Supplémentaires</a>
                    </li>
                </ul>

                <form id="clientForm">
                    @csrf
                    <input type="hidden" name="client_id" value="{{ $client->id ?? '' }}">

                    <div class="tab-content mt-3">
                        <!-- Onglet Société -->
                        <div class="tab-pane fade show active" id="societe">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="name">Nom Société *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" required>
                                    @error('name')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="type_societe">Type</label>
                                    <input type="text" class="form-control @error('type_societe') is-invalid @enderror" name="type_societe">
                                    @error('type_societe')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="ville">Ville</label>
                                    <input type="text" class="form-control @error('ville') is-invalid @enderror" name="ville">
                                    @error('ville')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Nom du dirigeant</label>
                                    <input type="text" class="form-control @error('dirigeant_nom') is-invalid @enderror" name="dirigeant_nom">
                                    @error('dirigeant_nom')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label>Téléphone</label>
                                    <input type="tel" class="form-control @error('dirigeant_telephone') is-invalid @enderror" name="dirigeant_telephone">
                                    @error('dirigeant_telephone')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label>Email</label>
                                    <input type="email" class="form-control @error('dirigeant_email') is-invalid @enderror" name="dirigeant_email">
                                    @error('dirigeant_email')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group flex gap-6">
                                <div class="col-md-2">
                                    <label for="date_estimative_envoi_variables">Date estimative d'envoi des variables</label>
                                    <input type="date" name="date_estimative_envoi_variables" id="date_estimative_envoi_variables" class="form-control @error('date_estimative_envoi_variables') is-invalid @enderror" value="{{ old('date_estimative_envoi_variables') }}">
                                    @error('date_estimative_envoi_variables')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="nb_bulletins">Nombre de bulletins</label>
                                    <input type="number" name="nb_bulletins" id="nb_bulletins" class="form-control @error('nb_bulletins') is-invalid @enderror" value="{{ old('nb_bulletins') }}">
                                    @error('nb_bulletins')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Onglet Contacts -->
                        <div class="tab-pane fade" id="contacts">
                            <h4>Contact Paie</h4>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Nom</label>
                                    <input type="text" class="form-control @error('contact_paie_nom') is-invalid @enderror" name="contact_paie_nom">
                                    @error('contact_paie_nom')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label>Prénom</label>
                                    <input type="text" class="form-control @error('contact_paie_prenom') is-invalid @enderror" name="contact_paie_prenom">
                                    @error('contact_paie_prenom')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label>Téléphone</label>
                                    <input type="tel" class="form-control @error('contact_paie_telephone') is-invalid @enderror" name="contact_paie_telephone">
                                    @error('contact_paie_telephone')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label>Email</label>
                                    <input type="email" class="form-control @error('contact_paie_email') is-invalid @enderror" name="contact_paie_email">
                                    @error('contact_paie_email')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <h4>Contact Comptabilité</h4>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Nom</label>
                                    <input type="text" class="form-control @error('contact_compta_nom') is-invalid @enderror" name="contact_compta_nom">
                                    @error('contact_compta_nom')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label>Prénom</label>
                                    <input type="text" class="form-control @error('contact_compta_prenom') is-invalid @enderror" name="contact_compta_prenom">
                                    @error('contact_compta_prenom')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label>Téléphone</label>
                                    <input type="tel" class="form-control @error('contact_compta_telephone') is-invalid @enderror" name="contact_compta_telephone">
                                    @error('contact_compta_telephone')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label>Email</label>
                                    <input type="email" class="form-control @error('contact_compta_email') is-invalid @enderror" name="contact_compta_email">
                                    @error('contact_compta_email')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Onglet Informations Internes -->
                        <div class="tab-pane fade" id="interne">
                            <h4>Responsable paie</h4>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Responsable *</label>
                                    <select name="responsable_paie_id" class="form-control @error('responsable_paie_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un responsable</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('responsable_paie_id')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label>Téléphone LD</label>
                                    <input type="tel" class="form-control @error('responsable_telephone_ld') is-invalid @enderror" name="responsable_telephone_ld">
                                    @error('responsable_telephone_ld')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <h4>Gestionnaire et Binôme</h4>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Gestionnaire principal *</label>
                                    <select name="gestionnaire_principal_id" class="form-control @error('gestionnaire_principal_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un gestionnaire</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('gestionnaire_principal_id')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="gestionnaires_secondaires">
                                            Binôme
                                        </label>
                                        <select name="binome_id" class="form-control @error('binome_id') is-invalid @enderror" required>
                                            <option value="">Sélectionner un binôme</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ isset($client) && $client->binome_id == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('binome_id')
                                            <div class="error-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="gestionnaire_telephone_ld">Téléphone LD Gestionnaire</label>
                                    <input type="tel" class="form-control @error('gestionnaire_telephone_ld') is-invalid @enderror" name="gestionnaire_telephone_ld">
                                    @error('gestionnaire_telephone_ld')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="binome_telephone_ld">Téléphone LD Binôme</label>
                                    <input type="tel" class="form-control @error('binome_telephone_ld') is-invalid @enderror" name="binome_telephone_ld">
                                    @error('binome_telephone_ld')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <h4>Convention Collective</h4>
                            <div class="form-group">
                                <label for="convention_collective_id">Convention Collective</label>
                                <select name="convention_collective_id" id="convention_collective_id" class="form-control @error('convention_collective_id') is-invalid @enderror">
                                    <option value="">Sélectionner une convention collective</option>
                                    @foreach ($conventionCollectives as $conventionCollective)
                                        <option value="{{ $conventionCollective->id }}">{{ $conventionCollective->name }}</option>
                                    @endforeach
                                </select>
                                @error('convention_collective_id')
                                    <div class="error-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="maj_fiche_para">Date de mise à jour fiche para</label>
                                <input type="date" name="maj_fiche_para" id="maj_fiche_para" class="form-control @error('maj_fiche_para') is-invalid @enderror" value="{{ old('maj_fiche_para') }}">
                                @error('maj_fiche_para')
                                    <div class="error-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Onglet Informations Supplémentaires -->
                        <div class="tab-pane fade" id="supplementaires">
                            <h4>Informations Supplémentaires</h4>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Saisie des variables *</label>
                                    <input type="checkbox" name="saisie_variables" value="1" class="@error('saisie_variables') is-invalid @enderror">
                                    @error('saisie_variables')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label>Client formé à la saisie en ligne</label>
                                    <input type="checkbox" name="client_forme_saisie" value="1" class="@error('client_forme_saisie') is-invalid @enderror">
                                    <input type="date" name="date_formation_saisie" class="form-control @error('date_formation_saisie') is-invalid @enderror">
                                    @error('date_formation_saisie')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2 form-group">
                                    <label for="date_debut_prestation">Date de début de prestation</label>
                                    <input type="date" name="date_debut_prestation" id="date_debut_prestation" class="form-control @error('date_debut_prestation') is-invalid @enderror" value="{{ old('date_debut_prestation') }}">
                                    @error('date_debut_prestation')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="date_fin_prestation">Date de fin de prestation</label>
                                    <input type="date" name="date_fin_prestation" id="date_fin_prestation" class="form-control @error('date_fin_prestation') is-invalid @enderror" value="{{ old('date_fin_prestation') }}">
                                    @error('date_fin_prestation')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="date_signature_contrat">Date de signature du contrat</label>
                                    <input type="date" name="date_signature_contrat" id="date_signature_contrat" class="form-control @error('date_signature_contrat') is-invalid @enderror" value="{{ old('date_signature_contrat') }}">
                                    @error('date_signature_contrat')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="date_rappel_mail">Date de rappel mail</label>
                                    <input type="date" name="date_rappel_mail" class="form-control @error('date_rappel_mail') is-invalid @enderror">
                                    @error('date_rappel_mail')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <h4>Taux & Adhésions</h4>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Taux AT *</label>
                                    <input type="text" name="taux_at" class="form-control @error('taux_at') is-invalid @enderror" required>
                                    @error('taux_at')
                                        <div class="error-feedback