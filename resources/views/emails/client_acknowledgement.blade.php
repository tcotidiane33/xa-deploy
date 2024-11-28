@component('mail::message')
Bonjour,

J’accuse bonne réception de vos variables de payes, vous payes vous seront livrés sous 24 à 72 heures.
Je reste à votre disposition.

Cordialement,
**{{ $managerName }}**
Gestionnaire de paie

details :
Nous vous informons que votre gestionnaire {{ $managerName }} ({{ $managerEmail }}) a pris en charge le client {{ $clientName }}.


{{ config('app.name') }}
@endcomponent
