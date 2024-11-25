<?php

namespace App\Exports;

use App\Models\Client;
use App\Models\PeriodePaie;
use App\Models\FicheClient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientPeriodeExport implements FromCollection, WithHeadings, WithMapping
{
    protected $periodePaie;

    public function __construct(PeriodePaie $periodePaie)
    {
        $this->periodePaie = $periodePaie;
    }

    public function collection()
    {
        return Client::all();
    }

    public function headings(): array
    {
        return [
            'Nom',
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

    public function map($client): array
    {
        $ficheClient = FicheClient::where('client_id', $client->id)
                                  ->where('periode_paie_id', $this->periodePaie->id)
                                  ->first();

        return [
            $client->name,
            $client->email,
            $client->phone,
            $client->responsable ? $client->responsable->name : 'N/A',
            $client->gestionnaire ? $client->gestionnaire->name : 'N/A',
            "{$client->contact_paie_nom} ({$client->contact_paie_email})",
            "{$client->contact_compta_nom} ({$client->contact_compta_email})",
            $this->periodePaie->reference,
            $this->periodePaie->debut->format('Y-m-d'),
            $this->periodePaie->fin->format('Y-m-d'),
            $ficheClient && $ficheClient->reception_variables instanceof \Carbon\Carbon ? $ficheClient->reception_variables->format('Y-m-d') : 'N/A',
            $ficheClient ? $ficheClient->reception_variables_file : 'N/A',
            $ficheClient && $ficheClient->preparation_bp instanceof \Carbon\Carbon ? $ficheClient->preparation_bp->format('Y-m-d') : 'N/A',
            $ficheClient ? $ficheClient->preparation_bp_file : 'N/A',
            $ficheClient && $ficheClient->validation_bp_client instanceof \Carbon\Carbon ? $ficheClient->validation_bp_client->format('Y-m-d') : 'N/A',
            $ficheClient ? $ficheClient->validation_bp_client_file : 'N/A',
            $ficheClient && $ficheClient->preparation_envoie_dsn instanceof \Carbon\Carbon ? $ficheClient->preparation_envoie_dsn->format('Y-m-d') : 'N/A',
            $ficheClient ? $ficheClient->preparation_envoie_dsn_file : 'N/A',
            $ficheClient && $ficheClient->accuses_dsn instanceof \Carbon\Carbon ? $ficheClient->accuses_dsn->format('Y-m-d') : 'N/A',
            $ficheClient ? $ficheClient->accuses_dsn_file : 'N/A',
            $ficheClient ? $ficheClient->notes : 'N/A'
        ];
    }
}