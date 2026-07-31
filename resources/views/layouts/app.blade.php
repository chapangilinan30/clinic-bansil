<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <!-- ================= BASIC STYLES ================= -->
    <style>
        /* Hide elements with x-cloak until Alpine or JS is ready */
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- ================= INITIAL JS FOR x-show ================= -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("[x-show]").forEach(el => {
                el.style.display = "none";
            });
        });
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Clinic System'))</title>

    <!-- ================= FONTS ================= -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- ================= STYLES & SCRIPTS ================= -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased bg-gray-100 h-screen">

    <div class="min-h-screen flex flex-col">

        <!-- ================= NAVIGATION ================= -->
        @include('layouts.navigation')
        <!-- ================= END NAVIGATION ================= -->

        <!-- ================= OPTIONAL HEADER ================= -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="container mx-auto px-6 py-4">
                    {{ $header }}
                </div>
            </header>
        @endisset
        <!-- ================= END HEADER ================= -->

        <!-- ================= MAIN CONTENT ================= -->
        <main class="flex-1 container mx-auto px-6 py-8">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>
        <!-- ================= END MAIN ================= -->

        <!-- ================= FOOTER ================= -->
        <footer class="bg-white shadow-inner text-center py-4 text-sm text-gray-500">
            © {{ date('Y') }} Dr. Paul Anthony Bansil Clinic. All rights reserved.
        </footer>
        <!-- ================= END FOOTER ================= -->

    </div>

    <!-- ================= OPTIONAL GLOBAL SCRIPTS ================= -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

</body>

</html>