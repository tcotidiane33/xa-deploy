@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="container">
            <br><br><br>
        </div>
        <h1 class="text-3xl font-bold mb-6">Détails de la relation Gestionnaire-Client</h1>


        <div class="main-content">
            <div class="container">
                <h1>Détails de la relation Gestionnaire-Client</h1>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informations principales</h5>
                        <p><strong>Client:</strong> {{ $gestionnaireClient->client->name }}</p>
                        <p><strong>Gestionnaire Principal:</strong> {{ $gestionnaireClient->gestionnaire->name }}</p>
                        <p><strong>Est Principal:</strong> {{ $gestionnaireClient->is_principal ? 'Oui' : 'Non' }}</p>
                        <p><strong>Notes:</strong> {{ $gestionnaireClient->notes ?? 'Aucune note' }}</p>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Gestionnaires Secondaires</h5>
                        @if ($gestionnaireClient->gestionnairesSecondaires->count() > 0)
                            <ul>
                                @foreach ($gestionnaireClient->gestionnairesSecondaires as $gestionnaire)
                                    <li>{{ $gestionnaire->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>Aucun gestionnaire secondaire</p>
                        @endif
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Documents</h5>
                        @if ($gestionnaireClient->documents->count() > 0)
                            <ul>
                                @foreach ($gestionnaireClient->documents as $document)
                                    <li>
                                        <a href="{{ route('admin.documents.download', $document->id) }}">
                                            {{ $document->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>Aucun document associé</p>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.gestionnaire-client.edit', $gestionnaireClient->id) }}"
                        class="btn btn-primary">Modifier</a>
                    <form action="{{ route('admin.gestionnaire-client.destroy', $gestionnaireClient->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette relation ?')">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.gestionnaire-client.edit', $gestionnaireClient->id) }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Modifier
            </a>
            <form action="{{ route('admin.gestionnaire-client.destroy', $gestionnaireClient->id) }}" method="POST"
                class="inline-block ml-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette relation ?')">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
@endsection
