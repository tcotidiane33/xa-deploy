<div class="header-right">
    <div class="dashboard-setting user-notification">
        <div class="dropdown">
            <!-- Icones des uploads, downloads, et chats -->
            <a href="{{ route('admin.files.index') }}" class="dropdown-toggle no-arrow">
                <span class="micon bi bi-upload"></span>
                <span class="micon bi bi-download"></span>
            </a>
            {{-- @auth
            <!-- Accès aux Chats -->
            <a href="{{ route('dashboard') }}" class="dropdown-toggle no-arrow">
                <span class="micon bi bi-chat-text"></span>
                <span class="mtext">Chats</span>
            </a>
            @endauth --}}
        </div>
    </div>

    @auth
        <!-- Section des notifications -->
        <div class="flex mt-0  mr-3 items-center space-x-4 ">
            <!-- Tickets -->
            <div class="relative">
                <button class="text-gray-500 w-50 h-50 hover:text-gray-700 focus:outline-none" id="ticketDropdown"
                    data-dropdown-toggle="ticketMenu">
                    <span
                        class="inline-flex items-center justify-center w-4 h-4 z-3 text-xs font-bold text-white bg-red-500 rounded-full absolute -top-1 -right-2">
                        {{ $tickets->count() }}
                    </span>
                    <i class="fa fa-ticket text-lg "></i>
                </button>
                <!-- Dropdown Tickets -->
                <div id="ticketMenu" class="hidden z-10 w-64 bg-white rounded-lg shadow-lg">
                    <div class="py-2 text-sm text-gray-700">
                        <h3 class="font-bold text-base px-4 py-2">Vous avez {{ $tickets->count() }} nouveaux tickets</h3>
                        <ul>
                            @foreach ($tickets->take(4) as $ticket)
                                <li class="border-b px-4 py-2">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="flex items-start space-x-3">
                                        <div>
                                            <p class="font-medium">{{ Str::limit($ticket->title, 30) }}</p>
                                            <p class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="px-4 py-2 text-center">
                            <a href="{{ route('tickets.index') }}" class="text-indigo-600 hover:underline">Voir tous les
                                tickets</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications générales -->
            <div class="relative">
                <button class="text-gray-500 w-50 h-50 hover:text-gray-700 focus:outline-none" id="notificationDropdown"
                    data-dropdown-toggle="notificationMenu">
                    <span
                        class="inline-flex items-center justify-center  w-4 h-4 z-3  text-xs font-bold text-white bg-blue-500 rounded-full absolute -top-1 -right-2">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                    <i class="fa fa-bell text-lg"></i>
                </button>
                <!-- Dropdown Notifications -->
                <div id="notificationMenu" class="hidden z-10 w-64 bg-white rounded-lg shadow-lg">
                    <div class="py-2 text-sm text-gray-700">
                        <h3 class="font-bold text-base px-4 py-2">Vous avez
                            {{ auth()->user()->unreadNotifications->count() }} nouvelles notifications</h3>
                        <ul>
                            @foreach (auth()->user()->unreadNotifications->take(4) as $notification)
                                <li class="border-b px-4 py-2">
                                    <a href="{{ route('notifications.show', $notification->id) }}"
                                        class="flex items-start space-x-3">
                                        <div>
                                            <p class="font-medium">{{ Str::limit($notification->data['message'], 30) }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="px-4 py-2 text-center">
                            <a href="{{ route('notifications.index') }}" class="text-indigo-600 hover:underline">Voir
                                toutes les notifications</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Posts -->
            <div class="relative">
                <button class="text-gray-500 w-50 h-50 hover:text-gray-700 focus:outline-none" id="postDropdown"
                    data-dropdown-toggle="postMenu">
                    <span
                        class="inline-flex items-center justify-center  w-4 h-4 z-3  text-xs font-bold text-white bg-green-500 rounded-full absolute -top-1 -right-2">
                        {{ $posts->count() }}
                    </span>
                    <i class="fa fa-tasks text-lg"></i>
                </button>
                <!-- Dropdown Posts -->
                <div id="postMenu" class="hidden z-10 w-64 bg-white rounded-lg shadow-lg">
                    <div class="py-2 text-sm text-gray-700">
                        <h3 class="font-bold text-base px-4 py-2">Il y a {{ $posts->count() }} nouveaux posts</h3>
                        <ul>
                            @foreach ($posts->take(4) as $post)
                                <li class="border-b px-4 py-2">
                                    <a href="{{ route('posts.show', $post) }}" class="flex items-start space-x-3">
                                        <div>
                                            <p class="font-medium">{{ Str::limit($post->title, 20) }}</p>
                                            <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="px-4 py-2 text-center">
                            <a href="{{ route('posts.index') }}" class="text-indigo-600 hover:underline">Voir tous les
                                posts</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ajouter les scripts pour activer les dropdowns -->
        <script>
            // Activer les dropdowns Flowbite
            document.addEventListener('DOMContentLoaded', function() {
                const dropdownToggle = document.querySelectorAll('[data-dropdown-toggle]');
                dropdownToggle.forEach(function(el) {
                    el.addEventListener('click', function() {
                        const menu = document.getElementById(el.getAttribute('data-dropdown-toggle'));
                        menu.classList.toggle('hidden');
                    });
                });
            });
        </script>


        <!-- Profil de l'utilisateur -->
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" />
                        @else
                            <img src="{{ asset('backoffice/vendors/images/photo1.jpg') }}" alt="Default Avatar" />
                        @endif
                    </span>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('profile.index') }}"><i class="dw dw-user1"></i> Profile</a>
                    <a class="dropdown-item" href="{{ route('profile.settings') }}"><i class="dw dw-settings2"></i>
                        Settings</a>
                    <a class="dropdown-item" href="{{ route('tickets.index') }}"><i class="dw dw-help"></i> Help</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="dw dw-logout"></i> Log Out
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    @endauth
</div>
