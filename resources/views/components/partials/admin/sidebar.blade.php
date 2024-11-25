<style>
    .sidebar-left {
        height: 100vh;
        overflow: auto;
        overflow-y: hidden;
        /* overflow-anchor: none; */

        /* background: #2c072569; */
        /* overflow-y: scroll; */
        /* padding: 2em 1em 0; */
        /* background-color: #f5f5f5b4; */
    }

    .sidebar-menu {
        padding-bottom: 60px;
        /* Espace en bas pour éviter que le dernier élément ne soit caché */
    }
</style>

<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
    <aside class="sidebar-left">
        <nav class="navbar navbar-inverse relative overflow-y-auto">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse"
                    aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <h1><a class="navbar-brand" href="{{ route('dashboard') }}"><span class="fa fa-area-chart"></span>
                        Admin<span class="dashboard_text">Dashboard</span></a></h1>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="sidebar-menu">
                    <li class="header">EXTERNAILLIANCE</li>
                    <li class="treeview">
                        <a href="{{ route('dashboard') }}">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('clients.index') ? 'active' : '' }}">
                        <a href="{{ route('clients.index') }}">
                            <i class="fa fa-users"></i> <span>{{ __('Clients') }}</span>
                        </a>
                    </li>

                    
                    <li class="{{ request()->routeIs('convention-collectives.index') ? 'active' : '' }}">
                        <a href="{{ route('convention-collectives.index') }}">
                            <i class="fa fa-book"></i> <span>{{ __('Conventions Collectives') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.gestionnaire-client.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.gestionnaire-client.index') }}">
                            <i class="fa fa-link"></i> <span>{{ __('Relation Client Gestionnaire') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('periodes-paie.index') ? 'active' : '' }}">
                        <a href="{{ route('periodes-paie.index') }}">
                            <i class="fa fa-calendar"></i> <span>{{ __('Periode Paie') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('traitements-paie.index') ? 'active' : '' }}">
                        <a href="{{ route('traitements-paie.index') }}">
                            <i class="fa fa-money"></i> <span>{{ __('Traitement Paie') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">
                            <i class="fa fa-user"></i> <span>{{ __('Utilisateurs') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.roles.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="fa fa-lock"></i> <span>{{ __('Rôles et permissions') }}</span>
                        </a>
                    </li>
                    
                    <li class="{{ request()->routeIs('tickets.index') ? 'active' : '' }}">
                        <a href="{{ route('tickets.index') }}">
                            <i class="fa fa-ticket"></i> <span>{{ __('Tickets') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('notifications.index') ? 'active' : '' }}">
                        <a href="{{ route('notifications.index') }}">
                            <i class="fa fa-bell"></i> <span>{{ __('Notifications') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('posts.index') ? 'active' : '' }}">
                        <a href="{{ route('posts.index') }}">
                            <i class="fa fa-pencil"></i> <span>{{ __('Posts') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>
</div>
