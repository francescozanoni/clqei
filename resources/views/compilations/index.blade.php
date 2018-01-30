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
                                    <th>{{ __('Last name') }}</th>
                                    <th>{{ __('First name') }}</th>
                                @endif
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Stage location') }}</th>
                                <th>{{ __('Stage ward') }}</th>
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
        $.getScript('//cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/dataRender/datetime.js', function () {
            // DataTables is instanced only after its datetime plugin is loaded.
            $('#compilations-table').DataTable({
                serverSide: true,
                processing: true,
                language: {
                    url: window.datatablesLocalizations.{{ config('app.locale') }}
                },
                ajax: '',
                @if (Auth::user()->cannot('viewAll', App\Models\Compilation::class))
                searching: false,
                paging: false,
                info: false,
                @endif
                columnDefs: [{
                    targets: -4,
                    render: $.fn.dataTable.render.moment('DD/MM/YYYY')
                }],
                columns: [
                        @if (Auth::user()->can('viewAll', App\Models\Compilation::class))
                    {
                        data: 'student.identification_number',
                        name: 'student.identification_number'
                    },
                    {
                        data: 'student.user.last_name',
                        name: 'student.user.last_name'
                    },
                    {
                        data: 'student.user.first_name',
                        name: 'student.user.first_name'
                    },
                        @endif
                    {
                        data: 'created_at',
                        name: 'compilations.created_at'
                    },
                    {
                        data: 'stage_location.name',
                        name: 'stageLocation.name'
                    },
                    {
                        data: 'stage_ward.name',
                        name: 'stageWard.name'
                    },
                    {
                        name: 'link_to_detail',
                        render: function (data, type, row) {
                            return '<a href="compilations/' + row['id'] + '" title="{{ __('View') }}">' +
                                    '<span class="glyphicon glyphicon-search" aria-hidden="true"></span>' +
                                    '</a>';
                        },
                        sortable: false,
                        searchable: false
                    }
                ]
            });
        });
    });
</script>
@endpush
