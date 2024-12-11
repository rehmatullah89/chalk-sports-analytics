@extends('layouts.app')
@section('content')
<style>
    select[name="wrong_predictions_length"], select[name="all_predictions_length"], input[type="search"]{
        color: white;
    }
</style>
<div class="overlay"></div>
<div class="spanner">
    <div class="loader"></div>
    <p>Please wait while Test Report is running!</p>
</div>
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
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif
                <!-- Modal -->
                    <div class="alert alert-success" style="display: none;" id="show_success"></div>
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Run Test Report</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                    <div class="modal-body">
                                        @hasrole('admin')
                                            <div class="form-group">
                                                <div class="d-flex">
                                                    <div class="d-inline-block"  style="width:50%; float:left;">
                                                        <strong>Select User:</strong>
                                                    </div>
                                                    <div class="d-inline-block"  style="width:50%; float:right;">
                                                        {!! Form::select('user_id', $users, 0, array('class' => 'form-select', 'required'=>true, 'id'=>'user_id')) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <input type="hidden" name="user_id" id='user_id' value="{{auth()->user()->id}}">
                                        @endhasrole
                                            <div class="form-group margin-top">
                                                <div class="d-flex">
                                                    <div class="d-inline-block"  style="width:50%; float:left;">
                                                        <strong>Select Season:</strong>
                                                    </div>
                                                    <div class="d-inline-block" style="width:50%; float:right;">
                                                        {!! Form::select('season_id', $seasons, $season_id, array('class' => 'form-select', 'required'=>true, 'id'=>'season_id')) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group margin-top">
                                                <div class="d-flex">
                                                    <div class="d-inline-block"  style="width:50%; float:left;">
                                                        <label for="week_no">Select Week:</label>
                                                    </div>
                                                    <div class="d-inline-block"  style="width:50%; float:right;">
                                                        {!! Form::select('week_no', [0=>'All Weeks Selected']+$weeks, 0, array('class' => 'form-select','id'=>'week_no')) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group margin-top">
                                                <div class="d-flex">
                                                    <div class="d-inline-block"  style="width:50%; float:left;">
                                                        <label for="team_1">Select Team 1:</label>
                                                    </div>
                                                    <div class="d-inline-block"  style="width:50%; float:right;">
                                                        {!! Form::select('team_1', [0=>'All Teams Selected']+$team_list, 0, array('class' => 'form-select','id'=>'team_1')) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group margin-top">
                                                <div class="d-flex">
                                                    <div class="d-inline-block"  style="width:50%; float:left;">
                                                        <label for="team_2">Select Team 2:</label>
                                                    </div>
                                                    <div class="d-inline-block"  style="width:50%; float:right;">
                                                        {!! Form::select('team_2', [0=>'All Teams Selected']+$team_list, 0, array('class' => 'form-select','id'=>'team_2')) !!}
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal" id="run-test-report">Run Report</button>
                                    </div>
                            </div>
                        </div>
                    </div>

        <div class="card">
            <div class="card-header">
                Predictive Model Test Report
                <span class="float-right">
                {{--
                    @can('run-test')
                        <a class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" id="runTestReportModal" data-bs-target="#exampleModal"><strong>Run Test Report</strong></a>
                    @endcan
                    --}}
                        @if(auth()->user()->hasRole('admin'))
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('dashboard') }}"><strong>Dashboard</strong></a>
                        @endif
                </span>
                <br/><span style="font-size: 12px;">
                    <em style="display: block; line-height: 15px;">User: {{@$users[$summary->user_id]}}</em>
                    <em style="display: block; line-height: 15px;">Season: {{@$seasons[$summary->season_id]}}</em>
                    <em style="display: block; line-height: 15px;">Week: {{@$summary->week_number}}</em>
                    <em style="display: block; line-height: 15px;">HomeTeam: {{@$team_list[$summary->team_1_id]}}</em>
                    <em style="display: block; line-height: 15px;">AwayTeam: {{@$team_list[$summary->team_2_id]}}</em>
                </span>
            </div>

            <div class="card-body">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="chart-tab" data-bs-toggle="tab" data-bs-target="#chart" type="button" role="tab" aria-controls="chart" aria-selected="true">Prediction Graph</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Teams</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Wrong Predictions</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">All Predictions</button>
                        </li>
                    </ul>
                    <div class="tab-content mb-5" id="myTabContent">
                        {{-- Zero Tab --}}
                        <div class="tab-pane fade show active" id="chart" role="tabpanel" aria-labelledby="chart-tab">
                            @include('games.chart')
                        </div>

                        {{-- First Tab --}}
                        <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card-body">
                                <table class="table table-hover">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Team</th>
                                        <th style="text-align: right;">Number Wins</th>
                                        <th style="text-align: right;">Number Loses</th>
                                        <th style="text-align: right;">Percent Wins</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($teams as $key => $object)
                                        <tr>
                                            <td><strong>{{ $object->team_name }}</strong></td>
                                            <td style="text-align: right;">{{ $object->wins }}</td>
                                            <td style="text-align: right;">{{ $object->count - $object->wins }}</td>
                                            <td style="text-align: right;">{{ number_format(($object->wins/$object->count)*100) }}%</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Second Tab --}}
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab" id="table_data">
                            @include('games.wrong_predictions')
                        </div>

                        {{-- Third Tab --}}
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab" id="table_data2">
                            @include('games.all_predictions')
                        </div>
                        <style>
                            .spanner{
                                position:absolute;
                                top: 50%;
                                left: 0;
                                background: #2a2a2a55;
                                width: 100%;
                                display:block;
                                text-align:center;
                                height: 100%;
                                color: #FFF;
                                transform: translateY(-50%);
                                z-index: 1000;
                                visibility: hidden;
                            }
                            .overlay{
                                position: fixed;
                                width: 100%;
                                height: 100%;
                                background: rgba(0,0,0,0.5);
                                visibility: hidden;
                            }
                            .loader,
                            .loader:before,
                            .loader:after {
                                border-radius: 50%;
                                width: 2.5em;
                                height: 2.5em;
                                -webkit-animation-fill-mode: both;
                                animation-fill-mode: both;
                                -webkit-animation: load7 1.8s infinite ease-in-out;
                                animation: load7 1.8s infinite ease-in-out;
                            }
                            .loader {
                                color: #ffffff;
                                font-size: 10px;
                                margin: 80px auto;
                                position: relative;
                                text-indent: -9999em;
                                -webkit-transform: translateZ(0);
                                -ms-transform: translateZ(0);
                                transform: translateZ(0);
                                -webkit-animation-delay: -0.16s;
                                animation-delay: -0.16s;
                            }
                            .loader:before,
                            .loader:after {
                                content: '';
                                position: absolute;
                                top: 0;
                            }
                            .loader:before {
                                left: -3.5em;
                                -webkit-animation-delay: -0.32s;
                                animation-delay: -0.32s;
                            }
                            .loader:after {
                                left: 3.5em;
                            }
                            @-webkit-keyframes load7 {
                                0%,
                                80%,
                                100% {
                                    box-shadow: 0 2.5em 0 -1.3em;
                                }
                                40% {
                                    box-shadow: 0 2.5em 0 0;
                                }
                            }
                            @keyframes load7 {
                                0%,
                                80%,
                                100% {
                                    box-shadow: 0 2.5em 0 -1.3em;
                                }
                                40% {
                                    box-shadow: 0 2.5em 0 0;
                                }
                            }
                            .show{
                                visibility: visible;
                            }
                            .spanner, .overlay{
                                opacity: 0;
                                -webkit-transition: all 0.3s;
                                -moz-transition: all 0.3s;
                                transition: all 0.3s;
                            }

                            .spanner.show, .overlay.show {
                                opacity: 1
                            }
                         </style>
                        <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
                        <style>
                            .dataTables_wrapper .dataTables_paginate .paginate_button{
                                color: black !important;
                                border: none;
                                background-color: white !important;
                            }
                            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                                color: white !important;
                                border: none;
                                background-color: #585858;
                                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #585858), color-stop(100%, #111));
                                /* Chrome,Safari4+ */
                                background: -webkit-linear-gradient(top, #585858 0%, #111 100%);
                                /* Chrome10+,Safari5.1+ */
                                background: -moz-linear-gradient(top, #585858 0%, #111 100%);
                                /* FF3.6+ */
                                background: -ms-linear-gradient(top, #585858 0%, #111 100%);
                                /* IE10+ */
                                background: -o-linear-gradient(top, #585858 0%, #111 100%);
                                /* Opera 11.10+ */
                                background: linear-gradient(to bottom, #585858 0%, #111 100%);
                                /* W3C */
                            }
                        </style>
                        <script src="{!! asset('js/jquery.min.js') !!}"></script>
                        <script src="{!! asset('js/jquery.dataTables.min.js') !!}"></script>
                        <script>
                            $(document).ready(function(){

                                $('#all_predictions').DataTable();
                                $('#wrong_predictions').DataTable();

                                $(document).on('click', '#run-test-report', function(event){
                                    $("div.spanner").addClass("show");
                                    $("div.overlay").addClass("show");
                                    document.getElementById("runTestReportModal").disabled = true;
                                    $.ajax({
                                        type:'POST',
                                        url:'/run-test-report',
                                        data: {
                                            "_token": "{{ csrf_token() }}",
                                            "season_id": $('#season_id').val(),
                                            "week_number": $('#week_no').val(),
                                            "team_1_id": $('#team_1').val(),
                                            "team_2_id": $('#team_2').val(),
                                            "user_id": $('#user_id').val(),
                                        },
                                        success:function(data) {
                                            $('#show_success').show();

                                            if(data.includes("Error")) {
                                                $('#show_success').html('<p>There is some error occurred while executing test script, Please try again later.</p>');
                                                $("div.spanner").removeClass("show");
                                                $("div.overlay").removeClass("show");
                                            }
                                            else
                                                $('#show_success').html('<p>Test Report Executed Successfully</p>');

                                            document.getElementById("runTestReportModal").disabled = false;
                                            setTimeout(function() {
                                                window.location.href = "{{route('run-test')}}";
                                            }, 9000);
                                        }
                                    });
                                });

                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
