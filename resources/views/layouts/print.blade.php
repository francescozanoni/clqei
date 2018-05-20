<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        {{ config('app.name') }}
        -
        {{ config('app.name_extended') }}
    </title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/print.css') }}" rel="stylesheet">

    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon">
</head>
<body>
<div id="app">

    <div class="container">
        <div class="row">
            @yield('content')
        </div>
    </div>

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script>
$(document).ready(function() {
    // https://stackoverflow.com/questions/18325025/how-to-detect-window-print-finish
    // Print of a printable page is achieved by a link to a new window containing the printable page,
    // which triggers the print itself and then closes its new window.
    // This seems to be the most cross-browser-compatible solution.
    window.print();
    setTimeout(function() { window.close(); }, 100);
});
</script>
@stack('scripts')
</body>
</html>
