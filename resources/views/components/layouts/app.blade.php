<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Admin Dashboard' }}</title>
        
        <!-- FIX DARI GURU: MEMUAT CSS VIA CDN YANG STABIL -->
        <!-- 1. Font Awesome (Ikon) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        
        <!-- 2. Bootstrap (Dependency CSS) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        
        <!-- 3. AdminLTE (Theme CSS) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
        
        <!-- 4. Vite: Memuat app.js (JS, jQuery, AdminLTE JS) dan Custom CSS Tailwind/app.css -->
        @vite(['resources/js/app.js', 'resources/css/app.css'])
        
        @stack('styles')
    </head>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            @include('components.partials.sidebar')
            <div class="content-wrapper">
                @include('components.partials.header')
                {{ $slot }}
            </div>
            @include('components.partials.footer')
        </div>

        <!-- {{-- <script src="{{ mix('js/app.js') }}"></script> --}} -->
        @stack('scripts') 
    </body>

</html>