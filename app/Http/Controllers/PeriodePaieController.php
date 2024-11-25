<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\FicheClient;
use App\Models\PeriodePaie;
use Illuminate\Http\Request;
use App\Models\TraitementPaie;
use App\Models\PeriodePaieHistory;
use Illuminate\Support\Facades\Log;
use App\Services\PeriodePaieService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PeriodePaie\StorePeriodePaieRequest;
use App\Http\Requests\PeriodePaie\UpdatePeriodePaieRequest;

class PeriodePaieController extends Controller
{
    protected $periodePaieService;

    public function __construct(PeriodePaieService $periodePaieService)
    {
        $this->periodePaieService = $periodePaieService;
    }

    public function index(Request $request)
    {
        $query = PeriodePaie::query();
    
        // Filtre par client
        if ($request->has('client_id') && $request->client_id) {
            $query->whereHas('fichesClients', function ($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }
    
        // Filtre par gestionnaire
        if ($request->has('gestionnaire_id') && $request->gestionnaire_id) {
            $query->whereHas('fichesClients.client.gestionnairePrincipal', function ($q) use ($request) {
                $q->where('id', $request->gestionnaire_id);
            });
        }
    
        // Filtre par date de début
        if ($request->has('date_debut') && !empty($request->date_debut)) {
            $query->where('debut', '>=', $request->date_debut);
        }
    
        // Filtre par date de fin
        if ($request->has('date_fin') && !empty($request->date_fin)) {
            $query->where('fin', '<=', $request->date_fin);
        }
    
        // Filtre par statut (validée ou non)
        if ($request->has('validee') && $request->validee !== '') {
            $query->where('validee', $request->validee);
        }
    
        // Filtre par mois courant
        if (!$request->has('date_debut') && !$request->has('date_fin')) {
            $query->whereMonth('debut', now()->month);
        }
    
        $periodesPaie = $query->paginate(15);
        // $periodesPaie = PeriodePaie::paginate(15);

        $clients = Client::all();
        $gestionnaires = User::role('gestionnaire')->get();
        // $fichesClients = FicheClient::paginate(15);
        $fichesClients = FicheClient::with('client.gestionnairePrincipal')->paginate(15);
        $ficheClient = new FicheClient(); // Ajoutez cette ligne
    
        $currentPeriodePaie = PeriodePaie::where('validee', false)->latest()->first();
    
        return view('periodes_paie.index', compact('periodesPaie','ficheClient', 'clients', 'gestionnaires', 'currentPeriodePaie', 'fichesClients'));
    }

    public function create()
    {
        Log::info('Début de la méthode create');
        return view('periodes_paie.create');
    }

    public function store(StorePeriodePaieRequest $request)
    {
        $validated = $request->validated();

        // Vérifier qu'il n'y a qu'une seule période de paie active par mois
        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');
        $existingPeriode = PeriodePaie::whereMonth('debut', $currentMonth)
            ->whereYear('debut', $currentYear)
            ->where('validee', true)
            ->first();

        if ($existingPeriode) {
            return redirect()->route('periodes-paie.index')->with('error', 'Il existe déjà une période de paie active pour ce mois.');
        }

        $this->periodePaieService->createPeriodePaie($validated);

        return redirect()->route('periodes-paie.index')->with('success', 'Période de paie créée avec succès.');
    }

    public function show(PeriodePaie $periodePaie)
    {
        return response()->json($periodePaie);
    }
    // public function show(PeriodePaie $periodePaie)
    // {
    //     // Déchiffrer les données de la période de paie
    //     $periodePaie->decrypted_data = $periodePaie->decryptedData;

    //     // Récupérer les traitements de paie associés à cette période
    //     $traitementsPaie = TraitementPaie::where('periode_paie_id', $periodePaie->id)->get();

    //     return view('periodes_paie.show', compact('periodePaie', 'traitementsPaie'));
    // }

    public function edit(PeriodePaie $periodePaie)
    {
        if ($periodePaie->validee && !Auth::user()->hasRole(['admin', 'responsable'])) {
            return redirect()->route('periodes-paie.index')->with('error', 'Vous n\'avez pas l\'autorisation de modifier une période validée.');
        }

        return view('periodes_paie.edit', compact('periodePaie'));
    }

    public function update(UpdatePeriodePaieRequest $request, PeriodePaie $periodePaie)
    {
        if ($periodePaie->validee && !Auth::user()->hasRole(['admin', 'responsable'])) {
            return redirect()->route('periodes-paie.index')->with('error', 'Vous n\'avez pas l\'autorisation de modifier une période validée.');
        }

        $validated = $request->validated();
        $periodePaie->update($validated);

        return redirect()->route('periodes-paie.index')->with('success', 'Période de paie mise à jour avec succès.');
    }
    public function updateFicheClient(Request $request, FicheClient $ficheClient)
    {
        $validated = $request->validate([
            'reception_variables' => 'nullable|date',
            'preparation_bp' => 'nullable|date',
            'validation_bp_client' => 'nullable|date',
            'preparation_envoie_dsn' => 'nullable|date',
            'accuses_dsn' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $this->periodePaieService->updateFicheClient($ficheClient, $validated);

        return redirect()->route('periodes-paie.index')->with('success', 'Fiche client mise à jour avec succès.');
    }

    public function destroy(PeriodePaie $periodePaie)
    {
        if (!Auth::user()->hasRole('Admin')) {
            return redirect()->route('periodes-paie.index')->with('error', 'Vous n\'avez pas l\'autorisation de supprimer une période de paie.');
        }

        try {
            $periodePaie->delete();
            return redirect()->route('periodes-paie.index')->with('success', 'Période de paie supprimée avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression de la période de paie : ' . $e->getMessage());
            return redirect()->route('periodes-paie.index')->with('error', 'Impossible de supprimer cette période de paie. ' . $e->getMessage());
        }
    }

    public function cloturer($id)
    {
        $periodePaie = PeriodePaie::findOrFail($id);
        $this->periodePaieService->closePeriodePaie($periodePaie);

        return redirect()->route('periodes-paie.index')->with('success', 'Période de paie clôturée avec succès.');
    }

    public function decloturer($id)
    {
        $periodePaie = PeriodePaie::findOrFail($id);
        $this->periodePaieService->openPeriodePaie($periodePaie);

        return redirect()->route('periodes-paie.index')->with('success', 'Période de paie déclôturée avec succès.');
    }
}