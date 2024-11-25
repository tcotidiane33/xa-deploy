<?php

namespace App\Services;

use App\Models\Material;
use App\Models\MaterialHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialService
{
    public function createMaterial(array $data)
    {
        $material = Material::create($data + ['user_id' => Auth::id()]);

        // Enregistrer l'historique des actions sur le matériau
        $this->logMaterialAction($material, 'created', 'Matériau créé.');
    }

    public function updateMaterial(Material $material, array $data)
    {
        $material->update($data);

        // Enregistrer l'historique des actions sur le matériau
        $this->logMaterialAction($material, 'updated', 'Matériau mis à jour.');
    }

    public function deleteMaterial(Material $material)
    {
        $material->delete();

        // Enregistrer l'historique des actions sur le matériau
        $this->logMaterialAction($material, 'deleted', 'Matériau supprimé.');
    }

    protected function logMaterialAction(Material $material, $action, $details)
    {
        MaterialHistory::create([
            'material_id' => $material->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'details' => $details,
        ]);
    }
}