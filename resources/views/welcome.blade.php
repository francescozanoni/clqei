<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .my-header {
            height: 15vh;
        }

        .my-body {
            height: 75vh;
        }

        .my-footer {
            height: 10vh;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 10vh;
        }

        .subtitle {
            font-size: 5vh;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
        }

        .my-body .links button {
            margin-top: 3rem;
            width: 100px;
        }

        #logo {
            height: 13vh;
        }
    </style>

    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon">
</head>

<body>

<div class="flex-center position-ref my-header">
    <div class="content">
        @foreach(['svg', 'png', 'jpg'] as $extension)
            @if(file_exists(public_path('logo.' . $extension)) === true)
                @if (config('clqei.educational_institution.url') !== '')
                    <a href="{{ config('clqei.educational_institution.url') }}" target="_blank">
                        <img id="logo" src="{{ asset('logo.' . $extension) }}"/>
                    </a>
                @else
                    <img id="logo" src="{{ asset('logo.' . $extension) }}"/>
                @endif
                @break
            @endif
        @endforeach
    </div>
</div>

<div class="flex-center position-ref my-body">

    <div class="content">

        <div class="title">
            {{ config('app.name') }}
        </div>

        <div class="subtitle">
            {{ config('app.name_extended') }}
        </div>
<!--        
        <div>
            {{ __('The Clinical Learning Quality Environment Inventory is a validated instrument that measures the clinical learning quality as experienced by nursing students, according to five factors') }}:
            <ul>
                <li>{{ __('quality of tutorial strategies') }},</li>
                <li>{{ __('learning opportunities') }},</li>
                <li>{{ __('safety and nursing care quality') }},</li>
                <li>{{ __('self-direct learning') }},</li>
                <li>{{ __('quality of the learning environment') }}.</li>
            </ul>
        </div>
-->
        <div class="links">
            @if (Auth::guest())
                <button class="btn btn-primary" onclick="location.href = '{{ route('login')}}'">
                    <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
                    {{ __('Login') }}
                </button>
                <button class="btn btn-primary" onclick="location.href = '{{ route('register') }}'">
                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    {{ __('Register') }}
                </button>
            @else
                <button class="btn btn-primary" onclick="location.href = '{{ url('/home') }}'">
                    <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                    {{ __('Home') }}
                </button>
            @endif
        </div>

    </div>
</div>

<div class="flex-center position-ref my-footer">
    <div class="content links">
        {!! link_to(
        config('copyright.url'),
        '&copy; ' . config('copyright.year') . ' ' . config('copyright.author')
        )!!}
    </div>
</div>

</body>

</html>
