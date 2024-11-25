<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notification de Client</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
        }
        .footer {
            text-align: center;
            padding: 10px 0;
            color: #777777;
            font-size: 12px;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Notification de Client</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $data['managerName'] }},</p>
            <p>Vous avez une nouvelle notification pour le client <strong>{{ $data['clientName'] }}</strong>.</p>
            <p><strong>Email du client :</strong> {{ $data['clientEmail'] }}</p>
            <hr>
            <h2>Détails du Gestionnaire</h2>
            <p><strong>Nom :</strong> {{ $data['managerName'] }}</p>
            <p><strong>Email :</strong> {{ $data['managerEmail'] }}</p>
            <p><strong>Téléphone :</strong> {{ $data['managerPhone'] ?? 'N/A' }}</p> <!-- Assurez-vous que cette clé est définie -->
            <hr>
            <h2>Détails du Client</h2>
            <p><strong>Nom :</strong> {{ $data['clientName'] }}</p>
            <p><strong>Email :</strong> {{ $data['clientEmail'] }}</p>
            <p>Merci,</p>
            <p>L'équipe {{ config('app.name') }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
            <p><a href="{{ url('/') }}">Visitez notre site web</a></p>
        </div>
    </div>
</body>
</html>
