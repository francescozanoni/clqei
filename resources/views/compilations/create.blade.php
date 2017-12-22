@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create</div>

                    <div class="panel-body">

                        {!! Form::open(['route' => 'compilations.store', 'method' => 'post']) !!}
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
