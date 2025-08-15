<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/modern.css') }}" rel="stylesheet">
</head>
<body class="tw-bg-gray-100 tw-font-sans tw-antialiased">
    <nav class="tw-bg-white tw-shadow tw-p-4">
        <div class="tw-container tw-mx-auto">
            <a href="/" class="tw-text-lg tw-font-semibold">Home</a>
        </div>
    </nav>
    <main class="tw-container tw-mx-auto tw-p-4">
        @yield('content')
    </main>
</body>
</html>
