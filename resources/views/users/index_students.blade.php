@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
            {{ __('Students') }}
        </div>

        <div class="panel-body">

                <table id="users-table" class="table">

                    <thead>
                    <tr>
                        <th class="hidden-xs">{{ __('Identification number') }}</th>
                        <th>{{ __('First name') }}</th>
                        <th>{{ __('Last name') }}</th>
                        <th></th>
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
        
            $('#users-table').DataTable({
                serverSide: true,
                processing: true,
                language: {
                    url: '{{ route('datatables-language', ['country' => config('app.locale')]) }}'
                },
                ajax: '',
                order: [[0, "desc"]],
                columns: [
                    {
                        data: 'student.identification_number',
                        name: 'student.identification_number',
                        className: "hidden-xs"
                    },
                    {
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        data: 'last_name',
                        name: 'last_name'
                    },
                    {
                        name: 'link_to_detail',
                        render: function (data, type, row) {
                            return '<a href="{{ url('/') }}/users/' + row['id'] + '" title="{{ __('View') }}">' +
                                    '<span class="glyphicon glyphicon-search" aria-hidden="true"></span>' +
                                    '</a>';
                        },
                        sortable: false,
                        searchable: false
                    },
                    {
                        name: 'link_to_deletion',
                        render: function (data, type, row) {
                            return '<form action="{{ url('/') }}/users/' + row['id'] + '" method="POST">' +
                                    '<a href="{{ url('/') }}/users/' + row['id'] + '" ' +
                                    'title="{{ __('Delete') }}" ' +
                                    'onclick="event.preventDefault(); if (confirm(\'{{ __('Do you really want to delete this student?') }}\') !== true) { return; } this.parentElement.submit();" ' +
                                    '>' +
                                    '<!--<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>-->' +
                                    '</a>' +
                                    '<input name="_method" type="hidden" value="DELETE">' +
                                    '</form>';
                        },
                        sortable: false,
                        searchable: false
                    }
                ]
            });
        
    });
</script>
@endpush
