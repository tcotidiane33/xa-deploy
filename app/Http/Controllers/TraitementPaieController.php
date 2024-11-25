<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\Post;
use App\Models\FicheClient;
use App\Models\PeriodePaie;
use Illuminate\Http\Request;
use App\Models\TraitementPaie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\TraitementPaieService;
use App\Http\Requests\TraitementPaieRequest;

class TraitementPaieController extends Controller
{
    protected $traitementPaieService;

    public function __construct(TraitementPaieService $traitementPaieService)
    {
        $this->traitementPaieService = $traitementPaieService;
    }

    // public function index(Request $request)
    // {
    //     $traitements = TraitementPaie::with(['client', 'gestionnaire', 'periodePaie'])->paginate(15);
    //     // / Récupérer la période de paie en cours
    //     $currentPeriodePaie = PeriodePaie::where('validee', false)->latest()->first();

    //     // Récupérer toutes les fiches clients associées à la période de paie en cours
    //     $fichesClients = FicheClient::where('periode_paie_id', $currentPeriodePaie->id)
    //                                 ->with(['client', 'periodePaie'])
    //                                 ->paginate(15);

    //     return view('traitements_paie.index', compact('fichesClients', 'currentPeriodePaie','traitements'));
    // }
    public function index()
    {
        $periodePaieEnCours = PeriodePaie::where('validee', false)->first();
        $fichesClients = FicheClient::where('periode_paie_id', $periodePaieEnCours->id)->paginate(15);
        $tickets = Ticket::latest()->take(5)->get(); 
    $posts = Post::latest()->take(5)->get(); 

        return view('traitements_paie.index', compact('fichesClients', 'posts','tickets'));
    }
    public function create()
    {
        $gestionnaire = Auth::user();
        $clients = Client::where('gestionnaire_principal_id', $gestionnaire->id)->with('fichesClients')->get();
        $gestionnaires = User::role('gestionnaire')->get();
        $periodesPaie = PeriodePaie::all();
        return view('traitements_paie.create', compact('clients', 'gestionnaires', 'periodesPaie'));
    }

    public function store(TraitementPaieRequest $request)
    {
        $validatedData = $request->validated();

        // Vérifier si le gestionnaire connecté est rattaché au client
        $gestionnaire = Auth::user();
        $client = Client::findOrFail($validatedData['client_id']);

        if ($client->gestionnaire_principal_id !== $gestionnaire->id) {
            return redirect()->route('traitements-paie.create')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier les informations de ce client.');
        }

        // Vérifier l'unicité de la période de paie
        $existingTraitement = TraitementPaie::where('client_id', $validatedData['client_id'])
            ->where('periode_paie_id', $validatedData['periode_paie_id'])
            ->first();

        if ($existingTraitement) {
            return redirect()->route('traitements-paie.create')
                ->with('error', 'Un traitement de paie pour ce client et cette période existe déjà.');
        }

        $this->traitementPaieService->createTraitementPaie($validatedData);

        return redirect()->route('traitements-paie.index')
            ->with('success', 'Traitement de paie créé avec succès.');
    }

    public function show(TraitementPaie $traitementPaie)
    {
        return view('traitements_paie.show', compact('traitementPaie'));
    }

    public function edit(TraitementPaie $traitementPaie)
    {
        $gestionnaire = Auth::user();
        $clients = Client::where('gestionnaire_principal_id', $gestionnaire->id)->with('fichesClients')->get();
        $gestionnaires = User::role('gestionnaire')->get();
        $periodesPaie = PeriodePaie::all();
        return view('traitements_paie.edit', compact('traitementPaie', 'clients', 'gestionnaires', 'periodesPaie'));
    }


    public function update(TraitementPaieRequest $request, TraitementPaie $traitementPaie)
    {
        $validatedData = $request->validated();

        // Vérifier si le gestionnaire connecté est rattaché au client
        $gestionnaire = Auth::user();
        $client = Client::findOrFail($validatedData['client_id']);

        if ($client->gestionnaire_principal_id !== $gestionnaire->id) {
            return redirect()->route('traitements-paie.edit', $traitementPaie)
                ->with('error', 'Vous n\'êtes pas autorisé à modifier les informations de ce client.');
        }

        $this->traitementPaieService->updateTraitementPaie($traitementPaie, $validatedData);

        return redirect()->route('traitements-paie.index')
            ->with('success', 'Traitement de paie mis à jour avec succès.');
    }

    public function updateFicheClient(Request $request, FicheClient $ficheClient)
    {
        Log::info('Début de la mise à jour de la fiche client.', ['fiche_client_id' => $ficheClient->id]);

        $validated = $request->validate([
            'reception_variables_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'preparation_bp_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'validation_bp_client_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'preparation_envoie_dsn_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'accuses_dsn_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'nb_bulletins_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'maj_fiche_para_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        Log::info('Données validées.', $validated);

        $this->traitementPaieService->updateFicheClient($ficheClient, $validated);

        return redirect()->route('traitements-paie.index')->with('success', 'Fiche client mise à jour avec succès.');
    }
    public function destroy(TraitementPaie $traitementPaie)
    {
        $this->traitementPaieService->deleteTraitementPaie($traitementPaie);

        return redirect()->route('traitements-paie.index')
            ->with('success', 'Traitement de paie supprimé avec succès.');
    }
}