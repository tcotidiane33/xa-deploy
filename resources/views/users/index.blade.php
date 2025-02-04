@extends('layouts.admin')

@section('title', 'Liste des utilisateurs')

@section('content')
    <div class="container mx-auto p-1 ">
        <div class="flex justify-center items-center">
            <h1 class="text-2xl font-bold">Liste des utilisateurs</h1>
        </div>
        <a href="{{ route('users.create') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2 inline-block">Nouveau
            membre</a>
        <div class="overflow-x-auto relative rounded-lg">
            <table class="w-full text-sm text-left  text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="py-3 px-6">Nom</th>
                        <th scope="col" class="py-3 px-6">Email</th>
                        <th scope="col" class="py-3 px-6">Rôles</th>
                        <th scope="col" class="py-3 px-6">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-2 px-6">{{ $user->name }}</td>
                            <td class="py-2 px-6"> <span
                                    class="bg-indigo-100 text-indigo-800 text-xxl font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-indigo-400 border border-indigo-400">
                                    {{ $user->email }}</span></td>
                            <td class="py-2 px-6"> <span
                                    class="inline-flex bg-green-100 text-green-800 text-xxl font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">

                                    @foreach ($user->roles as $role)
                                    <li class="grid"><label for="role-{{ $role->id }}"
                                        class="inline-flex items-center justify-center w-full px-2 py-0 mb-0 text-sm font-medium text-center hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border rounded-lg cursor-pointer text-gray-500 border-gray-200 dark:border-gray-700 dark:peer-checked:border-blue-500 peer-checked:border-blue-700 dark:hover:border-gray-600 dark:peer-checked:text-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-600 dark:peer-checked:bg-blue-900">
                                        {{ $role->name }}
                                    </label>
                                    </li>
                                    <br>
                                    @endforeach
                                </span></td>
                            <td class="py-2 px-6">
                                <a href="{{ route('users.show', $user) }}"
                                    class="text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-1 text-center me-2 mb-2">Voir</a>
                                <a href="{{ route('users.edit', $user) }}"
                                    class="text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-1 text-center me-2 mb-2">Éditer</a>
                                {{-- <form action="{{ route('users.destroy', $user) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Supprimer</button>
                                </form> --}}
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
@endsection
