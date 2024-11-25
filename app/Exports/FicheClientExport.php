<?php

namespace App\Exports;

use App\Models\FicheClient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FicheClientExport implements FromCollection, WithHeadings, WithMapping
{
    protected $fichesClients;

    public function __construct($fichesClients)
    {
        $this->fichesClients = $fichesClients;
    }

    public function collection()
    {
        return $this->fichesClients;
    }

    public function headings(): array
    {
        return [
            'Client',
            'Email',
            'Téléphone',
            'Responsable',
            'Gestionnaire',
            'Contact Paie',
            'Contact Comptable',
            'Référence Période Paie',
            'Début Période Paie',
            'Fin Période Paie',
            'Réception Variables',
            'Fichier Réception Variables',
            'Préparation BP',
            'Fichier Préparation BP',
            'Validation BP Client',
            'Fichier Validation BP Client',
            'Préparation et Envoi DSN',
            'Fichier Préparation et Envoi DSN',
            'Accusés DSN',
            'Fichier Accusés DSN',
            'Notes'
        ];
    }

    public function map($ficheClient): array
    {
        $client = $ficheClient->client;
        $periodePaie = $ficheClient->periodePaie;
        $traitementPaie = $ficheClient->traitementPaie;

        return [
            $client->name,
            $client->email,
            $client->phone,
            $client->responsable ? $client->responsable->name : 'N/A',
            $client->gestionnaire ? $client->gestionnaire->name : 'N/A',
            "{$client->contact_paie_nom} ({$client->contact_paie_email})",
            "{$client->contact_compta_nom} ({$client->contact_compta_email})",
            $periodePaie->reference,
            $periodePaie->debut->format('Y-m-d'),
            $periodePaie->fin->format('Y-m-d'),
            $ficheClient->reception_variables ? \Carbon\Carbon::parse($ficheClient->reception_variables)->format('d/m') : 'N/A',
            $ficheClient->reception_variables_file ?? 'N/A',
            $ficheClient->preparation_bp ? \Carbon\Carbon::parse($ficheClient->preparation_bp)->format('d/m') : 'N/A',
            $ficheClient->preparation_bp_file ?? 'N/A',
            $ficheClient->validation_bp_client ? \Carbon\Carbon::parse($ficheClient->validation_bp_client)->format('d/m') : 'N/A',
            $ficheClient->validation_bp_client_file ?? 'N/A',
            $ficheClient->preparation_envoie_dsn ? \Carbon\Carbon::parse($ficheClient->preparation_envoie_dsn)->format('d/m') : 'N/A',
            $ficheClient->preparation_envoie_dsn_file ?? 'N/A',
            $ficheClient->accuses_dsn ? \Carbon\Carbon::parse($ficheClient->accuses_dsn)->format('d/m') : 'N/A',
            $ficheClient->accuses_dsn_file ?? 'N/A',
            $ficheClient->notes ?? 'N/A'
        ];
    }
}