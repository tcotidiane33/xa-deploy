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

    public function index()
    {
        $periodes = PeriodePaie::orderBy('debut', 'desc')->get();
        
        $currentPeriode = PeriodePaie::where('validee', false)
            ->whereDate('fin', '>=', now())
            ->first();

        // Calcul de la progression pour chaque période
        $periodes = $periodes->map(function ($periode) {
            $periode->progression = $this->calculateProgression($periode);
            return $periode;
        });

        // Récupérer tous les clients
        $clients = Client::with('gestionnairePrincipal')->get();
        
        // Récupérer tous les gestionnaires
        $gestionnaires = User::role('Gestionnaire')->get();

        // Récupérer les clients avec leurs échéances
        $clientsWithDeadlines = Client::with(['fichesClients' => function($query) use ($currentPeriode) {
            if ($currentPeriode) {
                $query->where('periode_paie_id', $currentPeriode->id);
            }
        }])->get()->map(function($client) {
            $client->fiche_client = $client->fichesClients->first();
            return $client;
        });

        // Préparer les échéances globales
        $globalDeadlines = $this->prepareGlobalDeadlines($clientsWithDeadlines);

        // Préparer le récapitulatif détaillé
        $detailedRecap = $currentPeriode ? $this->prepareDetailedRecap($currentPeriode) : collect();

        // Récupérer l'historique
        $historique = PeriodePaieHistory::with(['user', 'periodePaie'])
            ->latest()
            ->take(50)
            ->get();

        return view('periodes_paie.index', compact(
            'periodes',
            'currentPeriode',
            'clientsWithDeadlines',
            'globalDeadlines',
            'detailedRecap',
            'clients',
            'gestionnaires',
            'historique'
        ));
    }

    private function calculateProgression(PeriodePaie $periode)
    {
        $totalSteps = 5; // Nombre total d'étapes
        $completedSteps = 0;
        
        $fichesClients = $periode->fichesClients;
        
        if (!$fichesClients->count()) {
            return 0;
        }

        foreach ($fichesClients as $fiche) {
            if ($fiche->reception_variables) $completedSteps++;
            if ($fiche->preparation_bp) $completedSteps++;
            if ($fiche->validation_bp_client) $completedSteps++;
            if ($fiche->preparation_envoie_dsn) $completedSteps++;
            if ($fiche->accuses_dsn) $completedSteps++;
        }

        $totalPossibleSteps = $fichesClients->count() * $totalSteps;
        return $totalPossibleSteps > 0 ? round(($completedSteps / $totalPossibleSteps) * 100) : 0;
    }

    private function prepareGlobalDeadlines($clients)
    {
        $deadlines = [];
        
        foreach($clients as $client) {
            if($client->fiche_client) {
                $dates = [
                    ['date' => $client->fiche_client->reception_variables_deadline, 'label' => 'Réception variables', 'color' => 'bg-yellow-400'],
                    ['date' => $client->fiche_client->preparation_bp_deadline, 'label' => 'Préparation BP', 'color' => 'bg-blue-400'],
                    ['date' => $client->fiche_client->validation_bp_deadline, 'label' => 'Validation BP', 'color' => 'bg-green-400'],
                    ['date' => $client->fiche_client->preparation_dsn_deadline, 'label' => 'Envoi DSN', 'color' => 'bg-purple-400'],
                    ['date' => $client->fiche_client->accuses_dsn_deadline, 'label' => 'Accusés DSN', 'color' => 'bg-red-400']
                ];

                foreach($dates as $dateInfo) {
                    if($dateInfo['date']) {
                        $key = $dateInfo['date']->format('Y-m-d') . '-' . $dateInfo['label'];
                        if(!isset($deadlines[$key])) {
                            $deadlines[$key] = [
                                'date' => $dateInfo['date'],
                                'label' => $dateInfo['label'],
                                'color' => $dateInfo['color'],
                                'clients' => 0
                            ];
                        }
                        $deadlines[$key]['clients']++;
                    }
                }
            }
        }

        return collect($deadlines)->sortBy('date')->values();
    }

    private function prepareDetailedRecap($currentPeriode)
    {
        return FicheClient::where('periode_paie_id', $currentPeriode->id)
            ->with(['client', 'gestionnaire'])
            ->get()
            ->map(function($fiche) {
                $fiche->progression = $this->calculateProgress($fiche);
                $fiche->statut = $this->determineStatus($fiche);
                $fiche->prochaine_echeance = $this->getNextDeadline($fiche);
                $fiche->statut_color = $this->getStatusColor($fiche->statut);
                return $fiche;
            });
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'Complété' => 'bg-green-100 text-green-800',
            'En retard' => 'bg-red-100 text-red-800',
            'En cours' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    private function determineStatus($fiche)
    {
        if ($fiche->accuses_dsn) {
            return 'Complété';
        }

        $lastFilledDate = collect([
            $fiche->reception_variables,
            $fiche->preparation_bp,
            $fiche->validation_bp_client,
            $fiche->preparation_envoie_dsn
        ])->filter()->last();

        if ($lastFilledDate && Carbon::parse($lastFilledDate)->addDays(3)->isPast()) {
            return 'En retard';
        }

        return 'En cours';
    }

    private function getNextDeadline($fiche)
    {
        $steps = [
            ['field' => 'reception_variables', 'label' => 'Réception variables'],
            ['field' => 'preparation_bp', 'label' => 'Préparation BP'],
            ['field' => 'validation_bp_client', 'label' => 'Validation BP'],
            ['field' => 'preparation_envoie_dsn', 'label' => 'Envoi DSN'],
            ['field' => 'accuses_dsn', 'label' => 'Accusés DSN']
        ];

        foreach ($steps as $step) {
            if (empty($fiche->{$step['field']})) {
                return [
                    'date' => null,
                    'label' => $step['label']
                ];
            }
        }

        return null;
    }

    public function create()
    {
        $clients = Client::with('gestionnairePrincipal')->get();
        return view('periodes_paie.create', compact('clients'));
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
        // Vérification des autorisations
        if ($periodePaie->validee && !auth()->user()->hasRole(['Admin', 'Responsable'])) {
            return redirect()->route('periodes-paie.index')
                ->with('error', 'Vous n\'avez pas l\'autorisation de modifier une période validée.');
        }

        // Récupération des clients pour le formulaire
        $clients = Client::with('gestionnairePrincipal')->get();

        return view('periodes_paie.edit', compact('periodePaie', 'clients'));
    }

    public function update(Request $request, PeriodePaie $periodePaie)
    {
        // Vérification des autorisations
        if ($periodePaie->validee && !auth()->user()->hasRole(['Admin', 'Responsable'])) {
            return redirect()->route('periodes-paie.index')
                ->with('error', 'Vous n\'avez pas l\'autorisation de modifier une période validée.');
        }

        // Validation des données
        $validated = $request->validate([
            'reference' => 'required|string|max:255|unique:periodes_paie,reference,' . $periodePaie->id,
            'debut' => 'required|date',
            'fin' => 'required|date|after:debut',
        ]);

        try {
            // Mise à jour de la période
            $periodePaie->update($validated);

            // Log de l'action
            activity()
                ->performedOn($periodePaie)
                ->causedBy(auth()->user())
                ->log('Période de paie mise à jour');

            return redirect()->route('periodes-paie.index')
                ->with('success', 'Période de paie mise à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de la période de paie.');
        }
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

    public function migrate($id)
    {
        try {
            $currentPeriode = PeriodePaie::findOrFail($id);
            
            if (!Auth::user()->hasRole(['admin', 'responsable'])) {
                return redirect()
                    ->route('periodes-paie.index')
                    ->with('error', 'Seuls les administrateurs et responsables peuvent effectuer la migration.');
            }

            $newPeriode = $this->periodePaieService->migrateToPeriode($currentPeriode);

            return redirect()
                ->route('periodes-paie.index')
                ->with('success', 'Migration effectuée avec succès vers la période ' . $newPeriode->reference);
        } catch (\Exception $e) {
            return redirect()
                ->route('periodes-paie.index')
                ->with('error', 'Erreur lors de la migration : ' . $e->getMessage());
        }
    }
}