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
            <div class="card-header">Teams
                @can('role-create')
                    <span class="float-right">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('teams.create') }}"><strong>New Team</strong></a>
                    </span>
                @endcan
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Logo</th>
                            <th style="text-align: center;">Ranking</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $key => $team)
                            <tr>
                                <td>{{ $team->name }}</td>
                                <td><img src="{{asset('images/logos/'.$team->logo)}}"  class="img-size"></td>
                                <td style="text-align: center;">{{ $team->ranking }}</td>
                                <td class="team-action-btn">
                                    <a class="btn btn-sm" title="View Details" href="{{ route('teams.show',$team->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                    @can('role-edit')
                                        <a class="btn btn-sm" title="Edit Details" href="{{ route('teams.edit',$team->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    @endcan
                                    @can('role-delete')
                                        {!! Form::open(['method' => 'DELETE', 'onsubmit'=>'return confirm("Please confirm you want to delete this team!");', 'route' => ['teams.destroy', $team->id],'style'=>'display:inline']) !!}
                                        <button type="submit" title='Delete' class="btn btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        {!! Form::close() !!}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
