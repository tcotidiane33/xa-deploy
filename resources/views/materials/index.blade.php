@extends('layouts.admin')

@section('title', 'Liste des Backup')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow p-4 overflow-x-auto shadow-md sm:rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">{{ __('Liste des Backup') }}</h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('materials.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-medium py-2 px-3 rounded inline-flex items-center transition-colors duration-150">
                            <i class="fas fa-plus-circle mr-1"></i>
                            Nouveau Backup
                        </a>
                    </div>
                </div>

                <table id="materialsTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Titre</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Client</th>
                            <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($materials as $material)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-2">{{ $material->title }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @switch($material->type)
                                            @case('document')
                                                bg-blue-100 text-blue-800
                                                @break
                                            @case('image')
                                                bg-green-100 text-green-800
                                                @break
                                            @default
                                                bg-gray-100 text-gray-800
                                        @endswitch
                                    ">
                                        {{ $material->type }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $material->client->name ?? 'N/A' }}</td>
                                <td class="px-6 py-2 space-x-1">
                                    <a href="{{ route('materials.show', $material->id) }}"
                                        class="inline-flex items-center px-1 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition-colors duration-150">
                                        <i class="fas fa-eye mr-1"></i> Voir
                                    </a>
                                    <a href="{{ route('materials.export.excel', $material->id) }}"
                                        class="inline-flex items-center px-1 py-1 text-sm bg-green-100 hover:bg-green-200 text-green-700 rounded transition-colors duration-150">
                                        <i class="fas fa-file-excel mr-1"></i> Excel
                                    </a>
                                    <a href="{{ route('materials.export.pdf', $material->id) }}"
                                        class="inline-flex items-center px-1 py-1 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded transition-colors duration-150">
                                        <i class="fas fa-file-pdf mr-1"></i> PDF
                                    </a>
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
