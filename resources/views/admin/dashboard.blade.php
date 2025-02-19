@extends('layouts.admin')

@section('title', 'Tableau de Bord Administrateur')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- En-tête avec résumé -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tableau de Bord Administrateur</h1>
        <div class="flex space-x-4">
            <select id="periodeFilter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1">
                <option value="today">Aujourd'hui</option>
                <option value="week">Cette semaine</option>
                <option value="month" selected>Ce mois</option>
                <option value="year">Cette année</option>
            </select>
            <button onclick="refreshStats()" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                <i class="fas fa-sync-alt mr-2"></i>Actualiser
            </button>
        </div>
    </div>

    <!-- Cartes statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Carte Rôles -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6">
            <div class="block items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-200 bg-opacity-30 rounded-full mr-4">
                        <i class="fas fa-user-tag text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-white text-lg font-semibold">Rôles</h3>
                        <p class="text-3xl font-bold text-white">{{ $totalRoles }}</p>
                    </div>
                </div>
                <div class="text-white text-sm">
                    <span class="flex items-center">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +{{ rand(1, 5) }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Carte Permissions -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6">
            <div class="block items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3 bg-green-200 bg-opacity-30 rounded-full mr-4">
                        <i class="fas fa-key text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-white text-lg font-semibold">Permissions</h3>
                        <p class="text-3xl font-bold text-white">{{ $totalPermissions }}</p>
                    </div>
                </div>
                <div class="text-white text-sm">
                    <span class="flex items-center">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +{{ rand(1, 5) }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Total Utilisateurs -->
        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="mr-4">
                <svg class="w-12 h-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A3.001 3.001 0 0 1 5 15V5a3 3 0 0 1 3-3h8a3 3 0 0 1 3 3v10a3 3 0 0 1-.121.804M15 21h-6m6 0a3 3 0 0 1-6 0m6 0H9" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2">Total Utilisateurs</h3>
                <p class="text-2xl font-bold">{{ $totalUsers }}</p>
            </div>
        </div>

        <!-- Total Clients -->
        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="mr-4">
                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.104-.896 2-2 2s-2-.896-2-2 .896-2 2-2 2 .896 2 2 2 2-.896 2-2zM12 11c0 1.104.896 2 2 2s2-.896 2-2-.896-2-2-2-2 .896-2 2zM12 11v10m0-10c0-1.104-.896-2-2-2s-2 .896-2 2 .896 2 2 2 2-.896 2-2zM12 11c0-1.104.896-2 2-2s2 .896 2 2-.896 2-2 2-2-.896-2-2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2">Total Clients</h3>
                <p class="text-2xl font-bold">{{ $totalClients }}</p>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Graphique des utilisateurs -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Évolution des Utilisateurs</h3>
            <canvas id="usersChart" height="300"></canvas>
        </div>

        <!-- Graphique des clients -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Répartition des Clients</h3>
            <canvas id="clientsChart" height="300"></canvas>
        </div>
    </div>

    <!-- Activités récentes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Liste des utilisateurs récents -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Utilisateurs Récents</h3>
                <a href="#" class="text-indigo-600 hover:text-indigo-800">Voir tout</a>
            </div>
            <div class="space-y-4">
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                            <span class="text-indigo-600 font-semibold">{{ substr($user->name, 0, 2) }}</span>
                        </div>
                        <div>
                            <p class="font-semibold">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Liste des clients récents -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Clients Récents</h3>
                <a href="#" class="text-indigo-600 hover:text-indigo-800">Voir tout</a>
            </div>
            <div class="space-y-4">
                @foreach($recentClients as $client)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                            <span class="text-green-600 font-semibold">{{ substr($client->name, 0, 2) }}</span>
                        </div>
                        <div>
                            <p class="font-semibold">{{ $client->name }}</p>
                            <p class="text-sm text-gray-500">{{ $client->email }}</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">{{ $client->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des utilisateurs
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    new Chart(usersCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            datasets: [{
                label: 'Nouveaux utilisateurs',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: 'rgb(79, 70, 229)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Graphique des clients
    const clientsCtx = document.getElementById('clientsChart').getContext('2d');
    new Chart(clientsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Actifs', 'Inactifs', 'En attente'],
            datasets: [{
                data: [65, 20, 15],
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(239, 68, 68)',
                    'rgb(234, 179, 8)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});

function refreshStats() {
    // Fonction pour actualiser les statistiques
    const periode = document.getElementById('periodeFilter').value;
    // Ajoutez ici la logique pour actualiser les données
}
</script>
@endpush
@endsection
