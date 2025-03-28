<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\ClientHistory;
use App\Exports\ClientsExport;
use App\Services\ClientService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ConventionCollective;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\ClientManagerChangeMail;
use App\Models\Ticket;
use App\Models\Post;

use App\Notifications\RelationUpdated;
use App\Mail\ClientAcknowledgementMail;
use App\Notifications\NewClientCreated;

use BayAreaWebPro\MultiStepForms\MultiStepForm;

use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }
    public function index(Request $request)
    {
        $clients = $this->clientService->getClients($request)->paginate(10); // Utilisez la pagination ici
        $clientsCount = Client::count(); // Exemple de données
        $clientGrowthData = $this->clientService->getClientGrowthData();
        $clientGrowthLabels = $clientGrowthData->pluck('year'); // Obtenez les labels de croissance des clients
        $topConventionsData = $this->clientService->getTopConventionsData();
        $topConventionsLabels = $topConventionsData->pluck('convention_collective_id'); // Obtenez les labels des conventions collectives
        $clientsByManagerData = $this->clientService->getClientsByManagerData();
        $clientsByManagerLabels = $clientsByManagerData->pluck('gestionnaire_principal_id'); // Obtenez les labels des gestionnaires principaux
        $clientsStatusData = $this->clientService->getClientsStatusData(); // Ajoutez cette ligne pour récupérer les données de statut des clients
        $tickets = Ticket::latest()->take(5)->get();
        $posts = Post::latest()->take(5)->get();

        // Récupérer les cabinets portefeuilles pour le filtre
        $portfolioCabinets = Client::where('is_cabinet', true)->get();

        return view('clients.index', compact(
            'clients',
            'tickets',
            'posts',
            'clientGrowthData',
            'clientGrowthLabels',
            'topConventionsData',
            'topConventionsLabels',
            'clientsByManagerData',
            'clientsByManagerLabels',
            'portfolioCabinets',
            'clientsCount',
            'clientsStatusData' // Passez la variable à la vue
        ));
    }


    public function create()
    {
        Log::info('Début de la méthode create');
        return view('clients.create');
    }

    public function storePartial(Request $request)
    {
        Log::info("Début du processus de storePartial", ['request' => $request->all()]);
        $result = $this->clientService->storePartial($request);

        if ($result['success']) {
            Log::info("storePartial réussi", ['client_id' => $result['client_id'], 'nextStep' => $result['nextStep']]);
            return response()->json([
                'success' => true,
                'nextStep' => $result['nextStep'],
                'client_id' => $result['client_id'],
            ]);
        } else {
            Log::info("storePartial échoué", ['errors' => $result['errors']]);
            return response()->json([
                'success' => false,
                'errors' => $result['errors'],
            ], 422);
        }
    }

    public function store(StoreClientRequest $request)
    {
        $result = $this->clientService->storeClient($request);

        if ($result['success']) {
            return redirect()->route('clients.index')->with('success', 'Client créé avec succès.');
        } else {
            return redirect()->back()->withErrors('Erreur lors de la création du client.');
        }
    }

    public function update(UpdateClientRequest $request, $id)
    {
        $result = $this->clientService->updateClient($request, $id);

        if ($result['success']) {
            return redirect()->route('clients.index')->with('success', 'Client mis à jour avec succès.');
        } else {
            return redirect()->back()->withErrors('Erreur lors de la mise à jour du client.');
        }
    }

    public function edit(Client $client)
    {
        $users = User::all();
        $conventionCollectives = ConventionCollective::all();
        $clients = Client::all();
        return view('clients.edit', compact('client', 'users', 'conventionCollectives', 'clients'));
    }

    public function show(Client $client)
    {
        $client->load(['responsablePaie', 'gestionnairePrincipal', 'conventionCollective', 'portfolioCabinet']);
        $events = $this->clientService->getClientEvents($client);
        // Assurez-vous que la variable $user est définie
        $user = auth()->user(); // ou $user = User::find($client->user_id); si vous avez une relation utilisateur-client

        return view('clients.show', compact('client', 'events', 'user'));
    }

    public function export()
    {
        return $this->clientService->exportClients();
    }

    public function destroy(Client $client)
    {
        $result = $this->clientService->deleteClient($client);

        if ($result['success']) {
            return redirect()->route('clients.index')->with('success', 'Client supprimé avec succès.');
        } else {
            return redirect()->back()->withErrors('Erreur lors de la suppression du client.');
        }
    }

        public function getInfo($id)
    {
        try {
            // Utilisation du service pour récupérer les informations du client
            $clientInfo = $this->clientService->getClientInfo($id);

            // Vérifiez si le service retourne une réponse JSON ou un tableau
            if ($clientInfo instanceof \Illuminate\Http\JsonResponse) {
                return $clientInfo; // Retourne directement la réponse JSON
            }

            return response()->json($clientInfo); // Si c'est un tableau, renvoyez-le en JSON
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des informations du client : ' . $e->getMessage());
            return response()->json(['error' => 'Erreur interne du serveur'], 500);
        }
    }

    public function transfer(Request $request)
    {
        $result = $this->clientService->transferClients($request);

        if ($result['success']) {
            return redirect()->route('admin.clients.index')->with('success', 'Clients transférés avec succès.');
        } else {
            return redirect()->back()->withErrors('Une erreur est survenue : ' . $result['message']);
        }
    }

    public function validateStep(Request $request, $step)
    {
        $result = $this->clientService->validateStep($request, $step);

        if ($result->passes()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['errors' => $result->errors()], 422);
        }
    }

    public function updatePartial(UpdateClientRequest $request, Client $client, $step)
    {
        $result = $this->clientService->updatePartial($request, $client, $step);

        if ($result['success']) {
            return redirect()->back()->with('success', 'Données mises à jour avec succès.');
        } else {
            return redirect()->back()->withErrors('Erreur lors de la mise à jour du client.');
        }
    }

    public function updateRelation(Request $request, $userId)
    {
        $result = $this->clientService->updateRelation($request, $userId);

        if ($result['success']) {
            return redirect()->back()->with('success', 'Notification envoyée avec succès.');
        } else {
            return redirect()->back()->withErrors('Erreur lors de l\'envoi de la notification.');
        }
    }

    public function attachGestionnaire(Request $request, Client $client)
    {
        $result = $this->clientService->attachGestionnaire($request, $client);

        if ($result['success']) {
            return redirect()->back()->with('success', 'Gestionnaire ajouté avec succès.');
        } else {
            return redirect()->back()->withErrors('Erreur lors de l\'ajout du gestionnaire.');
        }
    }

    public function detachGestionnaire(Request $request, Client $client)
    {
        $result = $this->clientService->detachGestionnaire($request, $client);

        if ($result['success']) {
            return redirect()->back()->with('success', 'Gestionnaire retiré avec succès.');
        } else {
            return redirect()->back()->withErrors('Erreur lors du retrait du gestionnaire.');
        }
    }

}
