@extends('layouts.admin')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <div class="breadcrumb">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">Notifications</h1>
            </div>

            <section class="dark:bg-gray-900">
                @forelse($notifications as $notification)
                    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16">
                        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12 mb-8 {{ $notification->read_at ? 'opacity-50' : '' }}">
                            <div class="bg-white shadow-md rounded-lg mb-4 overflow-hidden">
                                <div class="p-4">
                                    <h2 class="text-gray-900 dark:text-white text-3xl font-extrabold mb-2">
                                        {{ $notification->data['action'] }}</h2>
                                    <p class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-4">
                                        {{ $notification->data['details'] }}</p>
                                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4">
                                        Par : {{ $notification->data['creator_name'] ?? 'N/A' }}</p>
                                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4">
                                        E-mail envoyé : {{ $notification->data['email_sent'] ? 'Oui' : 'Non' }}</p>
                                    <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-blue-500 hover:underline">Marquer comme lue</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 mb-2">
                        Vous n'avez pas de notifications.</p>
                @endforelse
            </section>
        </div>
    </div>
@endsection