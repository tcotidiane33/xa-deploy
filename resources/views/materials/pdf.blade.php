<!DOCTYPE html>
<html>
<head>
    <title>{{ $material->title }}</title>
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
    <h1>{{ $material->title }}</h1>
    <table>
        <tr>
            <th>Client</th>
            <td>{{ $material->client->name }}</td>
        </tr>
        <tr>
            <th>Titre</th>
            <td>{{ $material->title }}</td>
        </tr>
        <tr>
            <th>Type</th>
            <td>{{ $material->type }}</td>
        </tr>
        <tr>
            <th>URL du contenu</th>
            <td>{{ $material->content_url }}</td>
        </tr>
        <tr>
            <th>Nom du champ</th>
            <td>{{ $material->field_name }}</td>
        </tr>
        <tr>
            <th>Créé le</th>
            <td>{{ $material->created_at->format('Y-m-d H:i:s') }}</td>
        </tr>
        <tr>
            <th>Mis à jour le</th>
            <td>{{ $material->updated_at->format('Y-m-d H:i:s') }}</td>
        </tr>
    </table>
</body>
</html>