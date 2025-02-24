@extends('layouts.admin')

@section('title', 'Gestion des Relations Clients/Utilisateurs')

@section('content')
<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Gestion des Relations Clients/Utilisateurs</h4>
            <p class="mb-30">Gérez les assignations entre clients et utilisateurs</p>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#transferModal">
                <i class="bi bi-arrow-left-right"></i> Transfert en masse
            </button>
        </div>
    </div>

    <!-- Filtres -->
    <div class="pb-20">
        <form id="filterForm" class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Filtrer par responsable</label>
                    <select class="form-control" name="responsable_id">
                        <option value="">Tous les responsables</option>
                        @foreach($responsables as $responsable)
                            <option value="{{ $responsable->id }}">{{ $responsable->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Filtrer par gestionnaire</label>
                    <select class="form-control" name="gestionnaire_id">
                        <option value="">Tous les gestionnaires</option>
                        @foreach($gestionnaires as $gestionnaire)
                            <option value="{{ $gestionnaire->id }}">{{ $gestionnaire->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Rechercher un client</label>
                    <input type="text" class="form-control" name="search" placeholder="Nom du client...">
                </div>
            </div>
        </form>
    </div>

    <!-- Table des relations -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>Client</th>
                <th>Responsable</th>
                <th>Gestionnaire Principal</th>
                <th>Binôme</th>
                <th>Dernière modification</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($relations as $relation)
            <tr data-responsable-id="{{ $relation->responsable_paie_id }}"
                data-gestionnaire-id="{{ $relation->gestionnaire_principal_id }}"
                data-binome-id="{{ $relation->binome_id }}">
                <td><input type="checkbox" class="client-checkbox" value="{{ $relation->id }}"></td>
                <td>{{ $relation->name }}</td>
                <td>
                    <span class="badge badge-primary">
                        {{ optional($relation->responsablePaie)->name ?? 'Non assigné' }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-info">
                        {{ optional($relation->gestionnairePrincipal)->name ?? 'Non assigné' }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-secondary">
                        {{ optional($relation->binome)->name ?? 'Non assigné' }}
                    </span>
                </td>
                <td>{{ $relation->updated_at->format('d/m/Y H:i') }}</td>
                <td>
                    <div class="dropdown">
                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                            <i class="dw dw-more"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                            <a class="dropdown-item" href="{{ route('admin.client_user.edit', $relation->id) }}">
                                <i class="dw dw-edit2"></i> Modifier
                            </a>
                            <a class="dropdown-item btn-transfer" href="#" data-client="{{ $relation->id }}">
                                <i class="bi bi-arrow-left-right"></i> Transférer
                            </a>
                            <a class="dropdown-item text-danger btn-delete" href="#" data-id="{{ $relation->id }}">
                                <i class="dw dw-delete-3"></i> Supprimer
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $relations->links() }}
</div>

<!-- Modal de transfert -->
@include('admin.client_user.partials.transfer_modal')

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Gestion des filtres
    $('select, input[name="search"]').on('change keyup', function() {
        $('#filterForm').submit();
    });

    // Sélection multiple
    $('#select-all').on('change', function() {
        $('.client-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Transfert en masse
    $('.btn-transfer').on('click', function(e) {
        e.preventDefault();
        $('#transferModal').modal('show');
    });

    // Confirmation de suppression
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        if (confirm('Êtes-vous sûr de vouloir supprimer cette relation ?')) {
            // Logique de suppression
        }
    });
});
</script>
@endpush