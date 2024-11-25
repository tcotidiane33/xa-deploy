<!DOCTYPE HTML>
<html>
<head>
    <title>@yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Flowbite CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flowbite@1.5.3/dist/flowbite.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel='stylesheet' type='text/css' />
    @stack('styles')
</head>
<body class="h-screen overflow-hidden">
    <div class="flex h-screen">
        {{-- @include('partials.sidebar')
        @include('partials.header') --}}

        <!-- main content start-->
        <div class="w-full h-screen overflow-y-auto">
            <div class="p-4">
                @yield('content')
            </div>
        </div>
        <!--footer-->
        {{-- @include('partials.footer') --}}
        <!--//footer-->
    </div>

    <!-- js-->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.3/dist/flowbite.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    @stack('scripts')
</body>
</html>