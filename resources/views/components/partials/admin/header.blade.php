<div class="sticky-header header-section ">
    <div class="header-left">
        <!--toggle button start-->
        <button id="showLeftPush"><i class="fa fa-bars"></i></button>
        <!--toggle button end-->
        <div class="profile_details_left">
            <!-- notifications of menu start -->
            <ul class="nofitications-dropdown">
                <!-- Tickets -->
                <li class="dropdown head-dpdn">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-ticket"></i><span class="badge">{{ $tickets->count() }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="notification_header">
                                <h3>Vous avez {{ $tickets->count() }} nouveaux tickets</h3>
                            </div>
                        </li>
                        @foreach ($tickets->take(4) as $ticket)
                            <li>
                                <a href="{{ route('tickets.show', $ticket) }}">
                                    <div class="user_img">
                                        @if ($ticket->creator && $ticket->creator->avatar)
                                            <img src="{{ $ticket->creator->avatar }}" alt="Avatar du créateur">
                                        @else
                                            <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar par défaut">
                                        @endif
                                    </div>
                                    <div class="notification_desc">
                                        <p>{{ Str::limit($ticket->title, 30) }}</p>
                                        <p><span>{{ $ticket->created_at->diffForHumans() }}</span></p>
                                    </div>
                                    <div class="clearfix"></div>
                                </a>
                            </li>
                        @endforeach
                        <li>
                            <div class="notification_bottom">
                                <a href="{{ route('tickets.index') }}">Voir tous les tickets</a>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- Notifications -->
                <li class="dropdown head-dpdn">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell"></i><span
                            class="badge blue">{{ auth()->user()->unreadNotifications->count() }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="notification_header">
                                <h3>Vous avez {{ auth()->user()->unreadNotifications->count() }} nouvelles notifications
                                </h3>
                            </div>
                        </li>
                        @foreach (auth()->user()->unreadNotifications->take(4) as $notification)
                            <li><a href="{{ route('notifications.show', $notification->id) }}">
                                    <div class="user_img"><img src="{{ asset('images/notification.png') }}"
                                            alt=""></div>
                                    <div class="notification_desc">
                                        <p>{{ Str::limit($notification->data['message'], 30) }}</p>
                                        <p><span>{{ $notification->created_at->diffForHumans() }}</span></p>
                                    </div>
                                    <div class="clearfix"></div>
                                </a></li>
                        @endforeach
                        <li>
                            <div class="notification_bottom">
                                <a href="{{ route('notifications.index') }}">Voir toutes les notifications</a>
                            </div>
                        </li>
                        
                    </ul>

                </li>

                <!-- Posts -->
                <li class="dropdown head-dpdn">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-tasks"></i><span class="badge blue1">{{ $posts->count() }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="notification_header">
                                <h3>Il y a {{ $posts->count() }} nouveaux posts</h3>
                            </div>
                        </li>
                        @foreach ($posts->take(4) as $post)
                            <li><a href="{{ route('posts.show', $post) }}">
                                    <div class="task-info">
                                        <span class="task-desc">{{ Str::limit($post->title, 20) }}</span>
                                        <span class="percentage">{{ $post->created_at->diffForHumans() }}</span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="progress progress-striped active">
                                        <div class="bar {{ ['yellow', 'green', 'red', 'blue'][array_rand(['yellow', 'green', 'red', 'blue'])] }}"
                                            style="width:100%;"></div>
                                    </div>
                                </a></li>
                        @endforeach
                        <li>
                            <div class="notification_bottom">
                                <a href="{{ route('posts.index') }}">Voir tous les posts</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="clearfix"> </div>
        </div>
        <!--search-box-->
        {{-- <div class="search-box mt-3 pl-4">

            <form class="input max-w-sm mx-auto" id="search-form">
                <input type="text" id="search-navbar"
                    class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search...">
                <button type="button" data-collapse-toggle="navbar-search" aria-controls="navbar-search"
                    aria-expanded="false"
                    class="md:hidden text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 me-1">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
                

            </form>
        </div> --}}

        <div class="clearfix">
        </div>
    </div>
    <div class="header-right mt-2">
        <!-- Add header right content here -->

        {{-- end search --}}
        <div class="profile_details">

            <ul>
                <li class="dropdown profile_details_drop">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <div class="profile_img">
                            <span class="prfil-img"><img
                                    src="{{ auth()->user()->avatar ?? 'images/default-avatar.jpg' }}" width="50"
                                    height="50" alt=""> </span>
                            <div class="user-name">
                                <p>{{ auth()->user()->name }}</p>
                                <span>{{ auth()->user()->roles->first()->name ?? 'User' }}</span>
                            </div>
                            <i class="fa fa-angle-down lnr"></i>
                            <i class="fa fa-angle-up lnr"></i>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                    <ul class="dropdown-menu drp-mnu">
                        <li> <a href="{{ route('admin.settings.index') }}"><i class="fa fa-cog"></i> Settings</a> </li>
                        {{-- <li> <a href="{{ route('profile.update-account') }}"><i class="fa fa-user"></i> My
                                Account</a> </li> --}}
                        <li> <a href="{{ route('profile.update') }}"><i class="fa fa-user"></i> My Account</a> </li>
                        <li> <a href="{{ route('profile.index') }}"><i class="fa fa-suitcase"></i> Profile</a> </li>
                        <li class="{{ request()->routeIs('admin.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.index') }}">
                                <i class="fa fa-cogs"></i> <span>{{ __('Panneau de configuration') }}</span>
                            </a>
                        </li>
                        <li>
                            @auth
                            <div class="dropdown">
                                
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach (auth()->user()->notifications as $notification)
                                        <a class="dropdown-item" href="#">
                                            {{ $notification->data['creator_name'] }} a créé une relation pour
                                            {{ $notification->data['client_name'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endauth
                        </li>
                        <li>
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out"></i> Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>


        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <div class="clearfix"> </div>
    </div>
    <div class="clearfix"> </div>
</div>
