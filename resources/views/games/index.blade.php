@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="justify-content-center">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>{{ \Session::get('success') }}</p>
            </div>
        @endif
        <div class="card schedules-page">
            <div class="card-header">NFL Schedules
                    <span class="float-right">
                    @hasrole('admin')
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('dashboard') }}"><strong>Dashboard</strong></a>
                    @else
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('home') }}"><strong>Home</strong></a>
                    @endhasrole
                    </span>
            </div>

            <div class="card-body">
                <div class="d-flex col-xs-12 ">
                    <div class="d-inline-block" style="width: 100px; float:left;">
                        <div class="form-group" style="text-align: center;">
                            <label for="pwd">Season</label>
                            {!! Form::select('season_id', $seasons, $season_id, array('class' => 'form-select drop-down', 'id'=>'season_id', 'onchange'=>'getData();')) !!}
                        </div>
                    </div>&nbsp;&nbsp;
                    <div class="d-inline-block"  style="width: 100px; float:left;">
                        <div class="form-group" style="text-align: center;">
                            <label for="week_no">Week</label>
                            {!! Form::select('week_no', $weeks, $week_number, array('class' => 'form-select drop-down','id'=>'pre_week_number', 'onchange'=>'getData();')) !!}
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-hover schedule-table schedule-table-mobile" id="dataTable" style="font-size: 18px; font-weight: 400;">
                        <thead class="thead-dark">
                            <th colspan="3"></th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div style="margin-top: 5px;">

                <script src="{!! asset('js/jquery.min.js') !!}"></script>
                <script>
                    function getData(){
                        $("#dataTable tbody").html("");
                        var weekNo = $('#pre_week_number').val();
                        var seasonId = $('#season_id').val();

                        if(weekNo == null || seasonId == null){
                            weekNo = {{$week_number}};
                            seasonId = 1;
                        }

                        $.ajax({
                            type:"GET",
                            url:"{{url('games')}}/"+weekNo+"_"+seasonId,
                            success: function(data) {
                                var html = ``;
                                //console.log(data);
                                data.forEach(function callback(value, index) {
                                    html += `<tr><td style="font-size: 14px;" class="align-middle">` + value.game_date + `</td><td class="align-middle"><div class="d-flex align-items-center internal-schedule"><div class="match-info add-style"><div class="team-name"><h3 class="hide-on-mobile">`+value.team_2+`</h3><h3 class="show-on-mobile">`+value.team_2_short+`</h3><div class="d-flex hide-on-mobile">`+value.away_ats+`</div></div><div class="team-logo add-margin"><img class="img-size" src="` + value.logo_2 + `" ></div><div class="team-score">`+value.team_2_score+`</div></div><div class="match-info right"><div class="team-score">`+value.team_1_score+`</div><div class="team-logo"><img class="img-size" src="` + value.logo_1 + `" ></div><div class="team-name"><h3 class="hide-on-mobile">`+value.team_1+`</h3><h3 class="show-on-mobile">`+value.team_1_short+`</h3><div class="d-flex hide-on-mobile">`+value.home_ats+`</div></div></div></div></td><td class="align-middle" style="text-align: right;">`;
                                    html += `<a class="btn btn-outline-primary btn-sm" style="border-radius: 20px;" href="/game-report/`+value.game_id+`"><strong>View Game</strong></a>&nbsp;`;
                                    @can('free-game')
                                    if(value.free_game == 'Y'){
                                        html += `<form class="form-inline btn-group" method="POST" action="` + "<?php echo e(route('free-game')); ?>" + `" onsubmit="return confirm('Are you sure you want to remove from free game?');"><input type="hidden" value="N" name="free_game"><input type="hidden" name="game_id" value="` + value.game_id + `">` + '{{ csrf_field() }}' + `<button type="submit" style="border-radius: 20px; font-weight: bold; padding:10px; min-width:120px;" class="btn btn-danger">Free Game!</button></form>&nbsp;`;
                                    }else {
                                        html += `<form class="form-inline btn-group" method="POST" action="` + "<?php echo e(route('free-game')); ?>" + `" onsubmit="return confirm('Are you sure you want to mark it as free game?');"><input type="hidden" value="Y" name="free_game"><input type="hidden" name="game_id" value="` + value.game_id + `">` + '{{ csrf_field() }}' + `<button type="submit" style="border-radius: 20px; font-weight: bold;" class="btn btn-outline-primary btn-sm">Free Game!</button></form>&nbsp;`;
                                    }
                                    @endcan
                                    @can('influence-factor-update')
                                    html += `<a class="btn btn-outline-primary btn-sm" style="border-radius: 20px;" onclick="setPreCookie(`+value.team_1_id+`,`+value.team_2_id+`)" href="/prediction/create"><strong>Adjust Ratings</strong></a>`;
                                    @endcan
                                    html += `</td>`;
                                });
                                $("#dataTable tbody").append(html);
                            }
                        });
                    }

                    function setPreCookie(team1,team2){
                        setCookie('home_team_id',team1,1);
                        setCookie('away_team_id',team2,1);
                        setCookie('pre_week_number',document.getElementById("pre_week_number").value,1);
                        setCookie('pre_season_id',document.getElementById("season_id").value,1);
                    }

                    function setCookie(cname, cvalue, exdays) {
                        const d = new Date();
                        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                        let expires = "expires="+d.toUTCString();
                        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                    }

                    getData();
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
