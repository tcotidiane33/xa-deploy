<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ExternAlliance') }}</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/backoffice/vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('/backoffice/vendors/images/logo-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('/backoffice/vendors/images/logo-icon.png') }}" />

    <!-- Autres balises head -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Flowbite -->
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/backoffice/vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/backoffice/vendors/styles/icon-font.min.css') }}" />
    {{-- <link rel="stylesheet" type="text/css"
        href="{{ asset('/backoffice/src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" /> --}}
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/backoffice/src/plugins/datatables/css/responsive.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/backoffice/vendors/styles/style.css') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.3.1/styles/default.min.css">


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
        crossorigin="anonymous"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag("js", new Date());

        gtag("config", "G-GBZ3SGGX85");
    </script>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                "gtm.start": new Date().getTime(),
                event: "gtm.js"
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != "dataLayer" ? "&l=" + l : "";
            j.async = true;
            j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, "script", "dataLayer", "GTM-NXZMQSS");
    </script>
    <!-- End Google Tag Manager -->
    @stack('styles')

    <!-- Styles -->
    @if ($theme == 'dark')
        <link href="{{ asset('assets/css/dark-theme.css') }}" rel="stylesheet">
        <style>
            body {
                /* background: url('assets/mp.svg') center/cover fixed; */
                background-color: #cbaace;
                overflow-x: hidden;
            }
        </style>
    @else
        <link href="{{ asset('assets/css/light-theme.css') }}" rel="stylesheet">
        <style>
            body {
                /* background: url('assets/mp.svg') center/cover fixed; */
                background-color: #cbaace3a;
                overflow-x: hidden;
            }
        </style>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.3.1/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.3.1/languages/sql.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightBlock(block);
            });
        });
    </script>
    @livewireStyles
    <style>
        /* Styles supplémentaires */
        .menu-container {
            position: fixed;
            bottom: 50px;
            right: 20px;
        }

        .breadcrumb-container {
            background-color: #f9fafb;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .breadcrumb-container nav ol {
            display: flex;
            align-items: center;
        }

        .breadcrumb-container nav ol li {
            display: flex;
            align-items: center;
        }

        .breadcrumb-container nav ol li a {
            color: #4b5563;
            font-size: 0.875rem;
            text-decoration: none;
        }

        .breadcrumb-container nav ol li a:hover {
            color: #1f2937;
        }

        .breadcrumb-container nav ol li span {
            color: #6b7280;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    @include('components.partials.admin.pre-loader')

    <div class="header">
        @include('components.partials.search-bar')
        @include('components.partials.header')

    </div>

    {{-- // customeize App setting --}}
    @include('components.partials.right-sidebar')
    {{-- // left side bar  --}}
    @include('components.partials.admin.left-side-bar')

    <div class="mobile-menu-overlay"></div>

    <main>
        @if (session('error'))
            <div class="alert alert-danger mt-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="main-container">
            <!-- Breadcrumb -->
            <div class="breadcrumb-container bg-gray-100 p-0 rounded-full shadow-sm">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}"
                                class="text-gray-700 hover:text-gray-900 inline-flex items-center ">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                    </path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <!-- Dynamic Breadcrumb Links -->
                        @if (isset($breadcrumbs))
                            @foreach ($breadcrumbs as $breadcrumb)
                                <li>
                                    <div class="flex items-center mt-0">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        @if (isset($breadcrumb['url']))
                                            <a href="{{ $breadcrumb['url'] }}"
                                                class="text-gray-700 hover:text-gray-900 ml-1 md:ml-2 text-sm font-medium">
                                                {{ $breadcrumb['name'] }}
                                            </a>
                                        @else
                                            <span class="text-gray-500 ml-1 md:ml-2 text-sm font-medium">
                                                {{ $breadcrumb['name'] }}
                                            </span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ol>
                </nav>
            </div>
            @yield('content')
        </div>
        <div class="menu-container">
            <div data-dial-init class="fixed right-6 bottom-6 group">
                <div id="speed-dial-menu" class="flex flex-col items-center hidden mb-4 space-y-2">
                    <a href="{{ route('tickets.create') }}"
                        class="flex items-center justify-center w-12 h-12 text-white bg-blue-600 rounded-full hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 18">
                            <path
                                d="M14.419 10.581a3.564 3.564 0 0 0-2.574 1.1l-4.756-2.49a3.54 3.54 0 0 0 .072-.71 3.55 3.55 0 0 0-.043-.428L11.67 6.1a3.56 3.56 0 1 0-.831-2.265c.006.143.02.286.043.428L6.33 6.218a3.573 3.573 0 1 0-.175 4.743l4.756 2.491a3.58 3.58 0 1 0 3.508-2.871Z" />
                        </svg>
                        <span class="sr-only">Create Ticket</span>
                    </a>
                    <a href="{{ route('posts.create') }}"
                        class="flex items-center justify-center w-12 h-12 text-white bg-green-600 rounded-full hover:bg-green-700 focus:ring-4 focus:ring-green-300 focus:outline-none dark:focus:ring-green-800">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path d="M5 20h10a1 1 0 0 0 1-1v-5H4v5a1 1 0 0 0 1 1Z" />
                            <path
                                d="M18 7H2a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2v-3a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Zm-1-2V2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v3h14Z" />
                        </svg>
                        <span class="sr-only">Create Post</span>
                    </a>
                    <a href="{{ route('clients.create') }}"
                        class="flex items-center justify-center w-12 h-12 text-white bg-red-600 rounded-full hover:bg-red-700 focus:ring-4 focus:ring-red-300 focus:outline-none dark:focus:ring-red-800">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 20">
                            <path
                                d="M5 9V4.13a2.96 2.96 0 0 0-1.293.749L.879 7.707A2.96 2.96 0 0 0 .13 9H5Zm11.066-9H9.829a2.98 2.98 0 0 0-2.122.879L7 1.584A.987.987 0 0 0 6.766 2h4.3A3.972 3.972 0 0 1 15 6v10h1.066A1.97 1.97 0 0 0 18 14V2a1.97 1.97 0 0 0-1.934-2Z" />
                            <path
                                d="M11.066 4H7v5a2 2 0 0 1-2 2H0v7a1.969 1.969 0 0 0 1.933 2h9.133A1.97 1.97 0 0 0 13 18V6a1.97 1.97 0 0 0-1.934-2Z" />
                        </svg>
                        <span class="sr-only">Create Client</span>
                    </a>
                    <a href="{{ route('convention-collectives.create') }}"
                        class="flex items-center justify-center w-12 h-12 text-white bg-yellow-600 rounded-full hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-300 focus:outline-none dark:focus:ring-yellow-800">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 18 20">
                            <path
                                d="M5 9V4.13a2.96 2.96 0 0 0-1.293.749L.879 7.707A2.96 2.96 0 0 0 .13 9H5Zm11.066-9H9.829a2.98 2.98 0 0 0-2.122.879L7 1.584A.987.987 0 0 0 6.766 2h4.3A3.972 3.972 0 0 1 15 6v10h1.066A1.97 1.97 0 0 0 18 14V2a1.97 1.97 0 0 0-1.934-2Z" />
                            <path
                                d="M11.066 4H7v5a2 2 0 0 1-2 2H0v7a1.969 1.969 0 0 0 1.933 2h9.133A1.97 1.97 0 0 0 13 18V6a1.97 1.97 0 0 0-1.934-2Z" />
                        </svg>
                        <span class="sr-only">Create Convention Collective</span>
                    </a>
                    <a href="{{ route('periodes-paie.create') }}"
                        class="flex items-center justify-center w-12 h-12 text-white bg-purple-600 rounded-full hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 focus:outline-none dark:focus:ring-purple-800">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z" />
                            <path
                                d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z" />
                        </svg>
                        <span class="sr-only">Create Période de Paie</span>
                    </a>
                    <a href="{{ route('traitements-paie.create') }}"
                        class="flex items-center justify-center w-12 h-12 text-white bg-pink-400 rounded-full hover:bg-pink-700 focus:ring-4 focus:ring-teal-300 focus:outline-none dark:focus:ring-teal-800">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z" />
                            <path
                                d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z" />
                        </svg>
                        <span class="sr-only">Create Traitement de Paie</span>
                    </a>
                    <!-- Ajoutez d'autres boutons ici -->
                </div>
                <button type="button" data-dial-toggle="speed-dial-menu" aria-controls="speed-dial-menu"
                    aria-expanded="false"
                    class="flex items-center justify-center text-white bg-blue-700 rounded-full w-14 h-14 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800">
                    <svg class="w-5 h-5 transition-transform group-hover:rotate-45" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 1v16M1 9h16" />
                    </svg>
                    <span class="sr-only">Actions Menu</span>
                </button>
            </div>
        </div>
        <!-- Inclure Flowbite JS -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggleButton = document.querySelector('[data-dial-toggle]');
                const menu = document.getElementById(toggleButton.getAttribute('data-dial-toggle'));

                toggleButton.addEventListener('click', function() {
                    menu.classList.toggle('hidden');
                });
            });
        </script>
    </main>
    <!-- welcome modal end -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.3/dist/flowbite.min.js"></script>
    <!-- js -->
    <script src="{{ asset('/backoffice/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('/backoffice/vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('/backoffice/vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('/backoffice/vendors/scripts/layout-settings.js') }}"></script>
    <script src="{{ asset('/backoffice/src/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('/backoffice/src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/backoffice/src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/backoffice/src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/backoffice/src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/backoffice/vendors/scripts/dashboard3.js') }}"></script>
    {{-- <!-- Google Tag Manager (noscript) --> --}}
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    @stack('scripts')
    @livewireScripts


</body>

</html>
