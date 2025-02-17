<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Material;
use App\Models\Ticket;
use App\Models\Post;
use PDF;
use Illuminate\Http\Request;
use App\Exports\MaterialExport;
use App\Services\MaterialService;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class MaterialController extends Controller
{
    protected $materialService;

    public function __construct(MaterialService $materialService)
    {
        $this->materialService = $materialService;
    }

    public function index(Request $request)
    {
        $query = Material::query();

        // Apply filters
        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $materials = $query->with(['client', 'user'])->paginate(10);
        // Fetch all clients for the dropdown
        $clients = Client::all();
        $tickets = Ticket::latest()->take(5)->get(); 
        $posts = Post::latest()->take(5)->get(); 
        $breadcrumbs = [
            ['name' => 'Tout Les Matériaux', 'url' => route('materials.index')],
        ];
        return view('materials.index', compact('materials', 'clients', 'posts','tickets', 'breadcrumbs'));
    }

    public function create()
    {
        $clients = Client::all();
        $breadcrumbs = [
            ['name' => 'Tout Les Matériaux', 'url' => route('materials.index')],
            ['name' => 'Nouveau Matériau', 'url' => route('materials.create')],
        ];
        return view('materials.create', compact('clients', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:autre,document,image',
            'content' => 'nullable|string',
            'content_url' => 'nullable|url',
            'field_name' => 'nullable|string|max:255',
        ]);

        $this->materialService->createMaterial($validated);

        return redirect()->route('materials.index')->with('success', 'Material created successfully.');
    }

    public function show(Material $material)
    {
        $client = $material->client; // Récupérer le client associé au matériel
        $breadcrumbs = [
            ['name' => 'Tout Les Matériaux', 'url' => route('materials.index')],
            ['name' => $material->title, 'url' => route('materials.show', $material->id)],
        ];
        return view('materials.show', compact('material', 'client', 'breadcrumbs'));
    }

    public function edit(Material $material)
    {
        $clients = Client::all();
        $breadcrumbs = [
            ['name' => 'Tout Les Matériaux', 'url' => route('materials.index')],
            ['name' => $material->title, 'url' => route('materials.edit', $material->id)],
        ];  
        return view('materials.edit', compact('material', 'clients', 'breadcrumbs'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:autre,document,image',
            'content' => 'nullable|string',
            'content_url' => 'nullable|url',
            'field_name' => 'nullable|string|max:255',
        ]);

        $this->materialService->updateMaterial($material, $validated);

        return redirect()->route('materials.index')->with('success', 'Material updated successfully.');
    }

    public function destroy(Material $material)
    {
        $this->materialService->deleteMaterial($material);

        return redirect()->route('materials.index')->with('success', 'Material deleted successfully.');
    }

    public function exportExcel($id)
    {
        $material = Material::findOrFail($id);
        return Excel::download(new MaterialExport($material), $material->title . '.xlsx');
    }

    public function exportPDF($id)
    {
        $material = Material::findOrFail($id);
        $pdf = PDF::loadView('materials.pdf', compact('material'));
        return $pdf->download($material->title . '.pdf');
    }
}


