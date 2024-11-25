<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour de la relation Gestionnaire-Client</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a5568;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
        }
        .footer {
            background-color: #f7fafc;
            padding: 10px 20px;
            text-align: center;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mise à jour de la relation Gestionnaire-Client</h1>
        </div>
        <div class="content">
            <p>Bonjour,</p>
            <p>Une mise à jour a été effectuée sur une relation Gestionnaire-Client :</p>
            <p><strong>Action :</strong> {{ $action }}</p>
            <p><strong>Détails :</strong> {{ $details }}</p>
            <p>Veuillez vous connecter à la plateforme pour plus d'informations.</p>
        </div>
        <div class="footer">
            <p>Ceci est un message automatique, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>



