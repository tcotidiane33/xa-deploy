<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Traits\TracksUserActions;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use TracksUserActions;

    public function index()
    {
        $tickets = Ticket::with('createur', 'assigneA')->paginate(10); // Paginer par 10 éléments par page
        $posts = Post::latest()->take(5)->get(); 
        return view('tickets.index', compact('tickets','posts'));
    }
    /**
     * Show the form for creating a new resource.
     */


    public function create()
    {
        $users = User::all();
        return view('tickets.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            //  'titre' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priorite' => 'required|in:basse,moyenne,haute',
            'assigne_a_id' => 'nullable|exists:users,id',
        ]);

        $validated['createur_id'] = auth()->id();
        $validated['statut'] = 'ouvert';

        $ticket = Ticket::create($validated);
        //  $this->logAction('create_ticket', "Création du ticket #{$ticket->id}");
        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $users = User::all();
        return view('tickets.edit', compact('ticket', 'users'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            // 'titre' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'statut' => 'required|in:ouvert,en_cours,ferme',
            'priorite' => 'required|in:basse,moyenne,haute',
            'assigne_a_id' => 'nullable|exists:users,id',
        ]);

        $ticket->update($validated);
        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket mis à jour avec succès.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket supprimé avec succès.');
    }
}
