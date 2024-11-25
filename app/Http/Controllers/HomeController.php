<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\TraitementPaie;
use App\Models\PeriodePaie;
use App\Models\Ticket;
use App\Models\Post;
use App\Models\User;
use OwenIt\Auditing\Models\Audit; // Ajoutez cette ligne
use Carbon\Carbon;


class HomeController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalClients = Client::count();
        $totalPeriodesPaie = PeriodePaie::count();
        $tickets = Ticket::latest()->take(5)->get();
        $posts = Post::latest()->take(5)->get();


        // Calculez le taux de réussite (remplacez ceci par votre propre logique)
        $successPercentage = 72;

        $traitementsPaieEnCours = TraitementPaie::whereHas('periodePaie', function ($query) {
            $query->where('validee', false);
        })->count();

        $traitementsPaieTerminer = TraitementPaie::whereHas('periodePaie', function ($query) {
            $query->where('validee', true);
        })->count();

        $traitementsPaieInterrompu = TraitementPaie::whereHas('periodePaie', function ($query) {
            $query->whereNull('validee');
        })->count();

        $latestClients = Client::latest()->take(5)->get();
        $recentTraitements = TraitementPaie::with('client')->latest()->take(5)->get();
        $recentTickets = Ticket::with('createur')->latest()->take(5)->get();

        // Récupérer les 10 clients les plus récents
        $recentClients = Client::latest()->take(10)->get();

        // Calculer les indicateurs de performance
        $totalTickets = Ticket::count();
        $ticketsOuverts = Ticket::where('statut', 'ouvert')->count();
        $ticketsFermes = Ticket::where('statut', 'fermé')->count();
        $ticketsEnCours = Ticket::where('statut', 'en cours')->count();

        // Récupérer les utilisateurs connectés
        $connectedUsers = User::where('is_active', true)->get();

        // Récupérer les logs d'audit récents
        $recentAudits = \OwenIt\Auditing\Models\Audit::latest()->take(10)->get();


        return view('dashboard', compact(
            'totalUsers',
            'totalClients',
            'totalPeriodesPaie',
            'successPercentage',
            'traitementsPaieEnCours',
            'traitementsPaieTerminer',
            'traitementsPaieInterrompu',
            'latestClients',
            'recentTraitements',
            'recentTickets',
            'recentClients',
            'totalTickets',
            'ticketsOuverts',
            'ticketsFermes',
            'ticketsEnCours',
            'connectedUsers',
            'recentAudits',
            'tickets',
            'posts'

        ));
    }
}