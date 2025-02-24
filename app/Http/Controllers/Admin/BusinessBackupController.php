<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\FicheClient;
use App\Models\PeriodePaie;
use App\Models\TraitementPaie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BusinessBackupController extends Controller
{
    protected $backupPath = 'business_backups';

    public function index()
    {
        $disk = Storage::disk('local');
        $files = $disk->files($this->backupPath);
        
        $backups = [];
        foreach ($files as $file) {
            if (substr($file, -5) == '.json') {
                $backups[] = [
                    'file_name' => basename($file),
                    'file_size' => $disk->size($file),
                    'last_modified' => $disk->lastModified($file),
                    'type' => $this->getBackupType($file)
                ];
            }
        }

        return view('admin.business-backups.index', compact('backups'));
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $data = [];

            // Sauvegarde des clients
            if ($request->input('backup_clients', true)) {
                $clients = Client::with([
                    'gestionnairePrincipal:id,name,email',
                    'responsablePaie:id,name,email',
                    'binome:id,name,email'
                ])->get()->map(function ($client) {
                    return [
                        'id' => $client->id,
                        'name' => $client->name,
                        'siret' => $client->siret,
                        'gestionnaire' => $client->gestionnairePrincipal ? [
                            'id' => $client->gestionnairePrincipal->id,
                            'name' => $client->gestionnairePrincipal->name,
                            'email' => $client->gestionnairePrincipal->email,
                        ] : null,
                        'responsable' => $client->responsablePaie ? [
                            'id' => $client->responsablePaie->id,
                            'name' => $client->responsablePaie->name,
                            'email' => $client->responsablePaie->email,
                        ] : null,
                        'created_at' => $client->created_at,
                        'updated_at' => $client->updated_at,
                    ];
                });
                $data['clients'] = $clients;
            }

            // Sauvegarde des fiches clients
            if ($request->input('backup_fiches', true)) {
                $fiches = FicheClient::with(['client:id,name', 'periodePaie:id,debut,fin'])
                    ->get()
                    ->map(function ($fiche) {
                        return [
                            'id' => $fiche->id,
                            'client_id' => $fiche->client_id,
                            'client_name' => $fiche->client->name,
                            'periode_paie' => [
                                'id' => $fiche->periodePaie->id,
                                'debut' => $fiche->periodePaie->debut,
                                'fin' => $fiche->periodePaie->fin,
                            ],
                            'status' => $fiche->status,
                            'notes' => $fiche->notes,
                            'created_at' => $fiche->created_at,
                            'updated_at' => $fiche->updated_at,
                        ];
                    });
                $data['fiches_clients'] = $fiches;
            }

            // Sauvegarde des périodes de paie
            if ($request->input('backup_periodes', true)) {
                $periodes = PeriodePaie::with(['traitements'])
                    ->get()
                    ->map(function ($periode) {
                        return [
                            'id' => $periode->id,
                            'debut' => $periode->debut,
                            'fin' => $periode->fin,
                            'status' => $periode->status,
                            'traitements_count' => $periode->traitements->count(),
                            'created_at' => $periode->created_at,
                            'updated_at' => $periode->updated_at,
                        ];
                    });
                $data['periodes_paie'] = $periodes;
            }

            // Sauvegarde des traitements de paie
            if ($request->input('backup_traitements', true)) {
                $traitements = TraitementPaie::with(['client:id,name', 'periodePaie:id,debut,fin'])
                    ->get()
                    ->map(function ($traitement) {
                        return [
                            'id' => $traitement->id,
                            'client_id' => $traitement->client_id,
                            'client_name' => $traitement->client->name,
                            'periode_paie' => [
                                'id' => $traitement->periodePaie->id,
                                'debut' => $traitement->periodePaie->debut,
                                'fin' => $traitement->periodePaie->fin,
                            ],
                            'status' => $traitement->status,
                            'notes' => $traitement->notes,
                            'created_at' => $traitement->created_at,
                            'updated_at' => $traitement->updated_at,
                        ];
                    });
                $data['traitements_paie'] = $traitements;
            }

            // Sauvegarde du fichier
            $fileName = "business_backup_{$timestamp}.json";
            Storage::disk('local')->put(
                $this->backupPath . '/' . $fileName,
                json_encode($data, JSON_PRETTY_PRINT)
            );

            DB::commit();

            return redirect()
                ->route('admin.business-backups.index')
                ->with('success', 'Sauvegarde des données métier effectuée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.business-backups.index')
                ->with('error', 'Erreur lors de la sauvegarde : ' . $e->getMessage());
        }
    }

    public function download($fileName)
    {
        $path = $this->backupPath . '/' . $fileName;
        
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->download($path);
        }

        return redirect()
            ->route('admin.business-backups.index')
            ->with('error', 'Fichier de sauvegarde introuvable.');
    }

    public function restore(Request $request, $fileName)
    {
        try {
            DB::beginTransaction();

            $path = $this->backupPath . '/' . $fileName;
            $content = Storage::disk('local')->get($path);
            $data = json_decode($content, true);

            // Restauration des données selon les options sélectionnées
            if ($request->input('restore_clients', true) && isset($data['clients'])) {
                foreach ($data['clients'] as $clientData) {
                    Client::updateOrCreate(
                        ['id' => $clientData['id']],
                        array_except($clientData, ['id', 'gestionnaire', 'responsable'])
                    );
                }
            }

            if ($request->input('restore_fiches', true) && isset($data['fiches_clients'])) {
                foreach ($data['fiches_clients'] as $ficheData) {
                    FicheClient::updateOrCreate(
                        ['id' => $ficheData['id']],
                        array_except($ficheData, ['id', 'client_name', 'periode_paie'])
                    );
                }
            }

            if ($request->input('restore_periodes', true) && isset($data['periodes_paie'])) {
                foreach ($data['periodes_paie'] as $periodeData) {
                    PeriodePaie::updateOrCreate(
                        ['id' => $periodeData['id']],
                        array_except($periodeData, ['id', 'traitements_count'])
                    );
                }
            }

            if ($request->input('restore_traitements', true) && isset($data['traitements_paie'])) {
                foreach ($data['traitements_paie'] as $traitementData) {
                    TraitementPaie::updateOrCreate(
                        ['id' => $traitementData['id']],
                        array_except($traitementData, ['id', 'client_name', 'periode_paie'])
                    );
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.business-backups.index')
                ->with('success', 'Restauration des données effectuée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.business-backups.index')
                ->with('error', 'Erreur lors de la restauration : ' . $e->getMessage());
        }
    }

    public function delete($fileName)
    {
        $path = $this->backupPath . '/' . $fileName;
        
        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
            return redirect()
                ->route('admin.business-backups.index')
                ->with('success', 'Sauvegarde supprimée avec succès.');
        }

        return redirect()
            ->route('admin.business-backups.index')
            ->with('error', 'Fichier de sauvegarde introuvable.');
    }

    protected function getBackupType($file)
    {
        $content = Storage::disk('local')->get($file);
        $data = json_decode($content, true);
        
        $types = [];
        if (isset($data['clients'])) $types[] = 'Clients';
        if (isset($data['fiches_clients'])) $types[] = 'Fiches clients';
        if (isset($data['periodes_paie'])) $types[] = 'Périodes de paie';
        if (isset($data['traitements_paie'])) $types[] = 'Traitements de paie';
        
        return implode(', ', $types);
    }
} 