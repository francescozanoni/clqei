@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        @if (Auth::user()->can('viewAll', App\Models\Compilation::class))
                            {{ __('All compilations') }}
                        @else
                            {{ __('My compilations') }}
                        @endif
                    </div>

                    <div class="panel-body">


                    <table id="compilations-table" class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Stage location') }}</th>
                                <th>{{ __('Stage ward') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                        </thead>
                    </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(function() {
    $('#compilations-table').DataTable({
        serverSide: true,
        processing: true,
        ajax: '',
        columns: [
            {data: 'stage_location.name', name: 'stageLocation.name'},
            {data: 'stage_ward.name', name: 'stageWard.name'},
            {data: 'created_at', name: 'compilations.created_at'}
        ]
    });
});
</script>
@endpush
