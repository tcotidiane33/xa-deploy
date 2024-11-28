@component('mail::message')
Bonjour,

Je vous informe qu’à compter de ce jour, je suis votre interlocuteur principal pour la gestion des paies.
Ci-dessous mes coordonnées :

{{-- Nom : {{ $managerName }} --}}
- **Mail :** {{ $managerEmail }}
- **Téléphone :** {{ $managerPhone }}

Je reste à votre disposition.

Cordialement,  **{{ $clients }}**
**{{ $managerName }}**
Gestionnaire de paie

{{ config('app.name') }}
@endcomponent
