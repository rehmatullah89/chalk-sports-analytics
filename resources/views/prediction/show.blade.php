@extends('layouts.app')
@section('content')
<script src='https://cdn.plot.ly/plotly-2.16.1.min.js'></script>
<div class="container-fluid">
    <div class="justify-content-center">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Opps!</strong> Something went wrong, please check below errors.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card">
            <div class="card-header">NFL Picks Leaderboard
                <span class="float-right">
                    @hasrole('admin')
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('dashboard') }}"><strong>Dashboard</strong></a>
                    @else
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('home') }}"><strong>Home</strong></a>
                    @endhasrole
                        <a class="btn btn-outline-primary btn-sm" href="{{ url('prediction/'.auth()->user()->id.'/edit') }}"><strong>Your NFL Picks</strong></a>
                </span>
            </div>

            <div class="card-body">

                <div class="row">

                    @foreach($stats as $index => $stat)
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="guage-box">
                                <div class="detail-info d-flex justify-content-between justify-content-center">
                                    <span class="badge bg-secondary">Rank {{$index+1}}</span>
                                    <h3>{{$stat->username}}</h3>
                                </div>
                                <div id='myDiv{{$index}}'><!-- Plotly chart will be drawn inside this DIV --></div>
                                <ul class="list">
                                    <li>Points: <strong>{{number_format($stat->points)}}</strong></li>
                                    <li>Percentile: <strong>{{number_format($stat->overall_percentile)}}</strong><sup>th</sup></li>
                                </ul>
                            </div>
                        </div>
                        <script>
                            var data = [
                                {
                                    type: "indicator",
                                    mode: "gauge+number+delta",
                                    value: {{number_format($stat->points)}},
                                    gauge: {
                                        axis: { range: [null, {{$max_points}}], tickwidth: 2, tickcolor: "black" },
                                        bar: { color: "red" },
                                        bgcolor: "lightgrey",
                                        borderwidth: 0,
                                        bordercolor: "black",
                                    }
                                }
                            ];
                            var layout = { width: 300, height: 250, margin: { t: 0, b: 0 } };
                            Plotly.newPlot('myDiv{{$index}}', data, layout,{displayModeBar: false});
                        </script>
                    @endforeach

                </div><br/><br/>

                <table class="table table-hover" id="dataTable">
                    <thead class="thead-dark">
                    <tr>
                        <th style="text-align: left;">Rank</th>
                        <th>User</th>
                        <th style="text-align: right;">Points</th>
                        <th style="text-align: right;">Correct Winning Team</th>
                        <th style="text-align: right;">Percentile</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(count($user_stats) > 0)
                            @foreach($user_stats as $index => $stat)
                                <tr>
                                    <td style="text-align: left;">{{$index + 1}}</td>
                                    <td>{{$stat->username}}</td>
                                    <td style="text-align: right;">{{number_format($stat->points, 2)}}</td>
                                    <td style="text-align: right;">{{number_format($stat->percent_win_correct)}}</td>
                                    <td style="text-align: right;">{{number_format($stat->overall_percentile)}}<sup>th</sup></td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="5">This User has no NFL Picks.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
