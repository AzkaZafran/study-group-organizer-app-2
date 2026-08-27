<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Study Group Organizer</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="d-flex flex-column min-vh-100" style="background-color: #F8FAFC;">
        @yield('content')
        <script src="https://kit.fontawesome.com/bdc95ccc7e.js" crossorigin="anonymous"></script>
    </body>
</html>