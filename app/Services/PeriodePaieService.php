<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\Material;
use App\Models\FicheClient;
use App\Models\PeriodePaie;
use Illuminate\Http\Request;
use App\Models\TraitementPaie;
use App\Models\MaterialHistory;
use App\Models\PeriodePaieHistory;
use Illuminate\Support\Facades\Log;
use App\Exports\ClientPeriodeExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Notifications\FicheClientActionNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class PeriodePaieService
{
    public function generateReference()
    {
        $currentMonth = Carbon::now()->format('F');
        $currentYear = Carbon::now()->format('Y');
        return 'PERIODE_' . strtoupper($currentMonth) . '_' . $currentYear;
    }

    public function createPeriodePaie(array $data)
    {
        $periodePaie = PeriodePaie::create($data);
        $periodePaie->generateReference();
        $periodePaie->save();

        PeriodePaieHistory::create([
            'periode_paie_id' => $periodePaie->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'details' => 'Période de paie créée',
        ]);

        // Dupliquer les fiches clients de la période précédente
        $this->duplicateFichesClients($periodePaie);

        return $periodePaie;
    }

    protected function duplicateFichesClients(PeriodePaie $newPeriodePaie)
    {
        $previousPeriodePaie = PeriodePaie::where('validee', true)->latest()->first();

        if ($previousPeriodePaie) {
            $previousFichesClients = $previousPeriodePaie->fichesClients;

            foreach ($previousFichesClients as $previousFicheClient) {
                FicheClient::create([
                    'client_id' => $previousFicheClient->client_id,
                    'periode_paie_id' => $newPeriodePaie->id, // Assurez-vous que l'ID de la nouvelle période de paie est utilisé
                    'reception_variables' => null,
                    'reception_variables_file' => null,
                    'preparation_bp' => null,
                    'preparation_bp_file' => null,
                    'validation_bp_client' => null,
                    'validation_bp_client_file' => null,
                    'preparation_envoie_dsn' => null,
                    'preparation_envoie_dsn_file' => null,
                    'accuses_dsn' => null,
                    'accuses_dsn_file' => null,
                    'notes' => $previousFicheClient->notes, // Conserver les notes
                ]);
            }
        }
    }

    // Mehtode pour Update la fiche client

    public function updateFicheClient(FicheClient $ficheClient, array $data)
    {
        Log::info('Mise à jour de la fiche client : ', ['fiche_client_id' => $ficheClient->id, 'data' => $data]);

        if (!empty($data['notes'])) {
            $newNotes = $ficheClient->notes . "\n" . now()->format('Y-m-d') . ': ' . $data['notes'];
            $data['notes'] = $newNotes;
        }

        $ficheClient->update($data);

        Log::info('Fiche client mise à jour avec succès : ', ['fiche_client_id' => $ficheClient->id]);
        // Envoyer la notification
        $ficheClient->notifyAction('updated', 'Fiche client mise à jour');
    }

    public function updatePeriodePaie(PeriodePaie $periodePaie, array $data)
    {
        $periodePaie->update($data);

        PeriodePaieHistory::create([
            'periode_paie_id' => $periodePaie->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'details' => 'Période de paie mise à jour',
        ]);

        return $periodePaie;
    }

    public function openPeriodePaie(PeriodePaie $periodePaie)
    {
        $periodePaie->validee = false;
        $periodePaie->save();

        PeriodePaieHistory::create([
            'periode_paie_id' => $periodePaie->id,
            'user_id' => Auth::id(),
            'action' => 'opened',
            'details' => 'Période de paie déclôturée',
        ]);
    }

    /**
     * Clôturer la période de paie.
     *
     * Cette méthode crée une sauvegarde des données de la période de paie avant de la clôturer.
     * Les données de la période de paie et des fiches clients sont sauvegardées dans le système de backup.
     * Après la clôture, les informations sont renouvelées pour la nouvelle période.
     *
     * @param PeriodePaie $periodePaie
     * @throws \Exception
     */
    public function closePeriodePaie(PeriodePaie $periodePaie)
    {
        try {
            DB::beginTransaction();

            // Créer une sauvegarde avant la clôture
            $this->createBackup($periodePaie);

            // Vider les colonnes spécifiées des fiches clients
            $this->clearFicheClientColumns($periodePaie);

            $periodePaie->validee = true;
            $periodePaie->save();

            PeriodePaieHistory::create([
                'periode_paie_id' => $periodePaie->id,
                'user_id' => Auth::id(),
                'action' => 'closed',
                'details' => 'Période clôturée avec backup'
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Vider les colonnes spécifiées des fiches clients.
     *
     * @param PeriodePaie $periodePaie
     */
    protected function clearFicheClientColumns(PeriodePaie $periodePaie)
    {
        foreach ($periodePaie->fichesClients as $ficheClient) {
            $ficheClient->update([
                'reception_variables' => null,
                'reception_variables_file' => null,
                'preparation_bp' => null,
                'preparation_bp_file' => null,
                'validation_bp_client' => null,
                'validation_bp_client_file' => null,
                'preparation_envoie_dsn' => null,
                'preparation_envoie_dsn_file' => null,
                'accuses_dsn' => null,
                'accuses_dsn_file' => null,
                'nb_bulletins_file' => null,
                'maj_fiche_para_file' => null,
            ]);
        }
    }

    /**
     * Créer une sauvegarde des données de la période de paie.
     *
     * Cette méthode sauvegarde les données de la période de paie et des fiches clients associées
     * dans le système de backup (Laravel Backup).
     *
     * @param PeriodePaie $periodePaie
     * @return string
     */
    protected function createBackup(PeriodePaie $periodePaie)
    {
        $backup = [
            'periode' => $periodePaie->toArray(),
            'fiches_clients' => $periodePaie->fichesClients()->with('client')->get()->toArray(),
            'created_at' => now()->format('Y-m-d H:i:s')
        ];

        $filename = 'backup_periode_' . $periodePaie->id . '_' . now()->format('Y-m-d_H-i-s') . '.json';
        Storage::disk('backups')->put($filename, json_encode($backup, JSON_PRETTY_PRINT));

        return $filename;
    }

    protected function createFilesForClients(PeriodePaie $periodePaie)
    {
        $clients = Client::all();

        foreach ($clients as $client) {
            $ficheClient = FicheClient::where('client_id', $client->id)
                ->where('periode_paie_id', $periodePaie->id)
                ->first();

            if ($ficheClient) {
                $fileName = $client->name . '_BACKUP_' . $periodePaie->reference . '_FC_' . now()->year . '.xlsx';
                $filePath = 'materials/' . $fileName;

                // Générer le fichier Excel
                Excel::store(new ClientPeriodeExport($periodePaie), $filePath);

                // Créer un enregistrement dans la table materials
                $material = Material::create([
                    'client_id' => $client->id,
                    'user_id' => Auth::id(),
                    'title' => $fileName,
                    'type' => 'excel',
                    'content_url' => $filePath,
                    'field_name' => 'Période de paie'
                ]);


                // Enregistrer l'historique des actions sur le matériau
                $this->logMaterialAction($material, 'created', 'Fichier de sauvegarde créé lors de la clôture de la période de paie.');
                // Conserver les notes de rappel
                $previousNotes = $ficheClient->notes;
                // Vider les données de la fiche client
                $ficheClient->update([
                    // 'reception_variables' => null,
                    // 'reception_variables_file' => null,
                    // 'preparation_bp' => null,
                    // 'preparation_bp_file' => null,
                    // 'validation_bp_client' => null,
                    // 'validation_bp_client_file' => null,
                    // 'preparation_envoie_dsn' => null,
                    // 'preparation_envoie_dsn_file' => null,
                    // 'accuses_dsn' => null,
                    // 'accuses_dsn_file' => null,
                    'notes' => $previousNotes, // Conserver les notes de rappel
                ]);
            }
        }
    }

    protected function logMaterialAction(Material $material, $action, $details)
    {
        MaterialHistory::create([
            'material_id' => $material->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'details' => $details,
        ]);
    }
    public function deletePeriodePaie(PeriodePaie $periodePaie)
    {
        $periodePaie->delete();

        PeriodePaieHistory::create([
            'periode_paie_id' => $periodePaie->id,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'details' => 'Période de paie supprimée',
        ]);
    }

    public function validatePeriode(PeriodePaie $periodePaie)
    {
        try {
            // Vérifier que tous les champs obligatoires sont remplis
            $incompleteFiches = $periodePaie->fichesClients()
                ->where(function ($query) {
                    $query->whereNull('reception_variables')
                        ->orWhereNull('preparation_bp')
                        ->orWhereNull('validation_bp_client')
                        ->orWhereNull('preparation_envoie_dsn')
                        ->orWhereNull('accuses_dsn');
                })->count();

            if ($incompleteFiches > 0) {
                throw new \Exception('Toutes les fiches clients doivent être complétées avant la validation.');
            }

            // Valider la période
            $periodePaie->validee = true;
            $periodePaie->save();

            // Créer l'historique
            PeriodePaieHistory::create([
                'periode_paie_id' => $periodePaie->id,
                'user_id' => Auth::id(),
                'action' => 'validated',
                'details' => 'Période validée et clôturée'
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la validation de la période : ' . $e->getMessage());
            throw $e;
        }
    }

    public function encryptOldPeriods()
    {
        $periodes = PeriodePaie::where('validee', 1)->get();

        foreach ($periodes as $periode) {
            if ($periode->shouldBeEncrypted()) {
                $periode->encrypted_data = $periode->encryptedData;
                $periode->save();
            }
        }
    }

    public function updateField(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periodes_paie,id',
            'field' => 'required|string',
            'value' => 'nullable|string',
            'date_value' => 'nullable|date',
        ]);

        $periodePaie = PeriodePaie::findOrFail($request->periode_id);
        $field = $request->field;
        $value = $request->value;

        // Utiliser la valeur de date si le champ sélectionné est une date
        if (in_array($field, ['reception_variables', 'preparation_bp', 'validation_bp_client', 'preparation_envoie_dsn', 'accuses_dsn'])) {
            $value = $request->date_value;
        }

        $periodePaie->update([$field => $value]);

        PeriodePaieHistory::create([
            'periode_paie_id' => $periodePaie->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'details' => "Champ $field mis à jour",
        ]);
    }

    public function getEligibleClients()
    {
        $nextMonth = Carbon::now()->addMonth();
        $fifteenthNextMonth = $nextMonth->copy()->day(15);

        return Client::where('date_signature_contrat', '<=', $fifteenthNextMonth)
            ->where('date_fin_prestation', '>=', $fifteenthNextMonth)
            ->get();
    }

    public function getEligibleClientsForCurrentPeriod()
    {
        $currentPeriod = PeriodePaie::where('validee', false)->latest()->first();
        if (!$currentPeriod) {
            return collect(); // Retourne une collection vide si aucune période en cours n'est trouvée
        }

        return Client::where('date_signature_contrat', '<=', $currentPeriod->fin)
            ->where('date_fin_prestation', '>=', $currentPeriod->debut)
            ->get();
    }

    public function migrateToPeriode(PeriodePaie $currentPeriode)
    {
        try {
            // Vérifier qu'il n'y a pas d'autre période active
            if (PeriodePaie::where('validee', false)
                ->where('id', '!=', $currentPeriode->id)
                ->exists()) {
                throw new \Exception('Une autre période est déjà active.');
            }

            // Vérifier que toutes les fiches sont complètes
            $incompleteFiches = $currentPeriode->fichesClients()
                ->where(function ($query) {
                    $query->whereNull('reception_variables')
                        ->orWhereNull('preparation_bp')
                        ->orWhereNull('validation_bp_client')
                        ->orWhereNull('preparation_envoie_dsn')
                        ->orWhereNull('accuses_dsn');
                })->count();

            if ($incompleteFiches > 0) {
                throw new \Exception('Toutes les fiches clients doivent être complétées avant la migration.');
            }

            // Créer la nouvelle période
            $newPeriode = PeriodePaie::create([
                'debut' => $currentPeriode->fin->addDay(),
                'fin' => $currentPeriode->fin->addMonth(),
                'validee' => false,
                'reference' => 'PERIODE_' . $currentPeriode->fin->addDay()->format('F_Y')
            ]);

            // Copier les données persistantes
            foreach ($currentPeriode->fichesClients as $ficheClient) {
                FicheClient::create([
                    'client_id' => $ficheClient->client_id,
                    'periode_paie_id' => $newPeriode->id,
                    'notes' => $ficheClient->notes,
                    'nb_bulletins' => $ficheClient->nb_bulletins,
                    'maj_fiche_para' => $ficheClient->maj_fiche_para
                ]);
            }

            // Clôturer l'ancienne période
            $currentPeriode->validee = true;
            $currentPeriode->save();

            // Créer l'historique
            PeriodePaieHistory::create([
                'periode_paie_id' => $currentPeriode->id,
                'user_id' => Auth::id(),
                'action' => 'migrated',
                'details' => 'Migration vers la nouvelle période ' . $newPeriode->reference
            ]);

            return $newPeriode;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la migration : ' . $e->getMessage());
            throw $e;
        }
    }

    public function migrateAllClientsToNewPeriode(PeriodePaie $currentPeriode)
    {
        try {
            DB::beginTransaction();

            // Vérifier que la période actuelle est complète
            $incompleteFiches = $currentPeriode->fichesClients()
                ->where(function ($query) {
                    $query->whereNull('reception_variables')
                        ->orWhereNull('preparation_bp')
                        ->orWhereNull('validation_bp_client')
                        ->orWhereNull('preparation_envoie_dsn')
                        ->orWhereNull('accuses_dsn');
                })->count();

            if ($incompleteFiches > 0) {
                throw new \Exception('Certaines fiches clients sont incomplètes. Impossible de migrer.');
            }

            // Créer la nouvelle période
            $newPeriode = PeriodePaie::create([
                'debut' => $currentPeriode->fin->addDay(),
                'fin' => $currentPeriode->fin->addMonth(),
                'validee' => false,
                'reference' => 'PERIODE_' . $currentPeriode->fin->addDay()->format('F_Y')
            ]);

            // Récupérer tous les clients actifs
            $clients = Client::where('actif', true)->get();

            // Migrer chaque client
            foreach ($clients as $client) {
                $oldFiche = FicheClient::where('client_id', $client->id)
                    ->where('periode_paie_id', $currentPeriode->id)
                    ->first();

                // Créer une nouvelle fiche client avec les données persistantes
                FicheClient::create([
                    'client_id' => $client->id,
                    'periode_paie_id' => $newPeriode->id,
                    'notes' => $oldFiche ? $oldFiche->notes : null,
                    'nb_bulletins' => $client->nb_bulletins,
                    'maj_fiche_para' => $client->maj_fiche_para
                ]);
            }

            // Clôturer l'ancienne période
            $currentPeriode->validee = true;
            $currentPeriode->save();

            // Créer l'historique
            PeriodePaieHistory::create([
                'periode_paie_id' => $currentPeriode->id,
                'user_id' => Auth::id(),
                'action' => 'mass_migration',
                'details' => 'Migration en masse vers la nouvelle période ' . $newPeriode->reference
            ]);

            DB::commit();
            return $newPeriode;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la migration en masse : ' . $e->getMessage());
            throw $e;
        }
    }

    public function migrateSelectedClients($clients)
    {
        try {
            DB::beginTransaction();

            $currentPeriode = PeriodePaie::where('validee', false)
                ->whereDate('fin', '>=', now())
                ->first();

            if (!$currentPeriode) {
                // Créer une nouvelle période si aucune n'est active
                $currentPeriode = PeriodePaie::create([
                    'debut' => now()->startOfMonth(),
                    'fin' => now()->endOfMonth(),
                    'validee' => false,
                    'reference' => 'PERIODE_' . now()->format('F_Y')
                ]);
            }

            foreach ($clients as $client) {
                // Vérifier si le client n'a pas déjà une fiche pour cette période
                $existingFiche = FicheClient::where('client_id', $client->id)
                    ->where('periode_paie_id', $currentPeriode->id)
                    ->exists();

                if (!$existingFiche && $client->date_fin_prestation > now()) {
                    FicheClient::create([
                        'client_id' => $client->id,
                        'periode_paie_id' => $currentPeriode->id,
                        'notes' => '',
                        'reception_variables' => null,
                        'preparation_bp' => null,
                        'validation_bp_client' => null,
                        'preparation_envoie_dsn' => null,
                        'accuses_dsn' => null
                    ]);
                }
            }

            PeriodePaieHistory::create([
                'periode_paie_id' => $currentPeriode->id,
                'user_id' => Auth::id(),
                'action' => 'selective_migration',
                'details' => 'Migration sélective des clients actifs'
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la migration sélective : ' . $e->getMessage());
            throw $e;
        }
    }

    public function migrateClients($clients)
    {
        try {
            DB::beginTransaction();

            $currentPeriode = PeriodePaie::where('validee', false)
                ->whereDate('fin', '>=', now())
                ->first();

            if (!$currentPeriode) {
                $currentPeriode = PeriodePaie::create([
                    'debut' => now()->startOfMonth(),
                    'fin' => now()->endOfMonth(),
                    'reference' => 'PERIODE_' . now()->format('Y_m'),
                    'validee' => false
                ]);
            }

            foreach ($clients as $client) {
                $existingFiche = FicheClient::where('client_id', $client->id)
                    ->where('periode_paie_id', $currentPeriode->id)
                    ->exists();

                if (!$existingFiche) {
                    FicheClient::create([
                        'client_id' => $client->id,
                        'periode_paie_id' => $currentPeriode->id,
                        'reception_variables' => null,
                        'preparation_bp' => null,
                        'validation_bp_client' => null,
                        'preparation_envoie_dsn' => null,
                        'accuses_dsn' => null,
                        'notes' => ''
                    ]);
                }
            }

            PeriodePaieHistory::create([
                'user_id' => Auth::id(),
                'periode_paie_id' => $currentPeriode->id,
                'action' => 'migration',
                'details' => 'Migration de ' . $clients->count() . ' clients'
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
