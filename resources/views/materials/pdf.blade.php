<!DOCTYPE html>
<html>
<head>
    <title>{{ $material->title }}</title>
    <style>
        @page {
            margin: 3cm 2cm;
        }
        
        body {
            font-family: Arial, sans-serif;
            color: #2d3748;
            line-height: 1.6;
            position: relative;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(229, 231, 235, 0.4);
            z-index: -1;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }

        h1 {
            color: #1a56db;
            font-size: 24px;
            margin: 0;
            padding: 10px 0;
        }

        .metadata {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
        }

        th {
            background-color: #f7fafc;
            color: #4a5568;
            font-weight: bold;
            width: 30%;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #718096;
            padding: 10px 0;
            border-top: 1px solid #e2e8f0;
        }

        .type-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .type-document {
            background-color: #ebf5ff;
            color: #1a56db;
        }

        .type-image {
            background-color: #f0fff4;
            color: #047857;
        }

        .type-autre {
            background-color: #f3f4f6;
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="watermark">BACKUP</div>

    <div class="header">
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        @endif
        <h1>{{ $material->title }}</h1>
        <div class="metadata">
            Généré le {{ now()->format('d/m/Y à H:i') }}
        </div>
    </div>

    <table>
        <tr>
            <th>Client</th>
            <td>{{ $material->client->name }}</td>
        </tr>
        <tr>
            <th>Type</th>
            <td>
                <span class="type-badge type-{{ $material->type }}">
                    {{ ucfirst($material->type) }}
                </span>
            </td>
        </tr>
        <tr>
            <th>URL du contenu</th>
            <td>{{ $material->content_url ?: 'Non spécifié' }}</td>
        </tr>
        <tr>
            <th>Nom du champ</th>
            <td>{{ $material->field_name ?: 'Non spécifié' }}</td>
        </tr>
        <tr>
            <th>Créé le</th>
            <td>{{ $material->created_at->format('d/m/Y à H:i') }}</td>
        </tr>
        <tr>
            <th>Mis à jour le</th>
            <td>{{ $material->updated_at->format('d/m/Y à H:i') }}</td>
        </tr>
    </table>

    <div class="footer">
        Document confidentiel - {{ config('app.name') }} © {{ date('Y') }}
    </div>
</body>
</html>