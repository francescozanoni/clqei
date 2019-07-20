@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
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
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Identification number') }}</th>
                    <th>{{ __('Last name') }}</th>
                    <th>{{ __('First name') }}</th>
                    <th>
                        <span class="hidden-xs hidden-sm">{{ __('Stage location') }}</span>
                        <span class="hidden-md hidden-lg">{{ __('Location') }}</span>
                    </th>
                    <th>
                        <span class="hidden-xs hidden-sm">{{ __('Stage ward') }}</span>
                        <span class="hidden-md hidden-lg">{{ __('Ward') }}</span>
                    </th>
                    <th>{{ __('Weeks') }}</th>
                    @if (Auth::user()->can('viewAny', App\Models\Compilation::class))
                        <th></th>
                        <th class="hidden-xs"></th>
                    @endif
                </tr>
                </thead>
            </table>

        </div>

    </div>

@endsection

@push('styles')
<link href="{{ asset('css/lists.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/lists.js') }}"></script>
<script>
    $(function () {
        $.getScript('{{ route('datatables-datetime') }}', function () {
            // DataTables is instanced only after its datetime plugin is loaded.
            $('#compilations-table').DataTable({
                serverSide: true,
                processing: true,
                language: {
                    url: '{{ route('datatables-language', ['country' => config('app.locale')]) }}'
                },
                ajax: '',
                order: [[0, "desc"]],
                columns: [
                    // short date
                    {
                        data: 'created_at',
                        name: 'compilations.created_at',
                        render: $.fn.dataTable.render.moment('DD/MM/YY'),
                        className: "hidden-md hidden-lg"
                    },
                    // long date
                    {
                        data: 'created_at',
                        name: 'compilations.created_at',
                        render: $.fn.dataTable.render.moment('DD/MM/YYYY'),
                        className: "hidden-xs hidden-sm"
                    },
                    {
                        data: 'student.identification_number',
                        name: 'student.identification_number',
                        className: "hidden-xs hidden-sm"
                    },
                    {
                        data: 'student.user.last_name',
                        name: 'student.user.last_name'
                    },
                    {
                        data: 'student.user.first_name',
                        name: 'student.user.first_name',
                        className: "hidden-xs"
                    },
                    {
                        data: 'stage_location.name',
                        name: 'stageLocation.name',
                        className: "hidden-xs"
                    },
                    {
                        data: 'stage_ward.name',
                        name: 'stageWard.name',
                        className: "hidden-xs"
                    },
                    {
                        data: 'stage_weeks',
                        name: 'compilations.stage_weeks',
                        searchable: false,
                        className: "hidden-xs hidden-sm"
                    }
                    @if (Auth::user()->can('viewAny', App\Models\Compilation::class))
                    ,
                    {
                        name: 'link_to_detail',
                        render: function (data, type, row) {
                            var url = '{{ url('/') }}/compilations/' + row['id'];
                            return '<a href="' + url + '" title="{{ __('View') }}">' +
                                    '<span class="glyphicon glyphicon-search" aria-hidden="true"></span>' +
                                    '</a>';
                        },
                        sortable: false,
                        searchable: false
                    },
                    {
                        name: 'link_to_receipt',
                        render: function (data, type, row) {
                            var url = '{{ url('/') }}/compilations/' + row['id'];
                            return '<a href="' + url + '?receipt" title="{{ __('Print compilation receipt') }}" target="_blank">' +
                                    '<span class="glyphicon glyphicon-print" aria-hidden="true"></span>' +
                                    '</a>';
                        },
                        sortable: false,
                        searchable: false,
                        className: "hidden-xs"
                    }
                    @endif
                ]
            });
        });
    });
</script>
@endpush
