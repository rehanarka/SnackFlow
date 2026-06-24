<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>@yield('title', 'SnackFlow')</title>
    @stack('parti')
</head>

<body class="min-h-screen bg-white">
    <x-sidebar />
    <div class="min-h-screen lg:ml-64">
        <x-header />

        <main class="mt-[104px] px-4 pb-28 pt-5 sm:px-6 lg:mt-[122px] lg:p-10">
            @yield('content')
        </main>
    </div>

</body>
</html>
