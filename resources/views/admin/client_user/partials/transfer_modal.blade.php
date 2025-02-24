<style>
.select2-container {
    width: 100% !important;
    min-width: 300px;
}

.modal-lg {
    max-width: 1000px;  /* Augmentation de la largeur du modal */
}

.select2-results__option {
    padding: 8px 12px;  /* Plus d'espace pour les options */
}

/* Style personnalisé pour les selects dans le modal */
#transferModal .form-control {
    min-width: 300px;
    height: 45px;  /* Hauteur augmentée pour une meilleure visibilité */
}

/* Ajustement de la largeur des colonnes */
#transferModal .col-md-6 {
    padding: 0 20px;  /* Plus d'espace entre les colonnes */
}

/* Style pour les options des périodes */
.periode-option-active {
    color: #28a745;
    font-weight: 500;
}

.periode-option-expired {
    color: #dc3545;
    font-style: italic;
}
</style>

<div class="modal fade" id="transferModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transfert en masse des clients</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="transferForm" action="{{ route('admin.client_user.transfer') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        Sélectionnez les nouveaux gestionnaires ou responsables pour les clients sélectionnés.
                        Les champs non remplis conserveront leurs valeurs actuelles.
                    </div>

                    <!-- Section Transfert de Responsable -->
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Transfert de Responsable</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ancien Responsable</label>
                                        <select class="custom-select2 form-control" id="old_responsable_id">
                                            <option  value="">Tous les responsables</option>
                                            @foreach($responsables as $responsable)
                                                <option class="w-400 " value="{{ $responsable->id }}">{{ $responsable->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nouveau Responsable</label>
                                        <select class="form-control w-100" name="new_responsable_id">
                                            <option value="">Sélectionner un responsable</option>
                                            @foreach($responsables as $responsable)
                                                <option value="{{ $responsable->id }}">{{ $responsable->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Transfert de Gestionnaire -->
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">Transfert de Gestionnaire</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ancien Gestionnaire Principal</label>
                                        <select class="form-control w-100" id="old_gestionnaire_id">
                                            <option value="">Tous les gestionnaires</option>
                                            @foreach($gestionnaires as $gestionnaire)
                                                <option value="{{ $gestionnaire->id }}">{{ $gestionnaire->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nouveau Gestionnaire Principal</label>
                                        <select class="form-control w-100" name="new_gestionnaire_id">
                                            <option value="">Sélectionner un gestionnaire</option>
                                            @foreach($gestionnaires as $gestionnaire)
                                                <option value="{{ $gestionnaire->id }}">{{ $gestionnaire->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ancien Binôme</label>
                                        <select class="form-control w-100" id="old_binome_id">
                                            <option value="">Tous les binômes</option>
                                            @foreach($gestionnaires as $gestionnaire)
                                                <option value="{{ $gestionnaire->id }}">{{ $gestionnaire->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nouveau Binôme</label>
                                        <select class="form-control w-100" name="new_binome_id">
                                            <option value="">Sélectionner un binôme</option>
                                            @foreach($gestionnaires as $gestionnaire)
                                                <option value="{{ $gestionnaire->id }}">{{ $gestionnaire->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Période de Paie -->
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">Période de Paie</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <select class="form-control w-100" name="periode_paie_id">
                                    <option value="">Sélectionner une période de paie</option>
                                    @foreach($periodesPaie as $periode)
                                        @php
                                            $isExpired = $periode->fin < now();
                                            $optionClass = $isExpired ? 'text-danger' : 'text-success';
                                        @endphp
                                        <option value="{{ $periode->id }}" 
                                            class="{{ $optionClass }}"
                                            {{ $isExpired ? 'disabled' : '' }}>
                                            {{ $periode->nom }} 
                                            ({{ $periode->debut->format('d/m/Y') }} - {{ $periode->fin->format('d/m/Y') }})
                                            {{ $isExpired ? '- Expirée' : '- En cours' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="client_ids" id="selectedClients">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Transférer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Configuration améliorée de Select2
    $('.custom-select2').select2({
        dropdownParent: $('#transferModal'),
        width: '100%',
        containerCssClass: 'select2-container--large',
        dropdownCssClass: 'select2-dropdown--large',
        placeholder: 'Sélectionner...',
        allowClear: true,
        language: {
            noResults: function() {
                return "Aucun résultat trouvé";
            }
        }
    });

    // Mise à jour des clients sélectionnés lors de l'ouverture du modal
    $('#transferModal').on('show.bs.modal', function () {
        var selectedIds = [];
        $('.client-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });
        $('#selectedClients').val(JSON.stringify(selectedIds));
    });

    // Filtrage des clients en fonction des sélections
    $('#old_responsable_id, #old_gestionnaire_id, #old_binome_id').on('change', function() {
        filterClients();
    });

    function filterClients() {
        var oldResponsable = $('#old_responsable_id').val();
        var oldGestionnaire = $('#old_gestionnaire_id').val();
        var oldBinome = $('#old_binome_id').val();

        $('.client-checkbox').each(function() {
            var $row = $(this).closest('tr');
            var showRow = true;

            if (oldResponsable && $row.data('responsable-id') != oldResponsable) showRow = false;
            if (oldGestionnaire && $row.data('gestionnaire-id') != oldGestionnaire) showRow = false;
            if (oldBinome && $row.data('binome-id') != oldBinome) showRow = false;

            $row.toggle(showRow);
        });
    }
});
</script>
@endpush 