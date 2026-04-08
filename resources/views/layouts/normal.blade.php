<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')

    <title>@yield('title', 'SnackFlow')</title>
    @stack('parti')
</head>

<body class="bg-slate-200 min-h-screen overflow-hidden">

    @yield('content')

</body>
</html>