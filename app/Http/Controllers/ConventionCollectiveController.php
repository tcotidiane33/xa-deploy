<?php

namespace App\Http\Controllers;

use App\Models\ConventionCollective;
use Illuminate\Http\Request;

class ConventionCollectiveController extends Controller
{
    public function index()
    {
        $conventionCollectives = ConventionCollective::paginate(10);
        $breadcrumbs = [
            ['name' => 'Tout Les Conventions Collectives', 'url' => route('convention-collectives.index')],
        ];
        return view('convention-collectives.index', compact('conventionCollectives', 'breadcrumbs'));
    }
    
    public function create()
    {
        $breadcrumbs = [
            ['name' => 'Tout Les Conventions Collectives', 'url' => route('convention-collectives.index')],
            ['name' => 'Nouvelle Convention Collective', 'url' => route('convention-collectives.create')],
        ];
        return view('convention-collectives.create', compact('breadcrumbs'));
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'idcc' => 'required|digits:4|unique:convention_collective,idcc',
            'name' => 'required|max:255|unique:convention_collective,name',
            'description' => 'nullable',
        ]);
    
        ConventionCollective::create($validatedData);
    
        return redirect()->route('convention-collectives.index')->with('success', 'Convention collective créée avec succès.');
    }
    
    public function show(ConventionCollective $conventionCollective)
    {
        $breadcrumbs = [
            ['name' => 'Tout Les Conventions Collectives', 'url' => route('convention-collectives.index')],
            ['name' => $conventionCollective->name, 'url' => route('convention-collectives.show', $conventionCollective->id)],
        ];
        return view('convention-collectives.show', compact('conventionCollective', 'breadcrumbs'));
    }
    
    public function edit(ConventionCollective $conventionCollective)
    {
        $breadcrumbs = [
            ['name' => 'Tout Les Conventions Collectives', 'url' => route('convention-collectives.index')],
            ['name' => $conventionCollective->name, 'url' => route('convention-collectives.edit', $conventionCollective->id)],
        ];
            return view('convention-collectives.edit', compact('conventionCollective', 'breadcrumbs'));
    }
    
    public function update(Request $request, ConventionCollective $conventionCollective)
    {
        $validatedData = $request->validate([
            'idcc' => 'required|digits:4|unique:convention_collective,idcc,' . $conventionCollective->id,
            'name' => 'required|max:255|unique:convention_collective,name,' . $conventionCollective->id,
            'description' => 'nullable',
        ]);
    
        $conventionCollective->update($validatedData);
    
        return redirect()->route('convention-collectives.index')->with('success', 'Convention collective mise à jour avec succès.');
    }
    
    public function destroy(ConventionCollective $conventionCollective)
    {
        $conventionCollective->delete();
    
        return redirect()->route('convention-collectives.index')->with('success', 'Convention collective supprimée avec succès.');
    }
}