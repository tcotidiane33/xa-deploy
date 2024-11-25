@extends('layouts.admin')

@section('title', 'Modifier le Ticket')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <div class="row">
                <div class="container">
                    <form method="POST" action="{{ route('tickets.update', $ticket->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="subject" class="block text-gray-700 text-sm font-bold mb-2">Titre:</label>
                            <input type="text" name="subject" id="subject" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $ticket->subject }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                            <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ $ticket->description }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="priorite" class="block text-gray-700 text-sm font-bold mb-2">Priorité:</label>
                            <select name="priorite" id="priorite" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="basse" {{ $ticket->priorite == 'basse' ? 'selected' : '' }}>Basse</option>
                                <option value="moyenne" {{ $ticket->priorite == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                                <option value="haute" {{ $ticket->priorite == 'haute' ? 'selected' : '' }}>Haute</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="assigne_a_id" class="block text-gray-700 text-sm font-bold mb-2">Assigné à:</label>
                            <select name="assigne_a_id" id="assigne_a_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Non assigné</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $ticket->assigne_a_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
