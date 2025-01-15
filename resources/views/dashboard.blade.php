@extends('layouts.admin')

@section('title', 'Tableau de Bord')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Tableau de Bord</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Utilisateurs -->
            <div class="bg-white rounded-lg shadow p-2 text-center">
                <svg class="w-[54px] h-[54px] text-blue-500 dark:text-blue-300 mx-auto" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="54" height="54" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2.4"
                        d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                </svg>
                <h3 class="text-lg font-semibold mb-2">Total Utilisateurs</h3>
                <p class="text-2xl font-bold">{{ $totalUsers }}</p>
            </div>

            <!-- Total Clients -->
            <div class="bg-white rounded-lg shadow p-2 text-center">
                <svg class="w-[48px] h-[48px] text-blue-500 dark:text-blue-300 mx-auto" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="54" height="54" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2.1"
                        d="M12.1429 11v9m0-9c-2.50543-.7107-3.19099-1.39543-6.13657-1.34968-.48057.00746-.86348.38718-.86348.84968v7.2884c0 .4824.41455.8682.91584.8617 2.77491-.0362 3.45995.6561 6.08421 1.3499m0-9c2.5053-.7107 3.1067-1.39542 6.0523-1.34968.4806.00746.9477.38718.9477.84968v7.2884c0 .4824-.4988.8682-1 .8617-2.775-.0362-3.3758.6561-6 1.3499m2-14c0 1.10457-.8955 2-2 2-1.1046 0-2-.89543-2-2s.8954-2 2-2c1.1045 0 2 .89543 2 2Z" />
                </svg>
                <h3 class="text-lg font-semibold mb-2">Total Clients</h3>
                <p class="text-2xl font-bold">{{ $totalClients }}</p>
            </div>

            <!-- Total Périodes de Paie -->
            <div class="bg-white rounded-lg shadow p-2 text-center">
                <svg class="w-[48px] h-[48px] text-purple-500 dark:text-purple-300 mx-auto" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="54" height="54" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1"
                        d="M17 8H5m12 0a1 1 0 0 1 1 1v2.6M17 8l-4-4M5 8a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.6M5 8l4-4 4 4m6 4h-4a2 2 0 1 0 0 4h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1Z" />
                </svg>
                <h3 class="text-lg font-semibold mb-2">Total Périodes de Paie</h3>
                <p class="text-2xl font-bold">{{ $totalPeriodesPaie }}</p>
            </div>

            <!-- Taux de Réussite -->
            <div class="bg-white rounded-lg shadow p-2 text-center">
                <svg class="w-[48px] h-[48px] text-green-500 dark:text-green-300 mx-auto" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="54" height="54" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1"
                        d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="text-lg font-semibold mb-2">Taux de Réussite</h3>
                <p class="text-2xl font-bold">{{ $successPercentage }}%</p>
            </div>

            <!-- Traitements de Paie en Cours -->
            <div class="bg-white rounded-lg shadow p-2 text-center">
                <svg class="w-[48px] h-[48px] text-yellow-500 dark:text-yellow-300 mx-auto" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="54" height="54" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3M3.22302 14C4.13247 18.008 7.71683 21 12 21c4.9706 0 9-4.0294 9-9 0-4.97056-4.0294-9-9-9-3.72916 0-6.92858 2.26806-8.29409 5.5M7 9H3V5" />
                </svg>
                <h3 class="text-lg font-semibold mb-2">Traitements de Paie en Cours</h3>
                <p class="text-2xl font-bold">{{ $traitementsPaieEnCours }}</p>
            </div>

            <!-- Traitements de Paie Terminés -->
            <div class="bg-white rounded-lg shadow p-2 text-center">
                <svg class="w-[48px] h-[48px] text-green-500 dark:text-green-300 mx-auto" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="54" height="54" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 21a9 9 0 1 1 0-18c1.052 0 2.062.18 3 .512M7 9.577l3.923 3.923 8.5-8.5M17 14v6m-3-3h6" />
                </svg>
                <h3 class="text-lg font-semibold mb-2">Traitements de Paie Terminés</h3>
                <p class="text-2xl font-bold">{{ $traitementsPaieTerminer }}</p>
            </div>

            <!-- Traitements de Paie Interrompus -->
            <div class="bg-white rounded-lg shadow p-2 text-center">
                <svg class="w-[48px] h-[48px] text-red-500 dark:text-red-300 mx-auto" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="54" height="54" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="text-lg font-semibold mb-2">Traitements de Paie Interrompus</h3>
                <p class="text-2xl font-bold">{{ $traitementsPaieInterrompu }}</p>
            </div>

            <!-- Total Tickets -->
            <div class="bg-white rounded-lg shadow p-2 text-center">
                <svg class="w-[48px] h-[48px] text-gray-500 dark:text-gray-300 mx-auto" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="54" height="54" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1"
                        d="M12 2a10 10 0 1 1-10 10A10 10 0 0 1 12 2Zm0 5a5 5 0 1 0 5 5 5 5 0 0 0-5-5Z" />
                </svg>
                <h3 class="text-lg font-semibold mb-2">Total Tickets</h3>
                <p class="text-2xl font-bold">{{ $totalTickets }}</p>
            </div>

            <!-- Tickets Ouverts -->
            <div class="bg-white rounded-lg shadow p-2 text-center">
                <svg class="w-[48px] h-[48px] text-orange-500 dark:text-orange-300 mx-auto" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="54" height="54" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1"
                        d="M12 2a10 10 0 1 1-10 10A10 10 0 0 1 12 2Zm0 5a5 5 0 1 0 5 5 5 5 0 0 0-5-5Z" />
                </svg>
                <h3 class="text-lg font-semibold mb-2">Tickets Ouverts</h3>
                <p class="text-2xl font-bold">{{ $ticketsOuverts }}</p>
            </div>

            <!-- Tickets Fermés -->
            <div class="bg-white rounded-lg shadow p-2 text-center">
                <svg class="w-[48px] h-[48px] text-green-500 dark:text-green-300 mx-auto" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="54" height="54" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1"
                        d="M12 2a10 10 0 1 1-10 10A10 10 0 0 1 12 2Zm0 5a5 5 0 1 0 5 5 5 5 0 0 0-5-5Z" />
                </svg>
                <h3 class="text-lg font-semibold mb-2">Tickets Fermés</h3>
                <p class="text-2xl font-bold">{{ $ticketsFermes }}</p>
            </div>

            <!-- Tickets en Cours -->
            <div class="bg-white rounded-lg shadow p-2 text-center">
                <svg class="w-[48px] h-[48px] text-yellow-500 dark:text-yellow-300 mx-auto" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="54" height="54" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1"
                        d="M12 2a10 10 0 1 1-10 10A10 10 0 0 1 12 2Zm0 5a5 5 0 1 0 5 5 5 5 0 0 0-5-5Z" />
                </svg>
                <h3 class="text-lg font-semibold mb-2">Tickets en Cours</h3>
                <p class="text-2xl font-bold">{{ $ticketsEnCours }}</p>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Traitements de Paie</h3>
                <canvas id="traitementsPaieChart" style="height: 200px;"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Tickets</h3>
                <canvas id="ticketsChart" style="height: 200px;"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Derniers Clients -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Derniers Clients</h3>
                <ul>
                    @foreach ($latestClients as $client)
                        <li class="flex items-center mb-2">
                            <svg class="w-6 h-6 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A3.001 3.001 0 015 15V5a3 3 0 013-3h8a3 3 0 013 3v10a3 3 0 01-.121.804M15 21h-6m6 0a3 3 0 01-6 0m6 0H9" />
                            </svg>
                            <div>
                                <div class="font-bold">{{ $client->name }}</div>
                                <div class="text-sm text-gray-500">{{ $client->email }}</div>
                                <div class="text-sm text-gray-500">{{ $client->created_at->format('d/m/Y') }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Derniers Traitements de Paie -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Derniers Traitements de Paie</h3>
                <ul>
                    @foreach ($recentTraitements as $traitement)
                        <li class="flex items-center mb-2">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 11V7m0 4v4m0-4h4m-4 0H8m4 0a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold">{{ $traitement->client->name }}</div>
                                <div class="text-sm text-gray-500">Référence: {{ $traitement->reference }}</div>
                                <div class="text-sm text-gray-500">Période: {{ $traitement->periodePaie->nom }}</div>
                                <div class="text-sm text-gray-500">Gestionnaire: {{ $traitement->gestionnaire->name }}
                                </div>
                                <div class="text-sm text-gray-500">Statut:
                                    @if ($traitement->est_verrouille)
                                        <span
                                            class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">Verrouillé</span>
                                    @else
                                        <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">Ouvert</span>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Derniers Tickets -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Derniers Tickets</h3>
                <ul>
                    @foreach ($recentTickets as $ticket)
                        <li class="flex items-center mb-2">
                            <svg class="w-6 h-6 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11V7m0 4v4m0-4h4m-4 0H8m4 0a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                            </svg>
                            <div>
                                <div class="font-bold">{{ $ticket->titre }}</div>
                                <div class="text-sm text-gray-500">Créé par: {{ $ticket->createur->name }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- Utilisateurs Connectés -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Utilisateurs Connectés</h3>
                <ul>
                    @foreach ($connectedUsers as $user)
                        <li class="flex items-center mb-4">
                            <!-- Example additional icons -->
                            <svg class="w-6 h-6 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A3.001 3.001 0 015 15V5a3 3 0 013-3h8a3 3 0 013 3v10a3 3 0 01-.121.804M15 21h-6m6 0a3 3 0 01-6 0m6 0H9" />
                            </svg>
                            {{-- <img class="w-10 h-10 rounded-full mr-4" src="{{ $user->avatar }}" alt="{{ $user->name }}"> --}}
                            <div>
                                <div class="font-bold">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->getRoleNames()->implode(', ') }}</div>
                            </div>
                            <div class="ml-auto">
                                @if ($user->is_active)
                                    <svg class="w-6 h-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4">
                    {{ $connectedUsers->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Logs d'Audit Récents -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4">Logs d'Audit Récents</h3>
        <ul>
            @foreach ($recentAudits as $audit)
                <li class="flex items-center justify-between mb-2">
                    <div>
                        <div class="font-bold">{{ $audit->event }}</div>
                        <div class="text-sm text-gray-500">Type: {{ class_basename($audit->auditable_type) }} - ID:
                            {{ $audit->auditable_id }}</div>
                        <div class="text-sm text-gray-500">
                            Utilisateur: {{ $audit->user->name }} - Rôle:
                            {{ $audit->user->getRoleNames()->implode(', ') }}
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">{{ $audit->created_at->format('d/m/Y H:i') }}</div>
                </li>
            @endforeach
        </ul>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const traitementsPaieChartCtx = document.getElementById('traitementsPaieChart').getContext('2d');
        const traitementsPaieChart = new Chart(traitementsPaieChartCtx, {
            type: 'bar',
            data: {
                labels: ['En Cours', 'Terminés', 'Interrompus'],
                datasets: [{
                    label: 'Traitements de Paie',
                    data: [{{ $traitementsPaieEnCours }}, {{ $traitementsPaieTerminer }},
                        {{ $traitementsPaieInterrompu }}
                    ],
                    backgroundColor: ['#4CAF50', '#2196F3', '#FF9800'],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ticketsChartCtx = document.getElementById('ticketsChart').getContext('2d');
        const ticketsChart = new Chart(ticketsChartCtx, {
            type: 'pie',
            data: {
                labels: ['Ouverts', 'Fermés', 'En Cours'],
                datasets: [{
                    label: 'Tickets',
                    data: [{{ $ticketsOuverts }}, {{ $ticketsFermes }}, {{ $ticketsEnCours }}],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
@endsection
