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
                    <th>{{ __('Identification number') }}</th>
                    <th>{{ __('Last name') }}</th>
                    <th>{{ __('First name') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>
                        <span class="hidden-xs hidden-sm">{{ __('Stage location') }}</span>
                        <span class="hidden-md hidden-lg">{{ __('Location') }}</span>
                    </th>
                    <th>
                        <span class="hidden-xs hidden-sm">{{ __('Stage ward') }}</span>
                        <span class="hidden-md hidden-lg">{{ __('Ward') }}</span>
                    </th>
                    <th class="hidden-xs hidden-sm">{{ __('Weeks') }}</th>
                    <th></th>
                </tr>
                </thead>
            </table>

        </div>

    </div>

@endsection

@push('scripts')
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
                columnDefs: [
                    {
                        targets: [0, 2, 4, 5],
                        className: "hidden-xs"
                    },
                    {
                        targets: [6],
                        className: "hidden-xs hidden-sm"
                    }
                ],
                columns: [
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
                    {
                        data: 'created_at',
                        name: 'compilations.created_at',
                        render: $.fn.dataTable.render.moment('DD/MM/YYYY')
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
                        data: 'stage_weeks',
                        name: 'compilations.stage_weeks',
                        searchable: false
                    },
                    {
                        name: 'link_to_detail',
                        render: function (data, type, row) {
                            return '<a href="{{ url('/') }}/compilations/' + row['id'] + '" title="{{ __('View') }}">' +
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
