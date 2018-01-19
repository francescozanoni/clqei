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
                    
                    
                    <table id="users-table" class="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
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
    $('#users-table').DataTable({
        serverSide: true,
        processing: true,
        ajax: '',
        columns: [
            {data: 'id', name: 'compilations.id'},
            {data: 'student.first_name', name: 'student.first_name'},
            {data: 'ward.name', name: 'ward.name'},
            {data: 'created_at', name: 'compilations.created_at'}
        ]
    });
});
</script>
@endpush