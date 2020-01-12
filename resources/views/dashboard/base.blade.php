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
        <title>DR Social Spot</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/dashboard.css') }}">
    </head>

    <body>
        <div id="main-container" style="height:auto;">
            <div id="newsfeed-container" class="container">
                <mainmenu></mainmenu>

                @yield('main-section')
            </div>
        </div>

        <script src="{{ asset('js/dashboard.js') }}"></script>
    </body>
</html>