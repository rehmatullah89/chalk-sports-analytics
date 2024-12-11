@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="justify-content-center">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>{{ \Session::get('success') }}</p>
            </div>
        @endif
        <div class="card">
            <div class="card-header">Permissions
                @can('role-create')
                    <span class="float-right">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('permissions.create') }}"><strong>New Permission</strong></a>
                    </span>
                @endcan
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $permission)
                            <tr>
                                <td>{{ $permission->id }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    <a class="btn btn-sm" title="View Details" href="{{ route('permissions.show',$permission->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                    @can('role-edit')
                                        <a class="btn btn-sm" title="Edit Details" href="{{ route('permissions.edit',$permission->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    @endcan
                                    @can('role-delete')
                                        {!! Form::open(['method' => 'DELETE', 'onsubmit'=>'return confirm("Please confirm you want to delete this permission!");', 'route' => ['permissions.destroy', $permission->id],'style'=>'display:inline']) !!}
                                        <button type="submit" title='Delete' class="btn btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        {!! Form::close() !!}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $data->appends($_GET)->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
