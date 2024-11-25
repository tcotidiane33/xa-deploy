<?php

namespace App\Exports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MaterialExport implements FromCollection, WithHeadings, WithMapping
{
    protected $material;

    public function __construct(Material $material)
    {
        $this->material = $material;
    }

    public function collection()
    {
        return collect([$this->material]);
    }

    public function headings(): array
    {
        return [
            'Client',
            'Titre',
            'Type',
            'URL du contenu',
            'Nom du champ',
            'Créé le',
            'Mis à jour le'
        ];
    }

    public function map($material): array
    {
        return [
            $material->client->name,
            $material->title,
            $material->type,
            $material->content_url,
            $material->field_name,
            $material->created_at->format('Y-m-d H:i:s'),
            $material->updated_at->format('Y-m-d H:i:s')
        ];
    }
}