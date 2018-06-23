@inject('countryService', 'App\Services\CountryService')

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
            {{ $user->first_name . ' ' . $user->last_name }}
        </div>

        <div class="panel-body">

            <table class="table table-striped table-condensed">
                <thead>

                </thead>
                <tbody>
                <tr>
                    <td>{{ __('First name') }}</td>
                    <td>{{ $user->first_name }}</td>
                </tr>
                <tr>
                    <td>{{ __('Last name') }}</td>
                    <td>{{ $user->last_name }}</td>
                </tr>
                <tr>
                    <td>{{ __('E-mail address') }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
                {{-- Only users allowed to create viewers can see users' role --}}
                @can('createViewer', App\User::class)
                <tr>
                    <td>{{ __('Role') }}</td>
                    <td>{{ __($user->role) }}</td>
                </tr>
                @endcan
                @if ($user->role === \App\User::ROLE_STUDENT)
                    <tr>
                        <td>{{ __('Identification number') }}</td>
                        <td>{{ $user->student->identification_number }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Gender') }}</td>
                        <td>{{ __($user->student->gender) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Nationality') }}</td>
                        <td>{{ $countryService->getCountries()[$user->student->nationality] }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
            
            {{-- Only users allowed to create viewers can see student's compilations on this page --}}
            @can('createViewer', App\User::class)
                @if ($user->role === \App\User::ROLE_STUDENT && $user->student->compilations->count() > 0)
                
                    <br />
                    
                    <h3>{{ __('Compilations') }}</h3>   
                    
                    <table class="table">

                    <thead>
                    <tr>
                        <th>
                        <span class="hidden-xs hidden-sm">{{ __('Stage location') }}</span>
                        <span class="hidden-md hidden-lg">{{ __('Location') }}</span>
                    </th>
                    <th>
                        <span class="hidden-xs hidden-sm">{{ __('Stage ward') }}</span>
                        <span class="hidden-md hidden-lg">{{ __('Ward') }}</span>
                    </th>
                        <th>{{ __('Date') }}</th>
                        <th></th>
                        <th class="hidden-xs"></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($user->student->compilations as $compilation)
                        <tr>
                            <td>{{ $compilation->stageLocation->name }}</td>
                            <td>{{ $compilation->stageWard->name }}</td>
                            <td>
                                <span class="hidden-xs">
                                    {{ (new Carbon\Carbon($compilation->created_at))->format('d/m/Y') }}
                                </span>
                                <span class="visible-xs-inline">                        
                                    {{ (new Carbon\Carbon($compilation->created_at))->format('d/m/y') }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('compilations.show', ['compilation' => $compilation]) }}"
                                   title="{{ __('View') }}">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                </a>
                            </td>
                            <td class="hidden-xs">
                                <a href="{{ route('compilations.show', ['compilation' => $compilation]) }}?receipt"
                                   title="{{ __('Print compilation receipt') }}"
                                   target="_blank">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
                @endif
            @endcan

        </div>

    </div>

@endsection
