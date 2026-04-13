<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')

    <title>@yield('title', 'SnackFlow')</title>
    @stack('parti')
</head>

<body class="bg-white min-h-screen ">
    <x-sidebar />
    <div class="ml-64 min-h-screen">
        <x-header />

        <main class="p-10">
            @yield('content')
        </main>
    </div>

</body>
</html>