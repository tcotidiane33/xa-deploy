<div class="left-side-bar">

    <div class="brand-logo">
       <a href="#">
            <img src="{{ asset('backoffice/vendors/images/deskapp-logo.svg') }}" alt=""
                class="dark-logo" />
            <img src="{{ asset('backoffice/vendors/images/deskapp-logo-white.svg') }}" alt=""
                class="light-logo" />
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <!-- Dashboard -->
                <li class="dropdown">
                    <a href="{{ route('dashboard') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-house"></span><span class="mtext">Tableau de bord</span>
                    </a>
                </li>

                <!-- Clients -->
                <li>
                    <a href="{{ route('clients.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-people-fill"></span><span class="mtext">Clients</span>
                    </a>
                </li>

                <!-- Utilisateurs -->
                <li>
                    <a href="{{ route('users.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-person"></span><span class="mtext">Utilisateurs</span>
                    </a>
                </li>


                <!-- Fiche Client -->
                <li>
                    <a href="{{ route('fiches-clients.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-person-fill"></span><span class="mtext">Fiches Clients</span>
                    </a>
                </li>
                <!-- Matériels -->
                <li>
                    <a href="{{ route('materials.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-box-seam"></span><span class="mtext">Matériels</span>
                    </a>
                </li>
                <!-- Conention -->
                <li>
                    <a href="{{ route('convention-collectives.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-file-earmark-text"></span><span class="mtext">Conventions <br>
                            Collectives</span>
                    </a>
                </li>

                <!-- Périodes de Paie -->
                <li>
                    <a href="{{ route('periodes-paie.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-calendar"></span><span class="mtext">Périodes de Paie</span>
                    </a>
                </li>

                <!-- Traitement des Paies -->
                <li>
                    <a href="{{ route('traitements-paie.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-cash"></span><span class="mtext">Traitements Paie</span>
                    </a>

                </li>


                <!-- Admin -->
                <li class="dropdown {{ request()->is('admin*') ? 'active' : '' }}">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-gear"></span><span class="mtext">Admin</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('admin.dashboard') }}"
                                class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                                <span class="micon bi bi-speedometer2"></span><span class="mtext ml-2">Panneau Admin</span>
                            </a></li>
                        <li><a href="{{ route('admin.settings.index') }}"
                                class="{{ request()->is('admin/settings') ? 'active' : '' }}">
                                <span class="micon bi bi-gear-fill"></span><span class="mtext ml-2">Paramétrage</span>
                            </a></li>
                        <li><a href="{{ route('admin.activities.index') }}"
                                class="{{ request()->is('admin/activities') ? 'active' : '' }}">
                                <span class="micon bi bi-activity"></span><span class="mtext ml-2">Historiques activités</span>
                            </a></li>
                        <li><a href="{{ route('admin.roles.index') }}"
                                class="{{ request()->is('admin/roles') ? 'active' : '' }}">
                                <span class="micon bi bi-person-badge"></span><span class="mtext ml-2">Rôles</span>
                            </a></li>
                        <li><a href="{{ route('admin.permissions.index') }}"
                                class="{{ request()->is('admin/permissions') ? 'active' : '' }}">
                                <span class="micon bi bi-shield-lock"></span><span class="mtext ml-2">Permissions</span>
                            </a></li>
                        <li><a href="{{ route('admin.client_user.index') }}"
                                class="{{ request()->is('admin/client_user') ? 'active' : '' }}">
                                <span class="micon bi bi-people-fill"></span><span class="mtext ml-2">Gestion des clients</span>
                            </a></li>
                        <li>
                            <a href="{{ route('admin.backups.index') }}"
                                class="{{ request()->is('admin/backups*') ? 'active' : '' }}">
                                <span class="micon bi bi-archive"></span>
                                <span class="mtext ml-2">Sauvegardes système</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.business-backups.index') }}"
                                class="{{ request()->is('admin/business-backups*') ? 'active' : '' }}">
                                <span class="micon bi bi-database"></span>
                                <span class="mtext ml-2">Sauvegardes métier</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Conventions Collectives -->
                {{-- <li>
                    <a href="{{ route('convention-collectives.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-file-earmark-text"></span><span class="mtext">Conventions <br>
                            Collectives</span>
                    </a>
                </li>
                  <!-- Tickets -->
                <li>
                    <a href="{{ route('tickets.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-ticket"></span><span class="mtext">Tickets</span>
                    </a>
                </li>

                <!-- Notifications -->
                <li>
                    <a href="{{ route('notifications.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-bell"></span><span class="mtext">Notifications</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('posts.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-box"></span><span class="mtext">Posts</span>
                    </a>
                </li> --}}
            </ul>
        </div>

    </div>
</div>

<!-- {{-- <script src="{{ asset('js/app.js') }}"></script> --}} -->
<script>
    // Inclure les scripts JavaScript ici
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle sidebar
        var closeSidebar = document.querySelector('.close-sidebar');
        var leftSidebar = document.querySelector('.left-side-bar');

        closeSidebar.addEventListener('click', function() {
            leftSidebar.classList.toggle('closed');
        });

        // Toggle dropdowns
        var dropdowns = document.querySelectorAll('.dropdown');

        dropdowns.forEach(function(dropdown) {
            var toggle = dropdown.querySelector('.dropdown-toggle');
            var submenu = dropdown.querySelector('.submenu');

            toggle.addEventListener('click', function() {
                dropdown.classList.toggle('active');
                submenu.style.display = dropdown.classList.contains('active') ? 'block' :
                    'none';
            });
            // toggle.addEventListener('click', function() {
            //     submenu.classList.toggle('open');
            // });

            submenu.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
    });
</script>
