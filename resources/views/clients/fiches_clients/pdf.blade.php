<!DOCTYPE html>
<html>
<head>
    <title>Fiches Clients</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Fiches Clients</h1>
    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Responsable</th>
                <th>Gestionnaire</th>
                <th>Contact Paie</th>
                <th>Contact Comptable</th>
                <th>Référence Période Paie</th>
                <th>Début Période Paie</th>
                <th>Fin Période Paie</th>
                <th>Réception Variables</th>
                <th>Préparation BP</th>
                <th>Validation BP Client</th>
                <th>Préparation et Envoi DSN</th>
                <th>Accusés DSN</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fichesClients as $ficheClient)
                <tr>
                    <td>{{ $ficheClient->client->name }}</td>
                    <td>{{ $ficheClient->client->email }}</td>
                    <td>{{ $ficheClient->client->phone }}</td>
                    <td>{{ $ficheClient->client->responsable ? $ficheClient->client->responsable->name : 'N/A' }}</td>
                    <td>{{ $ficheClient->client->gestionnaire ? $ficheClient->client->gestionnaire->name : 'N/A' }}</td>
                    <td>{{ $ficheClient->client->contact_paie_nom }} ({{ $ficheClient->client->contact_paie_email }})</td>
                    <td>{{ $ficheClient->client->contact_compta_nom }} ({{ $ficheClient->client->contact_compta_email }})</td>
                    <td>{{ $ficheClient->periodePaie->reference }}</td>
                    <td>{{ $ficheClient->periodePaie->debut->format('Y-m-d') }}</td>
                    <td>{{ $ficheClient->periodePaie->fin->format('Y-m-d') }}</td>
                    <td>{{ $ficheClient->reception_variables ? \Carbon\Carbon::parse($ficheClient->reception_variables)->format('d/m') : 'N/A' }}</td>
                    <td>{{ $ficheClient->preparation_bp ? \Carbon\Carbon::parse($ficheClient->preparation_bp)->format('d/m') : 'N/A' }}</td>
                    <td>{{ $ficheClient->validation_bp_client ? \Carbon\Carbon::parse($ficheClient->validation_bp_client)->format('d/m') : 'N/A' }}</td>
                    <td>{{ $ficheClient->preparation_envoie_dsn ? \Carbon\Carbon::parse($ficheClient->preparation_envoie_dsn)->format('d/m') : 'N/A' }}</td>
                    <td>{{ $ficheClient->accuses_dsn ? \Carbon\Carbon::parse($ficheClient->accuses_dsn)->format('d/m') : 'N/A' }}</td>
                    <td>{{ $ficheClient->notes ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>