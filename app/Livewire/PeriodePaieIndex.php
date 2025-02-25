<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\PeriodePaie;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;

class PeriodePaieIndex extends Component
{
    public $periodes;
    public $currentPeriode;
    public $gestionnaires;
    public $isAdminOrResponsable;

    public function mount()
    {
        $this->isAdminOrResponsable = auth()->user()->hasRole(['Admin', 'Responsable']);
        $this->periodes = PeriodePaie::orderBy('debut', 'desc')->get();
        $this->currentPeriode = PeriodePaie::where('validee', false)
            ->whereDate('fin', '>=', now())
            ->first();
            
        if ($this->isAdminOrResponsable) {
            $this->gestionnaires = User::role('Gestionnaire')->get();
        }
    }

    public function render()
    {
        return view('livewire.periode-paie-index', [
            'periodes' => $this->periodes,
            'currentPeriode' => $this->currentPeriode,
            'gestionnaires' => $this->gestionnaires ?? collect(),
        ]);
    }

    public function createNewPeriode()
    {
        return redirect()->route('periodes-paie.create');
    }
} 