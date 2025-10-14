<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Admin Dashboard' }}</title>
        {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
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

        {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
        @stack('scripts')
    </body>

</html>