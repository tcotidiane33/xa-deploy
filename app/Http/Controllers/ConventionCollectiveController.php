<?php

namespace App\Http\Controllers;

use App\Models\ConventionCollective;
use Illuminate\Http\Request;

class ConventionCollectiveController extends Controller
{
    public function index()
    {
        $conventionCollectives = ConventionCollective::all();
        return view('convention-collectives.index', compact('conventionCollectives'));
    }
    
    public function create()
    {
        return view('convention-collectives.create');
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
        return view('convention-collectives.show', compact('conventionCollective'));
    }
    
    public function edit(ConventionCollective $conventionCollective)
    {
        return view('convention-collectives.edit', compact('conventionCollective'));
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