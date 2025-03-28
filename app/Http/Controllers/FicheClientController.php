<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\Post;
use App\Models\FicheClient;
use App\Models\PeriodePaie;
use Illuminate\Http\Request;
use App\Exports\FicheClientExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreFicheClientRequest;
use App\Http\Requests\UpdateFicheClientRequest;

class FicheClientController extends Controller
{
    public function index(Request $request)
    {
        $query = FicheClient::query();

        // Appliquer les filtres
        if ($request->has('client_id') && $request->client_id != '') {
            $query->where('client_id', $request->client_id);
        }
        if ($request->has('periode_paie_id') && $request->periode_paie_id != '') {
            $query->where('periode_paie_id', $request->periode_paie_id);
        }

        $fichesClients = $query->with('client.gestionnairePrincipal')->paginate(15);
        $clients = Client::all();
        $periodesPaie = PeriodePaie::all();
        $ficheClient = new FicheClient(); // Ajoutez cette ligne
        $tickets = Ticket::latest()->take(5)->get();
        $posts = Post::latest()->take(5)->get();
        $breadcrumbs = [
            // ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Fiches Clients', 'url' => route('fiches-clients.index')],
        ];

        return view('clients.fiches_clients.index', compact('fichesClients', 'clients', 'tickets', 'posts', 'periodesPaie', 'breadcrumbs', 'ficheClient'));
    }


