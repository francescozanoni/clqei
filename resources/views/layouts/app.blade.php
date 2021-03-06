@inject('compilationService', 'App\Services\CompilationService')

        <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name') }} - {{ config('app.name_extended') }}
    </title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')

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
                    <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                    {{ config('app.name') }}
                    <small class="hidden-xs hidden-sm">- {{ config('app.name_extended') }}</small>
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">

                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">&nbsp;</ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">

                    <!-- Authentication Links -->
                    @if (Auth::guest())

                        @if (\Request::is('login') === false)
                            <li>
                                <a href="{{ route('login') }}">
                                    <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
                                    {{ __('Login') }}
                                </a>
                            </li>
                        @endif
                        @if (\Request::is('register') === false)
                            <li>
                                <a href="{{ route('register') }}">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    {{ __('Register') }}
                                </a>
                            </li>
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
                                    <li>
                                        <a href="{{ route('compilations.create') }}">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            {{ __('New compilation') }}
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('compilations.index') }}">
                                        <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                        {{ __('My compilations') . ' (' . Auth::user()->student->compilations->count() . ')' }}
                                    </a>
                                </li>
                                @endcan
                                @cannot('create', App\Models\Compilation::class)
                                <li>
                                    <a href="{{ route('compilations.create') }}">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        {{ __('Compilation form') }}
                                    </a>
                                </li>
                                @endcannot
                                @can('viewAll', App\Models\Compilation::class)
                                <li>
                                    <a href="{{ route('compilations.index') }}">
                                        <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                        {{ __('All compilations') . ' (' . \App\Models\Compilation::count() . ')' }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('compilations.statistics_charts') }}">
                                        <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                                        {{ __('Statistics') }}
                                    </a>
                                </li>
                                @endif
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
                                    <li>
                                        <a href="{{ route('register') }}">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            {{ __('Register new viewer or administrator') }}
                                        </a>
                                    <li>
                                        <a href="{{ route('users.index', ['role' => \App\User::ROLE_ADMINISTRATOR]) }}">
                                            <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                            {{ __('Administrators') . ' (' . \App\User::administrators()->count() . ')' }}
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('register') }}">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            {{ __('Register new viewer') }}
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('users.index', ['role' => \App\User::ROLE_VIEWER]) }}">
                                        <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                        {{ __('Viewers') . ' (' . \App\User::viewers()->count() . ')' }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('users.index', ['role' => \App\User::ROLE_STUDENT]) }}">
                                        <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                        {{ __('Students') . ' (' . \App\User::students()->count() . ')'  }}
                                    </a>
                                </li>
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
                                    <a href="{{ route('locations.index') }}">
                                        <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                        {{ __('Locations') . ' (' . \App\Models\Location::count() . ')' }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('wards.index') }}">
                                        <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                        {{ __('Wards') . ' (' . \App\Models\Ward::count() . ')' }}
                                    </a>
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
                                    <a href="{{ route('users.show', ['user' => Auth::user()]) }}">
                                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                        {{ __('Profile') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                       onclick="event.preventDefault(); alert('{{ __('Currently password can be changed only by password reset procedure (login page)') }}.');">
                                        <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                                        {{ __('Change password') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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

    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-xs-offset-0 col-sm-12 col-sm-offset-0 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">

                @if ($compilationService->isCompilationCreatable() === false)
                    @cannot('create', App\Models\Compilation::class)
                    <div class="alert alert-danger" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">Error:</span>
                        {{ __('Currently no new compilations can be added: ensure at least one stage location and one stage ward are available') }}
                    </div>
                    @endcan
                @endif

                @if (session('status'))
                    <div class="alert alert-success hidden-print">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session(\App\Observers\EloquentModelObserver::FLASH_MESSAGE_KEY))
                    <div class="alert alert-success hidden-print">
                        {{ session(\App\Observers\EloquentModelObserver::FLASH_MESSAGE_KEY) }}
                    </div>
                @endif

                @yield('content')

            </div>
        </div>
    </div>

</div>

<!-- Scripts -->
@if(app('env') === 'local')
    {{-- On development environment, JavaScript errors are reported via alert box --}}
    <script src="{{ asset('js/error_handler.js') }}"></script>
@endif
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
