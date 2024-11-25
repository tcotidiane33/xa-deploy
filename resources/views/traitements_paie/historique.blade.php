@extends('layouts.admin')

@section('title', 'Historique des Traitements de Paie')

@section('content')
<div class="main-content">
    <div class="main-page">
        <div class="row">
            <div class="container">
                <h1>Historique des Traitements de Paie</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Client</th>
                            <th>Gestionnaire</th>
                            <th>Période de Paie</th>
                            <th>Date de Création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($traitements as $traitement)
                            <tr>
                                <td>{{ $traitement->reference }}</td>
                                <td>{{ $traitement->client->name }}</td>
                                <td>{{ $traitement->gestionnaire->name }}</td>
                                <td>{{ $traitement->periodePaie->reference }}</td>
                                <td>{{ $traitement->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('traitements-paie.show', $traitement) }}" class="btn btn-info">Voir</a>
                                    @if(!$traitement->validee || auth()->user()->hasRole(['admin', 'responsable']))
                                        <a href="{{ route('traitements-paie.edit', $traitement) }}" class="btn btn-warning">Modifier</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $traitements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection