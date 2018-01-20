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
                                @if (Auth::user()->can('viewAll', App\Models\Compilation::class))
                                    <th>{{ __('Identification number') }}</th>
                                    <th>{{ __('Last and first name') }}</th>
                                @endif
                                <th>{{ __('Stage location') }}</th>
                                <th>{{ __('Stage ward') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th></th>
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
    $(function () {
        $('#compilations-table').DataTable({
            serverSide: true,
            processing: true,
            {{-- @todo localize DataTables --}}
            ajax: '',
            columns: [
                    @if (Auth::user()->can('viewAll', App\Models\Compilation::class))
                {
                    data: 'student.identification_number',
                    name: 'student.identification_number'
                },
                {
                    {{-- @todo find how to make this column searchable for both first and last name --}}
                    /*
                    render: function (data, type, row) {
                        return row['student']['user']['last_name'] + ' ' + row['student']['user']['first_name'];
                    },
                    name: 'student.user.last_first_name'
                    */
                    data: 'student.user.last_name',
                    name: 'student.user.last_name'
                },
                    @endif
                {
                    data: 'stage_location.name',
                    name: 'stageLocation.name'
                },
                {
                    data: 'stage_ward.name',
                    name: 'stageWard.name'
                },
                {
                    {{-- @todo format date --}}
                    data: 'created_at',
                    name: 'compilations.created_at'
                },
                {
                    name: 'link_to_detail',
                    render: function (data, type, row) {
                        return '<a href="compilations/' + row['id'] + '">{{ __('View') }}</a>';
                    },
                    sortable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
@endpush
