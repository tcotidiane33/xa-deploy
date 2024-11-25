@extends('layouts.admin')

@section('title', 'Liste des Matériaux')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-4">
                <a href="{{ route('materials.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fa fa-plus mr-2" aria-hidden="true"></i>Créer un Matériau
                </a>
            </div>

            <div class="bg-white rounded-lg shadow p-6 overflow-x-auto shadow-md sm:rounded-lg">
                <h2 class="text-xl font-bold mb-4">{{ __('Liste des Matériaux') }}</h2>

                <table id="materialsTable" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Titre</th>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type</th>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Client</th>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($materials as $material)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $material->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $material->type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $material->client->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('materials.show', $material->id) }}"
                                        class="text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Voir</a>
                                    <a href="{{ route('materials.export.excel', $material->id) }}"
                                        class="text-white bg-gradient-to-r from-green-400 to-blue-500 hover:bg-gradient-to-l hover:from-green-400 hover:to-blue-500 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Exporter
                                        en Excel</a>
                                    <a href="{{ route('materials.export.pdf', $material->id) }}"
                                        class="text-white bg-gradient-to-r from-red-400 to-pink-500 hover:bg-gradient-to-l hover:from-red-400 hover:to-pink-500 focus:ring-4 focus:outline-none focus:ring-pink-300 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Exporter
                                        en PDF</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $materials->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- jQuery
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#materialsTable').DataTable({
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
@endpush
