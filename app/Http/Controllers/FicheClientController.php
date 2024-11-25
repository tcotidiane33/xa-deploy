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
    
        return view('clients.fiches_clients.index', compact('fichesClients', 'clients','tickets','posts', 'periodesPaie', 'ficheClient'));
    }

    public function create()
    {
        $clients = Client::all();
        $periodesPaie = PeriodePaie::all();
        return view('clients.fiches_clients.create', compact('clients', 'periodesPaie'));
    }

    public function store(StoreFicheClientRequest $request)
    {
        $validated = $request->validated();
        \Log::info('Validated Data:', $validated); // Ajoutez cette ligne pour vérifier les données validées

        FicheClient::create($validated);

        return redirect()->route('fiches-clients.index')->with('success', 'Fiche client créée avec succès.');
    }

    public function edit(FicheClient $fiches_client)
    {
        $clients = Client::all();
        $periodesPaie = PeriodePaie::all();
        return view('clients.fiches_clients.edit', compact('fiches_client', 'clients', 'periodesPaie'));
    }

    public function update(UpdateFicheClientRequest $request, FicheClient $fiches_client)
    {
        $validated = $request->validated();
        \Log::info('Validated Data:', $validated); // Ajoutez cette ligne pour vérifier les données validées

        if (!empty($validated['notes'])) {
            $newNotes = $fiches_client->notes . "\n" . now()->format('Y-m-d') . ': ' . $validated['notes'];
            $validated['notes'] = $newNotes;
        }

        $fiches_client->update($validated);

        return redirect()->route('fiches-clients.index')->with('success', 'Informations mises à jour avec succès.');
    }
    public function show(FicheClient $ficheClient)
    {
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
    Log::info('Début de la migration des fiches clients.');

    $request->validate([
        'periode_paie_id' => 'required|exists:periodes_paie,id',
    ]);

    $currentPeriod = PeriodePaie::find($request->periode_paie_id);
    Log::info('Période de paie sélectionnée : ', ['periode_paie_id' => $request->periode_paie_id]);

    if (!$currentPeriod) {
        Log::error('Période de paie sélectionnée non trouvée.');
        return redirect()->route('fiches-clients.index')->with('error', 'Période de paie sélectionnée non trouvée.');
    }

    $previousPeriod = PeriodePaie::where('validee', true)->latest()->first();
    Log::info('Dernière période de paie clôturée : ', ['periode_paie_id' => $previousPeriod->id ?? 'Aucune']);

    if (!$previousPeriod) {
        Log::error('Aucune période de paie précédente trouvée.');
        return redirect()->route('fiches-clients.index')->with('error', 'Aucune période de paie précédente trouvée.');
    }

    $previousFichesClients = $previousPeriod->fichesClients;
    Log::info('Nombre de fiches clients à migrer : ', ['count' => $previousFichesClients->count()]);

    foreach ($previousFichesClients as $previousFicheClient) {
        Log::info('Migration de la fiche client : ', ['fiche_client_id' => $previousFicheClient->id]);

        $previousFicheClient->update([
            'periode_paie_id' => $currentPeriod->id,
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
        ]);

        Log::info('Fiche client migrée avec succès : ', ['fiche_client_id' => $previousFicheClient->id]);
    }

    Log::info('Fin de la migration des fiches clients.');

    return redirect()->route('fiches-clients.index')->with('success', 'Toutes les fiches clients ont été migrées vers la nouvelle période de paie.');
}
}