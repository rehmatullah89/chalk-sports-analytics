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
            <div class="card-header">Team
                @can('team-list')
                    <span class="float-right">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('teams.index') }}"><strong>Back</strong></a>
                    </span>
                @endcan
            </div>
            <div class="card-body">
                <div class="lead">
                    <strong>Name:</strong>
                    {{ $team->name }}
                </div>
                <div class="lead">
                    <strong>Ranking:</strong>
                    {{ $team->ranking }}
                </div>
                <div class="lead">
                    <strong>Color:</strong>
                    {{ $team->colors }}
                </div>
                <div class="lead">
                    <strong>Details:</strong>
                    {{ $team->detail }}
                </div>
                <div class="lead">
                    <strong>Logo:</strong>
                    <img src="{{asset('images/logos/'.$team->logo)}}"  class="img-size">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
