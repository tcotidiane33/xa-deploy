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
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

            // Vérifier s'il existe une période en cours
            $currentPeriode = PeriodePaie::where('validee', false)->first();
            if (!$currentPeriode) {
                throw new \Exception('Aucune période de paie en cours trouvée.');
            }

            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $data = [];

            // Sauvegarder la période actuelle
            if ($request->input('backup_periodes', true)) {
                $periodes = PeriodePaie::with(['traitements', 'fichesClients'])
                    ->where('id', $currentPeriode->id)
                    ->get()
                    ->map(function ($periode) {
                        return [
                            'id' => $periode->id,
                            'debut' => $periode->debut,
                            'fin' => $periode->fin,
                            'validee' => true, // Forcer la validation
                            'reference' => $periode->reference,
                            'traitements_count' => $periode->traitements->count(),
                            'created_at' => $periode->created_at,
                            'updated_at' => $periode->updated_at
                        ];
                    });
                $data['periodes_paie'] = $periodes;

                // Créer la nouvelle période
                $newPeriode = PeriodePaie::create([
                    'debut' => $currentPeriode->fin->addDay(),
                    'fin' => $currentPeriode->fin->addMonth(),
                    'validee' => false,
                    'reference' => 'PERIODE_' . $currentPeriode->fin->addDay()->format('F_Y')
                ]);

                // Copier les fiches clients avec les données persistantes
                foreach ($currentPeriode->fichesClients as $ficheClient) {
                    FicheClient::create([
                        'client_id' => $ficheClient->client_id,
                        'periode_paie_id' => $newPeriode->id,
                        'notes' => $ficheClient->notes,
                        'nb_bulletins' => $ficheClient->nb_bulletins,
                        'maj_fiche_para' => $ficheClient->maj_fiche_para
                    ]);
                }
            }

            // Sauvegarder le fichier
            $fileName = "business_backup_{$timestamp}.json";
            Storage::disk('local')->put(
                $this->backupPath . '/' . $fileName,
                json_encode($data, JSON_PRETTY_PRINT)
            );

            // Marquer l'ancienne période comme validée
            $currentPeriode->update(['validee' => true]);

            DB::commit();

            return redirect()
                ->route('admin.business-backups.index')
                ->with('success', 'Sauvegarde effectuée et nouvelle période créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.business-backups.index')
                ->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function preview($fileName)
    {
        $path = $this->backupPath . '/' . $fileName;
        
        if (Storage::disk('local')->exists($path)) {
            $content = Storage::disk('local')->get($path);
            $data = json_decode($content, true);
            
            return view('admin.business-backups.preview', compact('data', 'fileName'));
        }

        return redirect()
            ->route('admin.business-backups.index')
            ->with('error', 'Fichier introuvable');
    }

    public function download($fileName, $format = 'json')
    {
        $path = $this->backupPath . '/' . $fileName;
        
        if (!Storage::disk('local')->exists($path)) {
            return redirect()
                ->route('admin.business-backups.index')
                ->with('error', 'Fichier introuvable');
        }

        $content = Storage::disk('local')->get($path);
        $data = json_decode($content, true);

        switch ($format) {
            case 'csv':
                return $this->downloadAsCsv($data, $fileName);
            case 'xlsx':
                return $this->downloadAsExcel($data, $fileName);
            case 'pdf':
                return $this->downloadAsPdf($data, $fileName);
            default:
                return Storage::disk('local')->download($path);
        }
    }

    public function restore(Request $request, $fileName)
    {
        try {
            DB::beginTransaction();

            $path = $this->backupPath . '/' . $fileName;
            $content = Storage::disk('local')->get($path);
            $data = json_decode($content, true);

            if ($request->input('restore_clients', true) && isset($data['clients'])) {
                foreach ($data['clients'] as $clientData) {
                    $filteredData = collect($clientData)
                        ->except(['id', 'gestionnaire', 'responsable'])
                        ->toArray();
                    Client::updateOrCreate(
                        ['id' => $clientData['id']],
                        $filteredData
                    );
                }
            }

            if ($request->input('restore_fiches', true) && isset($data['fiches_clients'])) {
                foreach ($data['fiches_clients'] as $ficheData) {
                    $filteredData = collect($ficheData)
                        ->except(['id', 'client_name', 'periode_paie'])
                        ->toArray();
                    FicheClient::updateOrCreate(
                        ['id' => $ficheData['id']],
                        $filteredData
                    );
                }
            }

            if ($request->input('restore_periodes', true) && isset($data['periodes_paie'])) {
                foreach ($data['periodes_paie'] as $periodeData) {
                    // Vérifier si l'utilisateur a les droits pour modifier une période verrouillée
                    if ($periodeData['validee'] && !auth()->user()->hasRole(['admin', 'responsable'])) {
                        continue; // Sauter cette période si l'utilisateur n'a pas les droits
                    }

                    $filteredData = collect($periodeData)
                        ->except(['id', 'traitements_count'])
                        ->toArray();

                    PeriodePaie::updateOrCreate(
                        ['id' => $periodeData['id']],
                        $filteredData
                    );
                }
            }

            if ($request->input('restore_traitements', true) && isset($data['traitements_paie'])) {
                foreach ($data['traitements_paie'] as $traitementData) {
                    $filteredData = collect($traitementData)
                        ->except(['id', 'client_name', 'periode_paie'])
                        ->toArray();
                    TraitementPaie::updateOrCreate(
                        ['id' => $traitementData['id']],
                        $filteredData
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

    protected function downloadAsPdf($data, $fileName)
    {
        // Formater les données pour le PDF
        $formattedData = [];
        foreach ($data as $type => $items) {
            foreach ($items as $item) {
                if ($type === 'traitements_paie') {
                    $formattedData[$type][] = [
                        'reference' => $item['reference'] ?? '',
                        'client' => $item['client_name'] ?? '',
                        'periode' => $item['periode_paie']['debut'] ?? '',
                        'fichiers' => [
                            'reception_variables' => $item['reception_variables_file'] ?? 'Non',
                            'preparation_bp' => $item['preparation_bp_file'] ?? 'Non',
                            'validation_bp_client' => $item['validation_bp_client_file'] ?? 'Non',
                            'preparation_envoie_dsn' => $item['preparation_envoie_dsn_file'] ?? 'Non',
                            'accuses_dsn' => $item['accuses_dsn_file'] ?? 'Non'
                        ]
                    ];
                } else {
                    $formattedData[$type][] = $item;
                }
            }
        }

        $pdf = PDF::loadView('admin.business-backups.pdf', ['data' => $formattedData]);
        return $pdf->download(str_replace('.json', '.pdf', $fileName));
    }

    protected function downloadAsCsv($data, $fileName)
    {
        $output = fopen('php://temp', 'w');
        
        foreach ($data as $type => $items) {
            // Ajouter un en-tête pour chaque section
            fputcsv($output, [strtoupper(str_replace('_', ' ', $type))]);
            
            if (!empty($items)) {
                // En-têtes des colonnes
                fputcsv($output, array_keys($items[0]));
                
                // Données
                foreach ($items as $item) {
                    $row = array_map(function ($value) {
                        if (is_array($value)) {
                            return json_encode($value);
                        }
                        return $value;
                    }, $item);
                    fputcsv($output, $row);
                }
                
                // Ligne vide entre les sections
                fputcsv($output, []);
            }
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . str_replace('.json', '.csv', $fileName) . '"');
    }

    protected function downloadAsExcel($data, $fileName)
    {
        $spreadsheet = new Spreadsheet();
        $currentRow = 1;
        
        foreach ($data as $type => $items) {
            // Créer une nouvelle feuille pour chaque type de données
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle(substr(str_replace('_', ' ', $type), 0, 31));
            
            // Titre de la section
            $sheet->setCellValue('A' . $currentRow, strtoupper(str_replace('_', ' ', $type)));
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
            $currentRow += 2;
            
            if (!empty($items)) {
                // En-têtes des colonnes
                $columns = array_keys($items[0]);
                foreach ($columns as $colIndex => $header) {
                    $sheet->setCellValueByColumnAndRow($colIndex + 1, $currentRow, strtoupper(str_replace('_', ' ', $header)));
                }
                $sheet->getStyle('A' . $currentRow . ':' . $sheet->getHighestColumn() . $currentRow)->getFont()->setBold(true);
                $currentRow++;
                
                // Données
                foreach ($items as $item) {
                    $colIndex = 1;
                    foreach ($item as $value) {
                        if (is_array($value)) {
                            $value = json_encode($value);
                        }
                        $sheet->setCellValueByColumnAndRow($colIndex, $currentRow, $value);
                        $colIndex++;
                    }
                    $currentRow++;
                }
                
                // Espace entre les sections
                $currentRow += 2;
            }
            
            // Ajuster la largeur des colonnes
            foreach (range('A', $sheet->getHighestColumn()) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        // Créer le fichier Excel
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'excel');
        $writer->save($tempFile);

        return response()->download($tempFile, str_replace('.json', '.xlsx', $fileName))
            ->deleteFileAfterSend(true);
    }
} 