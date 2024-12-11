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
            <div class="card-header">Users
                <span class="float-right">
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('users.create') }}"><strong>New User</strong></a>
                </span>
            </div>
            <div class="card-body">
                <div class="scroll-on-mobile">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if(!empty($user->getRoleNames()))
                                        @foreach($user->getRoleNames() as $val)
                                            <label class="">{{ $val }}</label>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-sm" title="View Details" href="{{ route('users.show',$user->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            @if($user->id != 0)
                                    @can('user-edit')
                                        <a class="btn btn-sm" title="Edit Details" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    @endcan
                                    @can('user-delete')
                                        {!! Form::open(['method' => 'DELETE', 'onsubmit'=>'return confirm("Please confirm you want to delete this user!");', 'route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                                        <button type="submit" title='Delete' class="btn btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        {!! Form::close() !!}
                                    @endcan
                            @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                {{ $data->render() }}
            </div>
        </div>
    </div>
</div>
@endsection
