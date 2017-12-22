@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">Create</div>

                    <div class="panel-body">

                        {!! Form::open(['route' => 'compilations.store', 'method' => 'post']) !!}

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist" id="myTabs">
                            @foreach ($sections as $section)
                                <li role="presentation"
                                    @if ($section->id === 1)
                                    class="active"
                                        @endif
                                >
                                    <a href="#section_{{ $section->id }}" aria-controls="section_{{ $section->id }}"
                                       role="tab"
                                       data-toggle="tab">{{ $section->text }}</a>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            @foreach ($sections as $section)
                                <div role="tabpanel"
                                     @if ($section->id === 1)
                                     class="tab-pane active"
                                     @else
                                     class="tab-pane"
                                     @endif
                                     id="section_{{ $section->id }}">
                                    {{ $section->text }}
                                </div>
                            @endforeach
                        </div>

                        {!! Form::token() !!}
                        {!! Form::submit('Create!') !!}
                        {!! Form::close() !!}

                                <!--
                        <form class="form-horizontal" method="POST" action="{{ route('compilations.store') }}">
                            {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>
                                </div>
                            </div>
                        </form>
                        -->

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
