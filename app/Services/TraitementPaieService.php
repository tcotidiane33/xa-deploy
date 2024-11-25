<?php

namespace App\Services;

use App\Models\FicheClient;
use Illuminate\Http\Request;
use App\Models\TraitementPaie;
use Illuminate\Support\Facades\Log;

class TraitementPaieService
{
    public function createTraitementPaie(array $data)
    {
        return TraitementPaie::create($data);
    }

    public function updateTraitementPaie(TraitementPaie $traitementPaie, array $data)
    {
        return $traitementPaie->update($data);
    }

    public function updateFicheClient(FicheClient $ficheClient, array $data)
    {
        $ficheClient->update($data);
        return $ficheClient;
    }

    public function deleteTraitementPaie(TraitementPaie $traitementPaie)
    {
        return $traitementPaie->delete();
    }
}