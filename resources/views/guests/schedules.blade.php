@extends('layouts.web-app')
@section('content')
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>{{ \Session::get('success') }}</p>
            </div>
        @endif
                    <h2 class="card-header-web">NFL Schedules</h2>

                    <div class="d-flex">
                        <div class="d-inline-block" style="width: 100px; float:left;">
                            <div class="form-group" style="text-align: center;">
                                <label for="pwd">Season</label>
                                {!! Form::select('season_id', $seasons, $season_id, array('class' => 'form-select drop-down', 'id'=>'season_id', 'onchange'=>'getSchedules();')) !!}
                            </div>
                        </div>&nbsp;&nbsp;
                        <div class="d-inline-block"  style="width: 100px; float:left;">
                            <div class="form-group" style="text-align: center;">
                                <label for="week_no">Week</label>
                                {!! Form::select('week_no', $weeks, $week_number, array('class' => 'form-select drop-down','id'=>'pre_week_number', 'onchange'=>'getSchedules();')) !!}
                            </div>
                        </div>

                    </div><br />
                    <div class="row schedules-page">
                <div class="col-12 col-lg-12">
                    <div class="card card-table">

                        <div class="table-frame">
                            <div class="tab-content">
                                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <ul>
                                            @foreach($data_list as $key => $game)
                                                <li class="d-flex align-items-center justify-content-between">
                                                    <!--- new struct add start---->
                                                    <div class="detail-info match-txt">
                                                        <p>{{date("l, F d", strtotime($game['game_date']))}}</p>
                                                        <div class="show-on-mobile">
                                                            <div class="d-flex justify-content-center mt-3">
                                                                @if(isset($market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]))
                                                                    <span style="font-size: 12px; color:#fff;min-width:54px;" class="badge bg-secondary">{{(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['home_money_line'] < @$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['spread']):addPlusSymbol(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['spread'])}}</span><span style="font-size: 12px; color:#fff;min-width:54px;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['away_money_line'])}}</span><span style="font-size: 12px; color:#fff;min-width:54px;" class="badge bg-secondary">{{@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width:84px;" class="badge bg-secondary">ATS &nbsp;{{rtrim(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['away_team_ats'], '-0')}}</span>
                                                                @endif
                                                            </div>
                                                            <div class="d-flex justify-content-center mt-3">
                                                                @if(isset($market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]))
                                                                    <span style="font-size: 12px; color:#fff;min-width:54px;" class="badge bg-secondary">{{(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['home_money_line'] > @$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['spread']):addPlusSymbol(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['spread'])}}</span><span style="font-size: 12px; color:#fff;min-width:54px;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['home_money_line'])}}</span><span style="font-size: 12px; color:#fff;min-width:54px;" class="badge bg-secondary">{{@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width:84px;" class="badge bg-secondary">ATS &nbsp;{{rtrim(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['home_team_ats'], '-0')}}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--- new struct add end---->

                                                    <div class="d-flex align-items-center">
                                                        <div class="match-info add-style">
                                                            <div class="team-name">
                                                                <h3 class="hide-on-mobile">{{@$game['team_2']}}</h3>
                                                                <h3 class="show-on-mobile">{{getLastWord(@$game['team_2'])}}</h3>
                                                                <div class="hide-on-mobile">
                                                                    @if(isset($market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]))
                                                                        <span style="font-size: 12px; color:#fff;min-width:54px;" class="badge bg-secondary">{{(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['home_money_line'] < @$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['spread']):addPlusSymbol(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['spread'])}}</span><span style="font-size: 12px; color:#fff;min-width:54px;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['away_money_line'])}}</span><span style="font-size: 12px; color:#fff;min-width:54px;" class="badge bg-secondary">{{@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width:84px;" class="badge bg-secondary">ATS &nbsp;{{rtrim(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['away_team_ats'], '-0')}}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="team-logo add-margin">
                                                                <img alt="{{@$game['team_2']}}" src="{{ @$game['logo_2'] }}"  class="img-size"/>
                                                            </div>
                                                            <div class="team-score">{{$game['team_2_score']>0?$game['team_2_score']:''}}</div>
                                                        </div>
                                                        <div class="match-info right">
                                                            <div class="team-score">{{$game['team_1_score']>0?$game['team_1_score']:''}}</div>
                                                            <div class="team-logo">
                                                                <img alt="{{@$game['team_1']}}" src="{{ @$game['logo_1'] }}"  class="img-size"/>
                                                            </div>
                                                            <div class="team-name">
                                                                <h3 class="hide-on-mobile">{{@$game['team_1']}}</h3>
                                                                <h3 class="show-on-mobile">{{getLastWord(@$game['team_1'])}}</h3>
                                                                <div class="hide-on-mobile">
                                                                    @if(isset($market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]))
                                                                        <span style="font-size: 12px; color:#fff;min-width:54px;" class="badge bg-secondary">{{(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['home_money_line'] > @$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['spread']):addPlusSymbol(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['spread'])}}</span><span style="font-size: 12px; color:#fff;min-width:54px;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['home_money_line'])}}</span><span style="font-size: 12px; color:#fff;min-width:54px;" class="badge bg-secondary">{{@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width:84px;" class="badge bg-secondary">ATS &nbsp;{{rtrim(@$market_ats[$game['week_number']][$game['team_2_id']][$game['team_1_id']]['home_team_ats'], '-0')}}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="match-txt">
                                                        <a class="btn btn-outline-primary btn-sm" style="border-radius: 20px; margin: 0;" href="{{ url('/view-game/'.$game['game_id']) }}"><strong>View Game</strong></a>
                                                        <a class="btn btn-outline-primary btn-sm" style="border-radius: 20px; margin: 0;" href="{{ url('/adjust-rating') }}"><strong>Adjust Ratings</strong></a>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



    <script src="{!! asset('js/jquery.min.js') !!}"></script>
    <script>
        function getSchedules(){
            $("#dataTable tbody").html("");
            var weekNo = $('#pre_week_number').val();
            var seasonId = $('#season_id').val();

            if(weekNo == null || seasonId == null){
                weekNo = {{($week_number == 0?1:$week_number)}};
                seasonId = {{$season_id}};
            }
            window.location.href = "{{ url('schedules')}}/"+seasonId+"/"+weekNo;
        }
    </script>
@endsection
