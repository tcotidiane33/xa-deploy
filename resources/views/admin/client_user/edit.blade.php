@extends('layouts.admin')

@section('title', 'Modifier la Relation Client/Utilisateur')

@section('content')
<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Modifier la Relation Client/Utilisateur</h4>
            <p class="mb-30">Modifiez les assignations pour le client : {{ $client->name }}</p>
        </div>
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

    <form action="{{ route('admin.client_user.update', $client->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Responsable Paie</label>
                    <select class="custom-select2 form-control" name="responsable_paie_id" style="width: 100%;">
                        <option value="">Sélectionner un responsable</option>
                        @foreach($responsables as $responsable)
                            <option value="{{ $responsable->id }}" 
                                {{ $client->responsable_paie_id == $responsable->id ? 'selected' : '' }}
                                data-email="{{ $responsable->email }}">
                                {{ $responsable->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted" id="responsable_info"></small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Gestionnaire Principal</label>
                    <select class="custom-select2 form-control" name="gestionnaire_principal_id" style="width: 100%;">
                        <option value="">Sélectionner un gestionnaire principal</option>
                        @foreach($gestionnaires as $gestionnaire)
                            <option value="{{ $gestionnaire->id }}" 
                                {{ $client->gestionnaire_principal_id == $gestionnaire->id ? 'selected' : '' }}
                                data-email="{{ $gestionnaire->email }}"
                                data-phone="{{ $gestionnaire->phone }}">
                                {{ $gestionnaire->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted" id="gestionnaire_info"></small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Binôme (Gestionnaire Secondaire)</label>
                    <select class="custom-select2 form-control" name="binome_id" style="width: 100%;">
                        <option value="">Sélectionner un binôme</option>
                        @foreach($gestionnaires as $gestionnaire)
                            <option value="{{ $gestionnaire->id }}" 
                                {{ $client->binome_id == $gestionnaire->id ? 'selected' : '' }}
                                data-email="{{ $gestionnaire->email }}"
                                data-phone="{{ $gestionnaire->phone }}">
                                {{ $gestionnaire->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted" id="binome_info"></small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Période de paie <small class="text-muted">(optionnel)</small></label>
                    <select class="custom-select2 form-control" name="periode_paie_id" style="width: 100%;">
                        <option value="">Aucune période sélectionnée</option>
                        @foreach($periodesPaie as $periode)
                            @php
                                $isCurrentPeriod = $client->fichesClients()->latest()->first() && 
                                    $client->fichesClients()->latest()->first()->periode_paie_id == $periode->id;
                                $isExpired = $periode->fin < now();
                                $optionClass = $isExpired ? 'text-danger' : 'text-success';
                            @endphp
                            <option value="{{ $periode->id }}" 
                                {{ $isCurrentPeriod ? 'selected' : '' }}
                                class="{{ $optionClass }}"
                                {{ $isExpired ? 'disabled' : '' }}>
                                {{ $periode->nom }} 
                                ({{ $periode->debut->format('d/m/Y') }} - {{ $periode->fin->format('d/m/Y') }})
                                {{ $isExpired ? '- Expirée' : '- En cours' }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">
                        Les périodes en vert sont actives, celles en rouge sont expirées
                    </small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" name="notes" rows="3" placeholder="Ajoutez des notes concernant cette modification..."></textarea>
                </div>
            </div>
        </div>

        <div class="form-group text-right">
            <a href="{{ route('admin.client_user.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialisation de Select2
    $('.custom-select2').select2();

    // Fonction pour mettre à jour les informations de contact
    function updateContactInfo(selectElement, infoElement) {
        const selectedOption = $(selectElement).find('option:selected');
        const email = selectedOption.data('email');
        const phone = selectedOption.data('phone');
        
        if (email || phone) {
            $(infoElement).html(`Email: ${email || 'N/A'} | Tél: ${phone || 'N/A'}`);
        } else {
            $(infoElement).html('');
        }
    }

    // Mise à jour des informations lors du changement de sélection
    $('select[name="responsable_paie_id"]').on('change', function() {
        updateContactInfo(this, '#responsable_info');
    });

    $('select[name="gestionnaire_principal_id"]').on('change', function() {
        updateContactInfo(this, '#gestionnaire_info');
    });

    $('select[name="binome_id"]').on('change', function() {
        updateContactInfo(this, '#binome_info');
    });

    // Initialisation des informations au chargement
    updateContactInfo('select[name="responsable_paie_id"]', '#responsable_info');
    updateContactInfo('select[name="gestionnaire_principal_id"]', '#gestionnaire_info');
    updateContactInfo('select[name="binome_id"]', '#binome_info');
});
</script>
@endpush