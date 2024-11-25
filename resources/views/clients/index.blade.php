@extends('layouts.admin')

@section('title', 'Liste des Clients')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <div class="row">
                <form action="{{ route('clients.index') }}" method="GET" class="mb-4">
                    <div class="grid grid-cols-6 md:grid-cols-6 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                            <select name="status" id="status" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="">Tous</option>
                                <option value="actif" {{ request('status') == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" {{ request('status') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                            </select>
                        </div>
                        <div>
                            <label for="is_cabinet" class="block text-sm font-medium text-gray-700">Client Cabinet</label>
                            <select name="is_cabinet" id="is_cabinet" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="">Tous</option>
                                <option value="1" {{ request('is_cabinet') == '1' ? 'selected' : '' }}>Oui</option>
                                <option value="0" {{ request('is_cabinet') == '0' ? 'selected' : '' }}>Non</option>
                            </select>
                        </div>
                        <div>
                            <label for="portfolio_cabinet_id" class="block text-sm font-medium text-gray-700">Cabinet Portefeuille</label>
                            <select name="portfolio_cabinet_id" id="portfolio_cabinet_id" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="">Tous</option>
                                @foreach ($portfolioCabinets as $cabinet)
                                    <option value="{{ $cabinet->id }}" {{ request('portfolio_cabinet_id') == $cabinet->id ? 'selected' : '' }}>
                                        {{ $cabinet->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="adhesion_mydrh" class="block text-sm font-medium text-gray-700">Adhésion MyDrh</label>
                            <select name="adhesion_mydrh" id="adhesion_mydrh" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="">Tous</option>
                                <option value="1" {{ request('adhesion_mydrh') == '1' ? 'selected' : '' }}>Oui</option>
                                <option value="0" {{ request('adhesion_mydrh') == '0' ? 'selected' : '' }}>Non</option>
                            </select>
                        </div>
                        <div>
                            <label for="client_forme_saisie" class="block text-sm font-medium text-gray-700">Formé à la saisie</label>
                            <select name="client_forme_saisie" id="client_forme_saisie" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="">Tous</option>
                                <option value="1" {{ request('client_forme_saisie') == '1' ? 'selected' : '' }}>Oui</option>
                                <option value="0" {{ request('client_forme_saisie') == '0' ? 'selected' : '' }}>Non</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Filtrer</button>
                        </div>
                    </div>
                </form>

                <!-- Liste des clients -->
                <div class="bg-white rounded-lg shadow p-6 overflow-x-auto shadow-md sm:rounded-lg">
                    <h2 class="text-xl font-bold mb-4">{{ __('Liste des clients') }}</h2>
                    <div class="mb-4">
                        <a href="{{ route('clients.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fa fa-plus mr-2" aria-hidden="true"></i>Ajouter un client
                        </a>
                    </div>

                    <table id="clientsTable" class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable Paie</th>
                                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gestionnaire Principal</th>
                                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Convention Collective</th>
                                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saisie Variables</th>
                                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Forme Saisie</th>
                                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Formation Saisie</th>
                                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Début Prestation</th>
                                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($clients as $client)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $client->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $client->responsablePaie->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $client->gestionnairePrincipal->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $client->conventionCollective->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $client->saisie_variables }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $client->client_forme_saisie }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $client->date_formation_saisie }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $client->date_debut_prestation }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('clients.show', $client->id) }}" class="text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Voir</a>
                                        <a href="{{ route('clients.edit', $client->id) }}" class="text-white bg-gradient-to-br from-pink-500 to-orange-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Modifier</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $clients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="container mt-3">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Répartition des clients par statut</h3>
                <div id="clientStatusChart"></div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Évolution du nombre de clients</h3>
                <div id="clientGrowthChart"></div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Top 5 des conventions collectives</h3>
                <div id="topConventionsChart"></div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-semibold mb-4">Répartition des clients par gestionnaire principal</h3>
            <div id="clientsByManagerChart"></div>
        </div>
    </div>

@endsection
{{--  --}}
@push('scripts')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#clientsTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "lengthMenu": "Afficher _MENU_ enregistrements par page",
                    "zeroRecords": "Aucun enregistrement trouvé",
                    "info": "Affichage de la page _PAGE_ sur _PAGES_",
                    "infoEmpty": "Aucun enregistrement disponible",
                    "infoFiltered": "(filtré à partir de _MAX_ enregistrements au total)",
                    "search": "Rechercher:",
                    "paginate": {
                        "first": "Premier",
                        "last": "Dernier",
                        "next": "Suivant",
                        "previous": "Précédent"
                    }
                }
            });
        });
    </script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          // Graphique de répartition des clients par statut
          var clientStatusOptions = {
              series: [{
                  data: [
                      {{ $clients->where('status', 'actif')->count() }},
                      {{ $clients->where('status', 'inactif')->count() }}
                  ]
              }],
              chart: {
                  type: 'pie',
                  height: 350
              },
              labels: ['Actif', 'Inactif'],
              responsive: [{
                  breakpoint: 480,
                  options: {
                      chart: {
                          width: 200
                      },
                      legend: {
                          position: 'bottom'
                      }
                  }
              }]
          };
          var clientStatusChart = new ApexCharts(document.querySelector("#clientStatusChart"),
              clientStatusOptions);
          clientStatusChart.render();

          // Graphique d'évolution du nombre de clients
          var clientGrowthOptions = {
              series: [{
                  name: 'Nombre de clients',
                  data: @json($clientGrowthData)
              }],
              chart: {
                  height: 350,
                  type: 'line',
              },
              xaxis: {
                  categories: @json($clientGrowthLabels)
              },
              yaxis: {
                  title: {
                      text: 'Nombre de clients'
                  }
              }
          };
          var clientGrowthChart = new ApexCharts(document.querySelector("#clientGrowthChart"),
              clientGrowthOptions);
          clientGrowthChart.render();

          // Graphique des top 5 conventions collectives
          var topConventionsOptions = {
              series: [{
                  data: @json($topConventionsData)
              }],
              chart: {
                  type: 'bar',
                  height: 350
              },
              plotOptions: {
                  bar: {
                      borderRadius: 4,
                      horizontal: true,
                  }
              },
              dataLabels: {
                  enabled: false
              },
              xaxis: {
                  categories: @json($topConventionsLabels)
              }
          };
          var topConventionsChart = new ApexCharts(document.querySelector("#topConventionsChart"),
              topConventionsOptions);
          topConventionsChart.render();

          // Graphique de répartition des clients par gestionnaire principal
          var clientsByManagerOptions = {
              series: [{
                  data: @json($clientsByManagerData)
              }],
              chart: {
                  type: 'bar',
                  height: 350
              },
              plotOptions: {
                  bar: {
                      borderRadius: 4,
                      horizontal: true,
                  }
              },
              dataLabels: {
                  enabled: false
              },
              xaxis: {
                  categories: @json($clientsByManagerLabels)
              }
          };
          var clientsByManagerChart = new ApexCharts(document.querySelector("#clientsByManagerChart"),
              clientsByManagerOptions);
          clientsByManagerChart.render();
      });
  </script>
@endpush
