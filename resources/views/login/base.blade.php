<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate"/>
        <meta http-equiv="expires" content="-1" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
        <title>DR Social Spot Login</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}">
    </head>

    <body style="background: url('{{ asset('images/dr-social-spot-background.png') }}') no-repeat fixed center center;">
        <div id="main-container" style="height:auto;">
            {{-- <div class="logo">
                <img src="{{ asset('images/dr-social-spot-logo.png') }}" alt="" width="150" height="150">
            </div> --}}

            <div id="login-container">
                <login></login>
            </div>
        </div>

        <script src="{{ asset('js/login.js') }}"></script>
    </body>
</html>