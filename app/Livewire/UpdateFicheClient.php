<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\FicheClient;
use Livewire\WithFileUploads;

class UpdateFicheClient extends Component
{
    use WithFileUploads;

    public $ficheClient;
    public $reception_variables;
    public $preparation_bp;
    public $validation_bp_client;
    public $preparation_envoie_dsn;
    public $accuses_dsn;
    public $notes;
    public $reception_variables_file;
    public $preparation_bp_file;
    public $validation_bp_client_file;
    public $preparation_envoie_dsn_file;
    public $accuses_dsn_file;
    public $nb_bulletins_file;
    public $maj_fiche_para_file;

    protected $rules = [
        'reception_variables' => 'nullable|date',
        'preparation_bp' => 'nullable|date',
        'validation_bp_client' => 'nullable|date',
        'preparation_envoie_dsn' => 'nullable|date',
        'accuses_dsn' => 'nullable|date',
        'notes' => 'nullable|string',
        'reception_variables_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'preparation_bp_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'validation_bp_client_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'preparation_envoie_dsn_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'accuses_dsn_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'nb_bulletins_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'maj_fiche_para_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ];

    public function mount(FicheClient $ficheClient)
    {
        $this->ficheClient = $ficheClient;
        $this->reception_variables = $ficheClient->reception_variables;
        $this->preparation_bp = $ficheClient->preparation_bp;
        $this->validation_bp_client = $ficheClient->validation_bp_client;
        $this->preparation_envoie_dsn = $ficheClient->preparation_envoie_dsn;
        $this->accuses_dsn = $ficheClient->accuses_dsn;
        $this->notes = $ficheClient->notes;
    }

    public function updateFicheClient()
    {
        $this->validate();

        $this->ficheClient->update([
            'reception_variables' => $this->reception_variables,
            'preparation_bp' => $this->preparation_bp,
            'validation_bp_client' => $this->validation_bp_client,
            'preparation_envoie_dsn' => $this->preparation_envoie_dsn,
            'accuses_dsn' => $this->accuses_dsn,
            'notes' => $this->notes,
        ]);

        if ($this->reception_variables_file) {
            $this->ficheClient->reception_variables_file = $this->reception_variables_file->store('files');
        }
        if ($this->preparation_bp_file) {
            $this->ficheClient->preparation_bp_file = $this->preparation_bp_file->store('files');
        }
        if ($this->validation_bp_client_file) {
            $this->ficheClient->validation_bp_client_file = $this->validation_bp_client_file->store('files');
        }
        if ($this->preparation_envoie_dsn_file) {
            $this->ficheClient->preparation_envoie_dsn_file = $this->preparation_envoie_dsn_file->store('files');
        }
        if ($this->accuses_dsn_file) {
            $this->ficheClient->accuses_dsn_file = $this->accuses_dsn_file->store('files');
        }
        if ($this->nb_bulletins_file) {
            $this->ficheClient->nb_bulletins_file = $this->nb_bulletins_file->store('files');
        }
        if ($this->maj_fiche_para_file) {
            $this->ficheClient->maj_fiche_para_file = $this->maj_fiche_para_file->store('files');
        }

        $this->ficheClient->save();

        session()->flash('success', 'Fiche client mise à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.update-fiche-client');
    }
}
