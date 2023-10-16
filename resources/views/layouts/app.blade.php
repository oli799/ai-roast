<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config('app.name')}}</title>
    @vite('resources/css/app.css')
    <script src="https://js.stripe.com/v3/"></script>
    @stack('scripts')
</head>

<body>
    @yield('content')
</body>

</html>
