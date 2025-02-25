<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Client;
use Livewire\Component;
use App\Models\PeriodePaie;
use App\Models\TraitementPaie;
use Illuminate\Support\Facades\Auth;
use App\Models\FicheClient;
use App\Services\PeriodePaieService;
use Livewire\WithPagination;
use App\Models\PeriodePaieHistory;
use App\Models\User;

class PeriodePaieForm extends Component
{
    use WithPagination;
    
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'migrateAllClients' => 'migrateAllClients',
        'migrateSelectedClients' => 'migrateSelectedClients'
    ];
    
    public $refreshInterval = 60000; // 60 secondes en millisecondes
    
    public $currentStep = 1;
    public $totalSteps = 3; // Mise à jour du nombre total d'étapes
    public $reference, $debut, $fin, $periodePaieId, $clientId; // Ajout de la propriété clientId
    public $isAdminOrResponsable;
    public $isGestionnaire;
    public $periodesPaieNonCloturees;
    public $clients;

    // Propriétés pour les champs de date
    public $reception_variables, $preparation_bp, $validation_bp_client, $preparation_envoie_dsn, $accuses_dsn, $notes;


    // Propriétés pour les champs de date
    public $isReceptionVariablesSubmitted = false;
    public $isPreparationBpSubmitted = false;
    public $isValidationBpClientSubmitted = false;
    public $isPreparationEnvoieDsnSubmitted = false;
    public $isAccusesDsnSubmitted = false;

    public $historique;
    protected $periodePaieService;

    public $selectedPeriode;
    public $selectedGestionnaire;
    public $selectedClient;
    public $periodes;
    public $gestionnaires;
    public $eventTimeline;

    public $editingField = null;
    public $editingFicheClientId = null;
    public $showEditModal = false;
    public $editingFiche = null;

    public function mount()
    {
        $this->periodePaieService = app(PeriodePaieService::class);
        $this->isAdminOrResponsable = Auth::user()->hasAnyRole(['Admin', 'Responsable']);
        $this->isGestionnaire = Auth::user()->hasRole('Gestionnaire');
        $this->periodesPaieNonCloturees = PeriodePaie::getNonCloturees();
        $this->clients = Auth::user()->clientsAsGestionnaire;
        $this->loadHistorique();
        
        $this->dispatch('startAutoRefresh', ['interval' => $this->refreshInterval]);
        
        // Initialiser les périodes
        $this->periodes = PeriodePaie::orderBy('debut', 'desc')->get();
        
        // Sélectionner la période en cours par défaut
        $this->selectedPeriode = PeriodePaie::where('validee', false)
            ->whereDate('fin', '>=', now())
            ->first()?->id;
            
        // Charger les gestionnaires si admin/responsable
        if ($this->isAdminOrResponsable) {
            $this->gestionnaires = User::role('Gestionnaire')->get();
        }
        
        // Initialiser le calendrier des événements
        $this->initializeEventTimeline();
    }

    public function render()
    {
        $fichesClients = collect();

        if ($this->selectedPeriode) {
            $query = FicheClient::with(['client.gestionnairePrincipal'])
                ->where('periode_paie_id', $this->selectedPeriode);

            // Filtrer par gestionnaire si l'utilisateur n'est pas admin ou responsable
            if (!$this->isAdminOrResponsable) {
                $query->whereHas('client.gestionnaires', function($q) {
                    $q->where('user_id', auth()->id());
                });
            }

            $fichesClients = $query->get();
        }

        return view('livewire.periode-paie-form', [
            'fichesClients' => $fichesClients,
            'periodes' => PeriodePaie::orderBy('debut', 'desc')->get()
        ]);
    }

    private function isDateInPeriod($date, $periode)
    {
        if (!$date) return null;
        $date = Carbon::parse($date);
        return [
            'date' => $date->format('Y-m-d'),
            'valid' => $date->between($periode->debut, $periode->fin)
        ];
    }

    private function loadHistorique()
    {
        $this->historique = PeriodePaieHistory::with(['user', 'periodePaie'])
            ->latest()
            ->take(50)
            ->get()
            ->map(function ($history) {
                return [
                    'timestamp' => $history->created_at->format('Y-m-d H:i:s'),
                    'user' => $history->user->name,
                    'action' => $history->action,
                    'details' => $history->details,
                    'periode' => $history->periodePaie->reference
                ];
            });
    }

    public function submitForm()
    {
        $this->validate([
            'debut' => 'required|date',
            'fin' => 'required|date|after_or_equal:debut',
        ]);

        // Générer la référence
        $reference = $this->generateUniqueReference();

        // Enregistrer les données du formulaire
        PeriodePaie::create([
            'reference' => $reference,
            'debut' => $this->debut,
            'fin' => $this->fin,
            'validee' => false,
            // 'client_id' => 0, // Indique que cela concerne tous les clients
        ]);

        session()->flash('message', 'Période de paie créée avec succès.');

        return redirect()->route('periodes-paie.index');
    }

    public function cloturerPeriode($id)
    {
        $periode = PeriodePaie::find($id);
        $periode->validee = 1;
        $periode->save();

        session()->flash('message', 'Période de paie clôturée avec succès.');
    }

    public function decloturerPeriode($id)
    {
        $periode = PeriodePaie::find($id);
        $periode->validee = 0;
        $periode->save();

        session()->flash('message', 'Période de paie rouverte avec succès.');
    }

    private function generateUniqueReference()
    {
        $reference = 'PERIODE_' . strtoupper(Carbon::parse($this->debut)->format('F_Y'));
        $existingReference = PeriodePaie::where('reference', $reference)->exists();

        if ($existingReference) {
            $reference .= '_' . strtoupper(Carbon::now()->format('His'));
        }

        return $reference;
    }

    public function nextStep()
    {
        if ($this->currentStep == 1) {
            $this->validate([
                'periodePaieId' => 'required|exists:periodes_paie,id',
            ]);
        } elseif ($this->currentStep == 2) {
            $this->validate([
                'clientId' => 'required|exists:clients,id',
            ]);
        }

        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function saveVariables()
    {
        $this->validate([
            'reception_variables' => 'required|date',
            'preparation_bp' => 'nullable|date',
            'validation_bp_client' => 'nullable|date',
            'preparation_envoie_dsn' => 'nullable|date',
            'accuses_dsn' => 'nullable|date',
            'notes' => 'nullable|string',
        ], [
            'reception_variables.after_or_equal' => 'La date de réception des variables doit être après ou égale à la date de début de la période.',
            'reception_variables.before_or_equal' => 'La date de réception des variables doit être avant ou égale à la date de fin de la période.',
            'preparation_bp.after_or_equal' => 'La date de préparation BP doit être après ou égale à la date de réception des variables.',
            'preparation_bp.before_or_equal' => 'La date de préparation BP doit être avant ou égale à la date de fin de la période.',
            'validation_bp_client.after_or_equal' => 'La date de validation BP client doit être après ou égale à la date de préparation BP.',
            'validation_bp_client.before_or_equal' => 'La date de validation BP client doit être avant ou égale à la date de fin de la période.',
            'preparation_envoie_dsn.after_or_equal' => 'La date de préparation et envoi DSN doit être après ou égale à la date de validation BP client.',
            'preparation_envoie_dsn.before_or_equal' => 'La date de préparation et envoi DSN doit être avant ou égale à la date de fin de la période.',
            'accuses_dsn.after_or_equal' => 'La date des accusés DSN doit être après ou égale à la date de préparation et envoi DSN.',
            'accuses_dsn.before_or_equal' => 'La date des accusés DSN doit être avant ou égale à la date de fin de la période.',
        ]);

            // Ajouter des messages de débogage
        \Log::info('Client ID: ' . $this->clientId);
        \Log::info('Période Paie ID: ' . $this->periodePaieId);
        \Log::info('Réception Variables: ' . $this->reception_variables);
        \Log::info('Préparation BP: ' . $this->preparation_bp);
        \Log::info('Validation BP Client: ' . $this->validation_bp_client);
        \Log::info('Préparation Envoie DSN: ' . $this->preparation_envoie_dsn);
        \Log::info('Accusés DSN: ' . $this->accuses_dsn);
        \Log::info('Notes: ' . $this->notes);

        // Set flags based on submission for field availability
        $this->isReceptionVariablesSubmitted = !empty($this->reception_variables);
        $this->isPreparationBpSubmitted = !empty($this->preparation_bp);
        $this->isValidationBpClientSubmitted = !empty($this->validation_bp_client);
        $this->isPreparationEnvoieDsnSubmitted = !empty($this->preparation_envoie_dsn);
        $this->isAccusesDsnSubmitted = !empty($this->accuses_dsn);

        TraitementPaie::create([
            'client_id' => $this->clientId,
            'periode_paie_id' => $this->periodePaieId,
            'reception_variables' => $this->reception_variables,
            'preparation_bp' => $this->preparation_bp,
            'validation_bp_client' => $this->validation_bp_client,
            'preparation_envoie_dsn' => $this->preparation_envoie_dsn,
            'accuses_dsn' => $this->accuses_dsn,
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'Variables enregistrées avec succès.');
        return redirect()->route('periodes-paie.index');
    }

    // Utility function to check if the date is more than 3 days ago
    // public function isDateExceeded($date)
    // {
    //     if (!$date) return false;

    //     return \Carbon\Carbon::parse($date)->diffInDays(now()) > 3;
    // }
    public function isDateExceeded($date)
    {
        if (!$date) return false;
        return Carbon::parse($date)->addDays(3)->isPast();
    }

    public function migrateAllClients()
    {
        try {
            $clients = Client::all();
            $this->periodePaieService->migrateClients($clients);
            
            session()->flash('message', 'Migration complète effectuée avec succès.');
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la migration complète : ' . $e->getMessage());
        }
    }

    public function migrateSelectedClients()
    {
        try {
            $clients = Client::where('date_fin_prestation', '>', now())->get();
            
            if ($clients->isEmpty()) {
                session()->flash('error', 'Aucun client actif trouvé pour la migration.');
                return;
            }

            $this->periodePaieService->migrateClients($clients);
            
            session()->flash('message', 'Migration des clients actifs effectuée avec succès.');
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la migration des clients actifs : ' . $e->getMessage());
        }
    }

    private function initializeEventTimeline()
    {
        $currentPeriode = PeriodePaie::find($this->selectedPeriode);
        if (!$currentPeriode) return;

        $this->eventTimeline = [
            [
                'date' => $currentPeriode->fin->subDays(3)->format('d/m/Y'),
                'description' => 'Date limite réception variables',
                'color' => 'bg-yellow-400'
            ],
            [
                'date' => $currentPeriode->fin->subDays(2)->format('d/m/Y'),
                'description' => 'Date limite préparation BP',
                'color' => 'bg-blue-400'
            ],
            [
                'date' => $currentPeriode->fin->subDays(1)->format('d/m/Y'),
                'description' => 'Date limite validation BP client',
                'color' => 'bg-green-400'
            ],
            [
                'date' => $currentPeriode->fin->addDays(3)->format('d/m/Y'),
                'description' => 'Date limite préparation et envoi DSN',
                'color' => 'bg-purple-400'
            ],
            [
                'date' => $currentPeriode->fin->addDays(5)->format('d/m/Y'),
                'description' => 'Date limite accusés DSN',
                'color' => 'bg-red-400'
            ]
        ];
    }

    public function updatedSelectedPeriode()
    {
        $this->initializeEventTimeline();
    }

    public function editField($ficheClientId, $fieldName)
    {
        // Vérifier si le champ précédent est rempli
        $ficheClient = FicheClient::find($ficheClientId);
        
        $fields = [
            'reception_variables',
            'preparation_bp',
            'validation_bp_client',
            'preparation_envoie_dsn',
            'accuses_dsn'
        ];
        
        $currentIndex = array_search($fieldName, $fields);
        if ($currentIndex > 0) {
            $previousField = $fields[$currentIndex - 1];
            if (empty($ficheClient->$previousField)) {
                session()->flash('error', 'Veuillez d\'abord remplir le champ précédent.');
                return;
            }
        }
        
        $this->editingField = $fieldName;
        $this->editingFicheClientId = $ficheClientId;
        $this->dispatch('openEditModal');
    }

    public function getStatusColor($fiche)
    {
        if ($fiche->accuses_dsn) {
            return 'bg-green-100 text-green-800';
        }
        if ($this->isAnyDateExceeded($fiche)) {
            return 'bg-red-100 text-red-800';
        }
        if ($this->hasInProgressSteps($fiche)) {
            return 'bg-yellow-100 text-yellow-800';
        }
        return 'bg-gray-100 text-gray-800';
    }

    public function getStatus($fiche)
    {
        if ($fiche->accuses_dsn) {
            return 'Complété';
        }
        if ($this->isAnyDateExceeded($fiche)) {
            return 'En retard';
        }
        if ($this->hasInProgressSteps($fiche)) {
            return 'En cours';
        }
        return 'À démarrer';
    }

    public function getDateStatusColor($date)
    {
        if (!$date) {
            return 'text-gray-500';
        }
        if ($date->addDays(3)->isPast()) {
            return 'text-red-600';
        }
        return 'text-green-600';
    }

    private function isAnyDateExceeded($fiche)
    {
        $fields = ['reception_variables', 'preparation_bp', 'validation_bp_client', 'preparation_envoie_dsn', 'accuses_dsn'];
        foreach ($fields as $field) {
            if ($fiche->$field && $fiche->$field->addDays(3)->isPast()) {
                return true;
            }
        }
        return false;
    }

    private function hasInProgressSteps($fiche)
    {
        return $fiche->reception_variables && !$fiche->accuses_dsn;
    }

    public function getNextDeadline($fiche)
    {
        $steps = [
            ['field' => 'reception_variables', 'label' => 'Réception variables'],
            ['field' => 'preparation_bp', 'label' => 'Préparation BP'],
            ['field' => 'validation_bp_client', 'label' => 'Validation BP client'],
            ['field' => 'preparation_envoie_dsn', 'label' => 'Préparation et envoi DSN'],
            ['field' => 'accuses_dsn', 'label' => 'Accusés DSN']
        ];

        foreach ($steps as $step) {
            if (!$fiche->{$step['field']}) {
                return [
                    'label' => $step['label'],
                    'date' => $this->calculateNextDeadline($fiche, $step['field'])
                ];
            }
        }

        return null;
    }

    private function calculateNextDeadline($fiche, $field)
    {
        // Logique de calcul des dates d'échéance basée sur les règles métier
        $baseDate = now();
        
        switch ($field) {
            case 'reception_variables':
                return $baseDate->copy()->addDays(5);
            case 'preparation_bp':
                return $fiche->reception_variables ? $fiche->reception_variables->copy()->addDays(3) : null;
            case 'validation_bp_client':
                return $fiche->preparation_bp ? $fiche->preparation_bp->copy()->addDays(2) : null;
            case 'preparation_envoie_dsn':
                return $fiche->validation_bp_client ? $fiche->validation_bp_client->copy()->addDays(2) : null;
            case 'accuses_dsn':
                return $fiche->preparation_envoie_dsn ? $fiche->preparation_envoie_dsn->copy()->addDays(1) : null;
            default:
                return null;
        }
    }

    // Méthodes pour gérer le modal
    public function editFicheClient($ficheId)
    {
        $this->editingFicheClientId = $ficheId;
        $this->editingFiche = FicheClient::find($ficheId)->toArray();
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingFiche = null;
        $this->editingFicheClientId = null;
    }

    public function updateFicheClient()
    {
        $this->validate([
            'editingFiche.reception_variables' => 'nullable|date',
            'editingFiche.preparation_bp' => 'nullable|date',
            'editingFiche.validation_bp_client' => 'nullable|date',
            'editingFiche.preparation_envoie_dsn' => 'nullable|date',
            'editingFiche.accuses_dsn' => 'nullable|date',
            'editingFiche.notes' => 'nullable|string'
        ]);

        $fiche = FicheClient::find($this->editingFicheClientId);
        $fiche->update($this->editingFiche);

        $this->closeEditModal();
        session()->flash('message', 'Fiche mise à jour avec succès.');
    }

    public function canEditField($field)
    {
        if (!$this->editingFiche) return false;

        $fields = [
            'reception_variables',
            'preparation_bp',
            'validation_bp_client',
            'preparation_envoie_dsn',
            'accuses_dsn'
        ];

        $currentIndex = array_search($field, $fields);
        if ($currentIndex === 0) return true;

        $previousField = $fields[$currentIndex - 1];
        return !empty($this->editingFiche[$previousField]);
    }
}
