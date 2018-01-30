@inject('compilationService', 'App\Services\CompilationService')

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())

                        <li>{!! link_to_route('login', __('Login')) !!}</li>
                        @if (\Request::is('register') === false)
                            <li>{!! link_to_route('register', __('Register')) !!}</li>
                        @endif

                    @else

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false" aria-haspopup="true">
                                {{ __('Compilations') }}
                                <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                @can('create', App\Models\Compilation::class)
                                @if ($compilationService->isCompilationCreatable() === true)
                                    <li>{!! link_to_route('compilations.create', __('New compilation')) !!}</li>
                                @endif
                                <li>
                                    {!! link_to_route('compilations.index', __('My compilations') . ' (' . Auth::user()->student->compilations->count() . ')') !!}
                                </li>
                                @endcan
                                @can('viewAll', App\Models\Compilation::class)
                                <li>
                                    {!! link_to_route('compilations.index', __('All compilations') . ' (' . \App\Models\Compilation::count() . ')') !!}
                                </li>
                                @endcan
                            </ul>
                        </li>

                        @can('createViewer', App\User::class)
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false" aria-haspopup="true">
                                {{ __('Users') }}
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                @if (Auth::user()->can('createAdministrator', App\User::class))
                                    <li>{!! link_to_route('register', __('Register new viewer or administrator')) !!}</li>
                                    <li>{!! link_to_route('users.index', __('Administrators'), ['role' => 'administrator']) !!}</li>
                                @else
                                    <li>{!! link_to_route('register', __('Register new viewer')) !!}</li>
                                @endif
                                <li>{!! link_to_route('users.index', __('Viewers'), ['role' => 'viewer']) !!}</li>
                                <li>{!! link_to_route('users.index', __('Students'), ['role' => 'student']) !!}</li>
                            </ul>
                        </li>
                        @endcan

                        @can('create', App\Models\Location::class)
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false" aria-haspopup="true">
                                {{ __('Stages') }}
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    {!! link_to_route('locations.index', __('Locations')) !!}
                                </li>
                                <li>
                                    {!! link_to_route('wards.index', __('Wards')) !!}
                                </li>
                            </ul>
                        </li>
                        @endcan

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false" aria-haspopup="true">
                                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                                <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#"
                                       onclick="event.preventDefault();
                                                     alert('{{ __('Currently password can be changed only by password reset procedure (login page)') }}.');">
                                        <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                                        {{ __('Change password') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
