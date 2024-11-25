@extends('layouts.admin')

@section('content')

<h1>Filtrer et transférer des clients</h1>

    <form action="{{ route('admin.clients.filter') }}" method="GET" class="space-y-6 bg-white p-6 rounded-lg shadow-md">
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                <select name="client_id" id="client_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Sélectionner un client</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="gestionnaire_id" class="block text-sm font-medium text-gray-700">Gestionnaire</label>
                <select name="gestionnaire_id" id="gestionnaire_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Sélectionner un gestionnaire</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="responsable_id" class="block text-sm font-medium text-gray-700">Responsable</label>
                <select name="responsable_id" id="responsable_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Sélectionner un responsable</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="px-4 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Filtrer
        </button>
    </form>

    <h2 class="mt-8">Résultats du filtre</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Client</th>
                    <th scope="col" class="px-6 py-3">Gestionnaire actuel</th>
                    <th scope="col" class="px-6 py-3">Responsable actuel</th>
                    <th scope="col" class="px-6 py-3">Historique des changements</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $client->name }}</td>
                        <td class="px-6 py-4">{{ $client->gestionnairePrincipal->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $client->responsablePaie->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <ul>
                                @foreach($client->histories as $history)
                                    <li>{{ $history->maj_fiche_para }} ({{ $history->created_at->format('d/m/Y H:i') }})</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h1>Affectation et transfert de clients</h1>

    <form action="{{ route('admin.clients.transfer') }}" method="POST" class="space-y-6 bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th scope="col" class="px-6 py-3">Client</th>
                        <th scope="col" class="px-6 py-3">Gestionnaire actuel</th>
                        <th scope="col" class="px-6 py-3">Responsable actuel</th>
                        <th scope="col" class="px-6 py-3">Nouveau gestionnaire</th>
                        <th scope="col" class="px-6 py-3">Nouveau responsable</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="client_ids[]" value="{{ $client->id }}" class="client-checkbox">
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $client->name }}</td>
                            <td class="px-6 py-4">{{ $client->gestionnairePrincipal->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $client->responsablePaie->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <select name="new_gestionnaire_id[{{ $client->id }}]" class="block w-full px-2 py-2 border rounded-md bg-gray-50 dark:bg-gray-800 dark:border-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Sélectionner un gestionnaire</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <select name="new_responsable_id[{{ $client->id }}]" class="block w-full px-2 py-2 border rounded-md bg-gray-50 dark:bg-gray-800 dark:border-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Sélectionner un responsable</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" class="px-4 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Transférer
        </button>
    </form>

    <script>
        document.getElementById('select-all').addEventListener('click', function(event) {
            const checkboxes = document.querySelectorAll('.client-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = event.target.checked);
        });
    </script>
@endsection