    public function create()
    {
        $clients = Client::all(); // Récupère tous les clients
        $periodesPaie = PeriodePaie::where('validee', false)->get(); // Récupère uniquement les périodes non clôturées
        $breadcrumbs = [
            ['name' => 'Fiches Clients', 'url' => route('fiches-clients.index')],
            ['name' => 'Créer une Fiche Client', 'url' => route('fiches-clients.create')],
        ];
        return view('clients.fiches_clients.create', compact('clients', 'periodesPaie', 'breadcrumbs'));
    }
        public function store(StoreFicheClientRequest $request)
        {
            try {
                // Log des données reçues pour le débogage
                \Log::info('Données reçues :', $request->all());

                // Valider les données
                $validated = $request->validated();

                // Créer la fiche client avec toutes les données
                $ficheClient = FicheClient::create([
                    'client_id' => $validated['client_id'],
                    'periode_paie_id' => $validated['periode_paie_id'],
                    'reception_variables' => $validated['reception_variables'] ?? null,
                    'preparation_bp' => $validated['preparation_bp'] ?? null,
                    'validation_bp_client' => $validated['validation_bp_client'] ?? null,
                    'preparation_envoie_dsn' => $validated['preparation_envoie_dsn'] ?? null,
                    'accuses_dsn' => $validated['accuses_dsn'] ?? null,
                    'notes' => $validated['notes'] ?? null
                ]);

                // Log de confirmation
                \Log::info('Fiche client créée :', ['id' => $ficheClient->id]);

                return redirect()
                    ->route('fiches-clients.index')
                    ->with('success', 'Fiche client créée avec succès.');
            } catch (\Exception $e) {
                // Log de l'erreur
                \Log::error('Erreur lors de la création de la fiche client :', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Erreur lors de la création de la fiche client : ' . $e->getMessage());
            }
        }

    public function edit(FicheClient $fiches_client)
    {
        $clients = Client::all();
        $periodesPaie = PeriodePaie::all();
        $breadcrumbs = [
            ['name' => 'Fiches Clients', 'url' => route('fiches-clients.index')],
            ['name' => 'Edit Fiches Clients', 'url' => route('fiches-clients.edit')],
        ];
        return view('clients.fiches_clients.edit', compact('fiches_client', 'clients', 'periodesPaie', 'breadcrumbs'));
    }
    public function update(UpdateFicheClientRequest $request, FicheClient $fiches_client)
    {
        try {
            $validated = $request->validated();
            \Log::info('Validated Data:', $validated);

            if (!empty($validated['notes'])) {
                $newNotes = $fiches_client->notes . "\n" . now()->format('Y-m-d') . ': ' . $validated['notes'];
                $validated['notes'] = $newNotes;
            }

            $fiches_client->update($validated);

            return redirect()
                ->route('fiches-clients.index')
                ->with('success', 'Informations mises à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $ficheClient = FicheClient::find($id);

        if (!$ficheClient) {
            return redirect()->route('fiches-clients.index')->with('error', 'Fiche client introuvable.');
        }

        return response()->json($ficheClient);
    }

    public function destroy(FicheClient $fiches_client)
    {
        $fiches_client->delete();
        return redirect()->route('fiches-clients.index')->with('success', 'Fiche client supprimée avec succès.');
    }

    public function exportExcel(Request $request)
    {
        $query = FicheClient::query();

        // Appliquer les filtres
        if ($request->has('client_id') && $request->client_id != '') {
            $query->where('client_id', $request->client_id);
        }
        if ($request->has('periode_paie_id') && $request->periode_paie_id != '') {
            $query->where('periode_paie_id', $request->periode_paie_id);
        }

        $fichesClients = $query->get();

        return Excel::download(new FicheClientExport($fichesClients), 'fiches_clients.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $query = FicheClient::query();

        // Appliquer les filtres
        if ($request->has('client_id') && $request->client_id != '') {
            $query->where('client_id', $request->client_id);
        }
        if ($request->has('periode_paie_id') && $request->periode_paie_id != '') {
            $query->where('periode_paie_id', $request->periode_paie_id);
        }

        $fichesClients = $query->get();
        $pdf = PDF::loadView('clients.fiches_clients.pdf', compact('fichesClients'));
        return $pdf->download('fiches_clients.pdf');
    }

        public function migrateToNewPeriod(Request $request)
    {
        try {
            Log::info('Début de la migration manuelle des fiches clients.');

            // Valider la période cible
            $request->validate([
                'periode_paie_id' => 'required|exists:periodes_paie,id',
            ]);

            // Récupérer la période cible
            $targetPeriod = PeriodePaie::findOrFail($request->periode_paie_id);

            // Vérifier que la période cible est active
            if ($targetPeriod->validee) {
                return redirect()
                    ->route('fiches-clients.index')
                    ->with('error', 'La période de paie sélectionnée est déjà clôturée.');
            }

            // Récupérer tous les clients qui n'ont pas encore de fiche pour cette période
            $clientsWithoutFiche = Client::whereNotIn('id', function ($query) use ($targetPeriod) {
                $query->select('client_id')
                    ->from('fiches_clients')
                    ->where('periode_paie_id', $targetPeriod->id);
            })->get();

            if ($clientsWithoutFiche->isEmpty()) {
                return redirect()
                    ->route('fiches-clients.index')
                    ->with('info', 'Tous les clients ont déjà une fiche pour cette période.');
            }

            // Créer les nouvelles fiches clients
            $count = 0;
            foreach ($clientsWithoutFiche as $client) {
                FicheClient::create([
                    'client_id' => $client->id,
                    'periode_paie_id' => $targetPeriod->id,
                    'notes' => "Fiche créée automatiquement lors de la migration manuelle le " . now()->format('d/m/Y H:i')
                ]);
                $count++;

                // Notifier pour chaque client migré
                Log::info("Client {$client->name} migré vers la période {$targetPeriod->reference}");
            }

            // Journal des actions
            Log::info('Migration manuelle terminée', [
                'periode' => $targetPeriod->reference,
                'nombre_clients' => $count
            ]);

            // Notification de succès avec le nombre de clients migrés
            return redirect()
                ->route('fiches-clients.index')
                ->with('success', "Migration réussie : {$count} client(s) migré(s) vers la période {$targetPeriod->reference}");

        } catch (\Exception $e) {
            Log::error('Erreur lors de la migration manuelle :', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('fiches-clients.index')
                ->with('error', 'Erreur lors de la migration : ' . $e->getMessage());
        }
    }
    // public function migrateToNewPeriod(Request $request)
    // {
    //     Log::info('Début de la migration des fiches clients.');

    //     $request->validate([
    //         'periode_paie_id' => 'required|exists:periodes_paie,id',
    //     ]);

    //     $currentPeriod = PeriodePaie::find($request->periode_paie_id);
    //     Log::info('Période de paie sélectionnée : ', ['periode_paie_id' => $request->periode_paie_id]);

    //     if (!$currentPeriod) {
    //         Log::error('Période de paie sélectionnée non trouvée.');
    //         return redirect()->route('fiches-clients.index')->with('error', 'Période de paie sélectionnée non trouvée.');
    //     }

    //     $previousPeriod = PeriodePaie::where('validee', true)->latest()->first();
    //     Log::info('Dernière période de paie clôturée : ', ['periode_paie_id' => $previousPeriod->id ?? 'Aucune']);

    //     if (!$previousPeriod) {
    //         Log::error('Aucune période de paie précédente trouvée.');
    //         return redirect()->route('fiches-clients.index')->with('error', 'Aucune période de paie précédente trouvée.');
    //     }

    //     $previousFichesClients = $previousPeriod->fichesClients;
    //     Log::info('Nombre de fiches clients à migrer : ', ['count' => $previousFichesClients->count()]);

    //     foreach ($previousFichesClients as $previousFicheClient) {
    //         Log::info('Migration de la fiche client : ', ['fiche_client_id' => $previousFicheClient->id]);

    //         $previousFicheClient->update([
    //             'periode_paie_id' => $currentPeriod->id,
    //             'reception_variables' => null,
    //             'reception_variables_file' => null,
    //             'preparation_bp' => null,
    //             'preparation_bp_file' => null,
    //             'validation_bp_client' => null,
    //             'validation_bp_client_file' => null,
    //             'preparation_envoie_dsn' => null,
    //             'preparation_envoie_dsn_file' => null,
    //             'accuses_dsn' => null,
    //             'accuses_dsn_file' => null,
    //         ]);

    //         Log::info('Fiche client migrée avec succès : ', ['fiche_client_id' => $previousFicheClient->id]);
    //     }

    //     Log::info('Fin de la migration des fiches clients.');

    //     return redirect()->route('fiches-clients.index')->with('success', 'Toutes les fiches clients ont été migrées vers la nouvelle période de paie.');
    // }
}
