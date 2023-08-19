<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') - Stisla</title>

    @stack('styles')

    @vite(['resources/js/app.js'])
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            @include('components.header')

            @include('components.sidebar')

            @yield('main')

            @include('components.footer')
        </div>
    </div>

    @stack('scripts')
</body>

</html>
