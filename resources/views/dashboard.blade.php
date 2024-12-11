@extends('layouts.app')

@section('content')
    @hasrole('admin')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>
                </div>
            </div>

            <div class="row" style="margin-top: 15px;">
                @can('prediction-list')
                    <div class="col-md-3 col-sm-6 col-12">
                        <a style="text-decoration: none;" href="{{ route('prediction.index') }}">
                            <div class="dashboard-card">
                                <h2>NFL Picks Leaderboard</h2>
                                <i class="fa fa-arrow-circle-o-right"></i>
                            </div>
                        </a>
                    </div>
                @endcan

                @can('prediction-create')
                    <div class="col-md-3 col-sm-6 col-12">
                        <a style="text-decoration: none;" href="{{ route('prediction.create') }}">
                            <div class="dashboard-card">
                                <h2>Custom NFL Predictive Model</h2>
                                <i class="fa fa-arrow-circle-o-right"></i>
                            </div>
                        </a>
                    </div>
                @endcan

                <div class="col-md-3 col-sm-6 col-12">
                    <a style="text-decoration: none;" href="{{ route('prediction.edit',auth()->user()->id) }}">
                        <div class="dashboard-card">
                            <h2>Your NFL Picks</h2>
                            <i class="fa fa-arrow-circle-o-right"></i>
                        </div>
                    </a>
                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <a style="text-decoration: none;" href="{{ route('games.index') }}">
                        <div class="dashboard-card">
                            <h2>NFL Schedules</h2>
                            <i class="fa fa-arrow-circle-o-right"></i>
                        </div>
                    </a>
                </div>

            </div>
        </div>
        @else
            @include('user_dashboard')
        @endhasrole
@endsection
