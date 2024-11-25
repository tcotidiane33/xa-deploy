<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Client;
use Livewire\Component;
use App\Models\FicheClient;
use App\Models\PeriodePaie;
use Livewire\WithFileUploads;
use App\Models\TraitementPaie;
use Illuminate\Support\Facades\Auth;

class TraitementPaieForm extends Component
{
    use WithFileUploads;

    public $traitementPaieId;
    public $clients;
    public $gestionnaires;
    public $periodesPaie;
    public $client_id;
    public $gestionnaire_id;
    public $periode_paie_id;
    public $reception_variables_file;
    public $preparation_bp_file;
    public $validation_bp_client_file;
    public $preparation_envoie_dsn_file;
    public $accuses_dsn_file;
    public $nb_bulletins_file;
    public $maj_fiche_para_file;

    public function mount($traitementPaieId = null)
    {
        $this->clients = Client::all();
        $this->gestionnaires = User::role('gestionnaire')->get();
        $this->periodesPaie = PeriodePaie::getNonCloturees();

        if ($traitementPaieId) {
            $traitementPaie = TraitementPaie::findOrFail($traitementPaieId);
            $this->traitementPaieId = $traitementPaie->id;
            $this->client_id = $traitementPaie->client_id;
            $this->gestionnaire_id = $traitementPaie->gestionnaire_id;
            $this->periode_paie_id = $traitementPaie->periode_paie_id;
            $this->nbr_bull = $traitementPaie->nbr_bull;
            $this->teledec_urssaf = $traitementPaie->teledec_urssaf;
            $this->reception_variables = $traitementPaie->reception_variables;
            $this->preparation_bp = $traitementPaie->preparation_bp;
            $this->validation_bp_client = $traitementPaie->validation_bp_client;
            $this->preparation_envoie_dsn = $traitementPaie->preparation_envoie_dsn;
            $this->accuses_dsn = $traitementPaie->accuses_dsn;
            $this->notes = $traitementPaie->notes;
        }
    }

    public function updatedClientId($value)
    {
        $this->loadFicheClient();
    }

    public function updatedPeriodePaieId($value)
    {
        $this->loadFicheClient();
    }

    private function loadFicheClient()
    {
        if ($this->client_id && $this->periode_paie_id) {
            $ficheClient = FicheClient::where('client_id', $this->client_id)
                ->where('periode_paie_id', $this->periode_paie_id)
                ->first();

            if ($ficheClient) {
                $this->reception_variables = $ficheClient->reception_variables;
                $this->preparation_bp = $ficheClient->preparation_bp;
                $this->validation_bp_client = $ficheClient->validation_bp_client;
                $this->preparation_envoie_dsn = $ficheClient->preparation_envoie_dsn;
                $this->accuses_dsn = $ficheClient->accuses_dsn;
                $this->notes = $ficheClient->notes;
            }
        }
    }



    public function render()
    {
        return view('livewire.traitement-paie-form');
    }

    public function save()
    {
        $validatedData = $this->validate([
            'client_id' => 'required|exists:clients,id',
            'gestionnaire_id' => 'required|exists:users,id',
            'periode_paie_id' => 'required|exists:periodes_paie,id',
            'reception_variables_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'preparation_bp_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'validation_bp_client_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'preparation_envoie_dsn_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'accuses_dsn_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'nb_bulletins_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'maj_fiche_para_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
        ]);

        // Vérifier si le gestionnaire connecté est rattaché au client
        $gestionnaire = Auth::user();
        $client = Client::findOrFail($this->client_id);

        if ($client->gestionnaire_principal_id !== $gestionnaire->id) {
            session()->flash('error', 'Vous n\'êtes pas autorisé à modifier les informations de ce client.');
            return;
        }

        // Mettre à jour la fiche client
        $ficheClient = FicheClient::where('client_id', $this->client_id)
            ->where('periode_paie_id', $this->periode_paie_id)
            ->first();

        if ($ficheClient) {
            $fileFields = [
                'reception_variables_file',
                'preparation_bp_file',
                'validation_bp_client_file',
                'preparation_envoie_dsn_file',
                'accuses_dsn_file',
                'nb_bulletins_file',
                'maj_fiche_para_file'
            ];

            foreach ($fileFields as $field) {
                if ($this->$field) {
                    $path = $this->$field->store('fiches_clients');
                    if ($path) {
                        $ficheClient->$field = $path;
                    } else {
                        session()->flash('error', 'Erreur lors du téléchargement du fichier ' . $field);
                        return;
                    }
                }
            }

            $ficheClient->save();
        } else {
            session()->flash('error', 'Fiche client introuvable.');
            return;
        }

        session()->flash('message', 'Fichiers de la fiche client enregistrés avec succès.');

        return redirect()->route('traitements-paie.index');
    }
}