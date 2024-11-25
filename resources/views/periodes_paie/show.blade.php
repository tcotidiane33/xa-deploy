@extends('layouts.admin')

@section('title', 'Détails de la période de paie')

@section('content')
<div class="container mx-auto p-4 pt-6 md:p-6">
    <h1 class="text-2xl font-bold mb-4">Détails de la période de paie</h1>

    <h2 class="text-xl font-bold mb-4">Période : {{ $periodePaie->reference }}</h2>
    {{-- <p>Date de début : {{ $periodePaie->debut->format('d/m/Y') }}</p>
    <p>Date de fin : {{ $periodePaie->fin->format('d/m/Y') }}</p> --}}

    @if(!$periodePaie->cloturee)
        {{-- <form action="{{ route('periodes-paie.close', $periodePaie) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-danger">Clôturer la période de paie</button>
        </form> --}}
    @endif

    <hr class="my-6">

    <h2 class="text-xl font-bold mb-4">Traitements de paie associés</h2>

    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>Client</th>
                <th>Réception des variables</th>
                <th>Préparation BP</th>
                <th>Validation BP client</th>
                <th>Préparation et envoie DSN</th>
                <th>Accusés DSN</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($traitementsPaie as $traitement)
                <tr>
                    <td>{{ $traitement->client->name }}</td>
                    <td>
                        <input type="date" name="reception_variables" data-field="reception_variables" value="{{ $traitement->reception_variables ? $traitement->reception_variables->format('Y-m-d') : '' }}" {{ $periodePaie->cloturee ? 'disabled' : '' }}>
                    </td>
                    <td>
                        <input type="date" name="preparation_bp" data-field="preparation_bp" value="{{ $traitement->preparation_bp ? $traitement->preparation_bp->format('Y-m-d') : '' }}" disabled {{ $periodePaie->cloturee ? 'disabled' : '' }}>
                    </td>
                    <td>
                        <input type="date" name="validation_bp_client" data-field="validation_bp_client" value="{{ $traitement->validation_bp_client ? $traitement->validation_bp_client->format('Y-m-d') : '' }}" disabled {{ $periodePaie->cloturee ? 'disabled' : '' }}>
                    </td>
                    <td>
                        <input type="date" name="preparation_envoie_dsn" data-field="preparation_envoie_dsn" value="{{ $traitement->preparation_envoie_dsn ? $traitement->preparation_envoie_dsn->format('Y-m-d') : '' }}" disabled {{ $periodePaie->cloturee ? 'disabled' : '' }}>
                    </td>
                    <td>
                        <input type="date" name="accuses_dsn" data-field="accuses_dsn" value="{{ $traitement->accuses_dsn ? $traitement->accuses_dsn->format('Y-m-d') : '' }}" disabled {{ $periodePaie->cloturee ? 'disabled' : '' }}>
                    </td>
                    <td>
                        <textarea name="notes" data-field="notes" {{ $periodePaie->cloturee ? 'disabled' : '' }}>{{ $traitement->notes }}</textarea>
                    </td>
                    <td>
                        @if(!$periodePaie->cloturee)
                            <button type="button" class="save-field btn btn-primary" data-traitement-id="{{ $traitement->id }}">Enregistrer</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const saveButtons = document.querySelectorAll('.save-field');

        saveButtons.forEach(button => {
            button.addEventListener('click', function () {
                const traitementId = this.getAttribute('data-traitement-id');
                const row = this.closest('tr');
                const fields = row.querySelectorAll('input, textarea');

                fields.forEach(field => {
                    const fieldName = field.getAttribute('data-field');
                    const fieldValue = field.value;

                    fetch('{{ route('periodes-paie.updateField') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            traitement_id: traitementId,
                            field: fieldName,
                            value: fieldName === 'notes' ? fieldValue : null,
                            date_value: fieldName !== 'notes' ? fieldValue : null
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Champ mis à jour avec succès');
                            if (fieldName === 'reception_variables') {
                                row.querySelector('input[name="preparation_bp"]').disabled = false;
                            } else if (fieldName === 'preparation_bp') {
                                row.querySelector('input[name="validation_bp_client"]').disabled = false;
                            } else if (fieldName === 'validation_bp_client') {
                                row.querySelector('input[name="preparation_envoie_dsn"]').disabled = false;
                            } else if (fieldName === 'preparation_envoie_dsn') {
                                row.querySelector('input[name="accuses_dsn"]').disabled = false;
                            }
                        } else {
                            alert('Erreur lors de la mise à jour du champ');
                        }
                    });
                });
            });
        });
    });
</script>
@endsection


{{-- 
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Détails de la période de paie</h1>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="reference">
                Référence
            </label>
            <p class="text-gray-700">{{ $periodePaie->reference }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="debut">
                Date de début
            </label>
            <p class="text-gray-700">{{ $periodePaie->debut->format('d/m/Y') }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="fin">
                Date de fin
            </label>
            <p class="text-gray-700">{{ $periodePaie->fin->format('d/m/Y') }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="client">
                Client
            </label>
            <p class="text-gray-700">{{ $periodePaie->client->name }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="reception_variables">
                Réception variables
            </label>
            <p class="text-gray-700">{{ $periodePaie->decrypted_data['reception_variables'] }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="preparation_bp">
                Préparation BP
            </label>
            <p class="text-gray-700">{{ $periodePaie->decrypted_data['preparation_bp'] }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="validation_bp_client">
                Validation BP client
            </label>
            <p class="text-gray-700">{{ $periodePaie->decrypted_data['validation_bp_client'] }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="preparation_envoie_dsn">
                Préparation et envoie DSN
            </label>
            <p class="text-gray-700">{{ $periodePaie->decrypted_data['preparation_envoie_dsn'] }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="accuses_dsn">
                Accusés DSN
            </label>
            <p class="text-gray-700">{{ $periodePaie->decrypted_data['accuses_dsn'] }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">
                Notes
            </label>
            <p class="text-gray-700">{{ $periodePaie->decrypted_data['notes'] }}</p>
        </div>
        <div class="flex items-center justify-between">
            <a href="{{ route('periodes-paie.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Retour
            </a>
        </div>
    </div>
</div>
@endsection --}}