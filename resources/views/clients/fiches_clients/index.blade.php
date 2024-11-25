@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-6">Fiches Clients</h1>
                <a href="{{ route('fiches-clients.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 h-10 rounded">
                    Créer une Fiche Client
                </a>
            </div>
            <div>
                <form class="" action="{{ route('fiches-clients.migrate') }}"
                    method="POST"
                    onsubmit="return confirm('Êtes-vous sûr de vouloir migrer toutes les fiches clients vers la nouvelle période de paie ?');">
                    @csrf
                    <div class="">
                        <label for="periode_paie_id" class="block text-sm font-medium text-gray-700">Période de Paie</label>
                        <select name="periode_paie_id" id="periode_paie_id" class="form-control">
                            @foreach ($periodesPaie as $periode)
                                <option value="{{ $periode->id }}">{{ $periode->reference }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Migrer vers la nouvelle période de paie
                    </button>
                </form>
            </div>
        </div>

        <!-- Formulaire de filtre -->
        <form method="GET" action="{{ route('fiches-clients.index') }}" class="mb-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                    <select name="client_id" id="client_id" class="form-control">
                        <option value="">Tous les clients</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="periode_paie_id" class="block text-sm font-medium text-gray-700">Période de Paie</label>
                    <select name="periode_paie_id" id="periode_paie_id" class="form-control">
                        <option value="">Toutes les périodes</option>
                        @foreach ($periodesPaie as $periode)
                            <option value="{{ $periode->id }}"
                                {{ request('periode_paie_id') == $periode->id ? 'selected' : '' }}>
                                {{ $periode->reference }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Filtrer
                    </button>
                </div>
            </div>
        </form>
        <div class="mb-4">
            <a href="{{ route('fiches-clients.export.excel', request()->query()) }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Exporter en Excel
            </a>
            <span class="p-1"></span>
            <a href="{{ route('fiches-clients.export.pdf', request()->query()) }}"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Exporter en PDF
            </a>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Client</th>
                        <th scope="col" class="px-6 py-3">Période de Paie</th>
                        <th scope="col" class="px-6 py-3">Réception variables</th>
                        <th scope="col" class="px-6 py-3">Préparation BP</th>
                        <th scope="col" class="px-6 py-3">Validation BP client</th>
                        <th scope="col" class="px-6 py-3">Préparation et envoie DSN</th>
                        <th scope="col" class="px-6 py-3">Accusés DSN</th>
                        {{-- <th scope="col" class="px-6 py-3">TELEDEC URSSAF</th> --}}
                        <th scope="col" class="px-26 py-3">NOTES</th>
                        {{-- <th scope="col" class="px-6 py-3">Actions</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fichesClients as $fiche)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">{{ $fiche->client->name }}</td>
                            <td class="px-6 py-4">{{ $fiche->periodePaie->reference }}</td>
                            <td class="px-6 py-4">
                                {{ $fiche->reception_variables ? \Carbon\Carbon::parse($fiche->reception_variables)->format('d/m') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $fiche->preparation_bp ? \Carbon\Carbon::parse($fiche->preparation_bp)->format('d/m') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $fiche->validation_bp_client ? \Carbon\Carbon::parse($fiche->validation_bp_client)->format('d/m') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $fiche->preparation_envoie_dsn ? \Carbon\Carbon::parse($fiche->preparation_envoie_dsn)->format('d/m') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $fiche->accuses_dsn ? \Carbon\Carbon::parse($fiche->accuses_dsn)->format('d/m') : 'N/A' }}
                            </td>
                            {{-- <td class="px-6 py-4">{{ $fiche->teledec_urssaf ? \Carbon\Carbon::parse($fiche->teledec_urssaf)->format('d/m') : 'N/A' }}</td> --}}
                            <td class="px-26 py-4">{{ $fiche->notes ?? 'N/A' }}</td>
                            {{-- <td class="px-6 py-4 flex space-x-2">
                                <button onclick="openPopup({{ $fiche->id }})"
                                    class="bg-blue-500 hover:bg-cyan-700 text-white font-bold py-1 px-1 rounded">
                                    Mettre à jour
                                </button>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $fichesClients->links() }}
            </div>
        </div>
    </div>

    <!-- Popup de mise à jour -->
    <div id="updatePopup"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center">
        <div class="relative p-6 border w-92 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Ajouter les variables</h3>
                <livewire:update-fiche-client :ficheClient="$ficheClient" />
            </div>
        </div>
    </div>

    <script>
        function openPopup(ficheClientId) {
            fetch(`/fiches-clients/${ficheClientId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('fiche_client_id').value = ficheClientId;
                    document.getElementById('updateForm').action = `/fiches-clients/${ficheClientId}`;
                    document.getElementById('reception_variables').value = data.reception_variables;
                    document.getElementById('preparation_bp').value = data.preparation_bp;
                    document.getElementById('validation_bp_client').value = data.validation_bp_client;
                    document.getElementById('preparation_envoie_dsn').value = data.preparation_envoie_dsn;
                    document.getElementById('accuses_dsn').value = data.accuses_dsn;
                    document.getElementById('notes').value = data.notes;
                    document.getElementById('updatePopup').classList.remove('hidden');
                });
        }

        function closePopup() {
            document.getElementById('updatePopup').classList.add('hidden');
        }
    </script>
@endsection
