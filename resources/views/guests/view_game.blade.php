@extends('layouts.web-app')
@section('content')
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
                <div class="card-header-web">Matchup Breakdown
                    <div  class="float-right">
                    <span style="font-size: 12px; display: block;">
                        &nbsp;&nbsp;<i class="fa fa-lock" aria-hidden="true"></i> Locked Items
                    </span>
                    </div>
                </div>

                    <div class="card-body viewGamePage">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="d-flex mb-4">
                                    <div class="d-inline-block hide-on-mobile" style="width: 33%; float:left;">
                                        <div class="form-group">
                                            <div class="team-info">
                                                <div class="team-logo">
                                                    <img src="{{asset('images/logos/'.$logos[$data->team_2_id])}}">
                                                </div>
                                                <div class="team-name">
                                                    <h3>{{@$teams[$data->team_2_id]}}</h3>
                                                    <span style="font-size: 16px; color: white; font-weight: lighter;">
                                                    @if($data->team_1_score>0 || $data->team_2_score>0)
                                                        {{$data->team_2_score}}
                                                    @else
                                                    {{@$records_t2->wins}} - {{@$records_t2->losses}} {{@$records_t2->ties>0?' - '.@$records_t2->ties:''}}
                                                    @endif
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-inline-block" style="width: 34%; float:right;">
                                        <div class="form-group" style="text-align: center;height: 100%;display: flex;justify-content: center;align-items: center;">
                                            <div class="team-info">
                                                <div class="team-name" style="width: 100%;">
                                                    <h3>{{date("l, F d", strtotime($data->game_date))}}</h3>
                                                    <span style="font-size: 16px; color: white; font-weight: lighter;">{{$data->game_time}}pm EST</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="show-on-mobile pt-3">
                                        <div class="d-flex justify-content-center">

                                            <div class="team-info">
                                                <div class="team-logo">
                                                    <img src="{{asset('images/logos/'.$logos[$data->team_2_id])}}">
                                                </div>
                                                <div class="team-name">
                                                    <h3>{{@$teams[$data->team_2_id]}}</h3>
                                                    <span style="font-size: 16px; color: white; font-weight: lighter;">
                                                    @if($data->team_1_score>0 || $data->team_2_score>0)
                                                        {{$data->team_2_score}}
                                                    @else
                                                    {{@$records_t2->wins}} - {{@$records_t2->losses}} {{@$records_t2->ties>0?' - '.@$records_t2->ties:''}}
                                                    @endif
                                                </span>
                                                </div>
                                            </div>
                                            <div class="team-info right">
                                            <div class="team-logo">
                                                    <img src="{{asset('images/logos/'.$logos[$data->team_1_id])}}">
                                                </div>
                                                <div class="team-name">
                                                    <h3>{{@$teams[$data->team_1_id]}}</h3>
                                                    <span style="font-size: 16px; color: white; font-weight: lighter;">
                                                    @if($data->team_1_score>0 || $data->team_2_score>0)
                                                        {{$data->team_1_score}}
                                                    @else
                                                        {{@$records_t1->wins}} - {{@$records_t1->losses}} {{@$records_t1->ties>0?' - '.@$records_t1->ties:''}}
                                                    @endif
                                                </span>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="d-inline-block hide-on-mobile"  style="width: 33%; float:right;">
                                        <div class="form-group" style="float:right;">
                                            <div class="team-info right">
                                                <div class="team-name">
                                                    <h3>{{@$teams[$data->team_1_id]}}</h3>
                                                    <span style="font-size: 16px; color: white; font-weight: lighter;">
                                                    @if($data->team_1_score>0 || $data->team_2_score>0)
                                                        {{$data->team_1_score}}
                                                    @else
                                                        {{@$records_t1->wins}} - {{@$records_t1->losses}} {{@$records_t1->ties>0?' - '.@$records_t1->ties:''}}
                                                    @endif
                                                </span>
                                                </div>
                                                <div class="team-logo">
                                                    <img src="{{asset('images/logos/'.$logos[$data->team_1_id])}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12 col-md-6">
                                <table class="table table-hover" style="table-layout: fixed;">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Betting Odds</th>
                                        <th style="text-align: right;">Chalk Sports</th>
                                        <th style="text-align: right;">Market</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr><td>Spread</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{addPlusSymbol($data->t2_spread)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">{{(@$market_values[0]->spread > 0)?addPlusSymbol(@$market_values[0]->spread):@$market_values[0]->spread}}</td></tr>
                                    <tr><td>Over/Under</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{$data->t2_over_under}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">{{@$market_values[0]->overunder}}</td></tr>
                                    <tr><td>Money Line</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{addPlusSymbol($data->t2_money_line)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">{{addPlusSymbol(@$market_values[0]->away_team_money_line)}}</td></tr>
                                    <tr><td>Win Probability</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                <div class="progress">
                                                    <div class="skills" style="color:black; text-align:center; width: {{($data->t2_probability>0 && $data->t2_probability<8)?8:$data->t2_probability}}%; background-color: {{($data->t2_probability < 40? '#7fff00' : ($data->t2_probability < 60 ? '#4cbb17' : '#228b22'))}};">{{$data->t2_probability}}%</div>
                                                </div>
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="progress">
                                                <div class="skills" style="color:black; text-align:center; width: {{(@$matchup_predictor->away_team_probability>0 && @$matchup_predictor->away_team_probability<8)?8:@$matchup_predictor->away_team_probability}}%; background-color: {{(@$matchup_predictor->away_team_probability < 40? '#7fff00' : (@$matchup_predictor->away_team_probability < 60 ? '#4cbb17' : '#228b22'))}};">{{number_format(@$matchup_predictor->away_team_probability)}}%</div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                                <div class="col-12 col-md-6">
                                <table class="table table-hover" style="table-layout: fixed;">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Betting Odds</th>
                                        <th style="text-align: right;">Chalk Sports</th>
                                        <th style="text-align: right;">Market</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr><td>Spread</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{addPlusSymbol($data->t1_spread)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">{{(@$market_values[0]->spread>0)?addPlusSymbol(@$market_values[0]->spread):@$market_values[0]->spread}}</td></tr>
                                    <tr><td>Over/Under</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{$data->t1_over_under}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">{{@$market_values[0]->overunder}}</td></tr>
                                    <tr><td>Money Line</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{addPlusSymbol($data->t1_money_line)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">{{addPlusSymbol(@$market_values[0]->home_team_money_line)}}</td></tr>
                                    <tr><td>Win Probability</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                <div class="progress">
                                                    <div class="skills" style="color:black; text-align:center; width: {{($data->t1_probability>0 && $data->t1_probability<8)?8:$data->t1_probability}}%; background-color: {{($data->t1_probability < 40? '#7fff00' : ($data->t1_probability < 60 ? '#4cbb17' : '#228b22'))}};">{{$data->t1_probability}}%</div>
                                                </div>
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="progress">
                                                <div class="skills" style="color:black; text-align:center; width: {{(@$matchup_predictor->home_team_probability>0 && @$matchup_predictor->home_team_probability<8)?8:@$matchup_predictor->home_team_probability}}%; background-color: {{(@$matchup_predictor->home_team_probability < 40? '#7fff00' : (@$matchup_predictor->home_team_probability < 60 ? '#4cbb17' : '#228b22'))}};">{{number_format(@$matchup_predictor->home_team_probability)}}%</div>
                                            </div>
                                        </td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12 col-md-6">
                                <table class="table table-hover view-game-table" id="dataTable">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Position Matchups</th>
                                        <th style="text-align: right;">Grades</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($curl_data['team_influence_factors']['factor']))
                                        @foreach ($curl_data['team_influence_factors']['factor'] as  $factors)
                                            @if(@$factors['factor_id'] < 8)
                                                <tr>
                                                    <td>{{@$factors['factor_name']}}</td>
                                                    <td style="text-align: left;">
                                                        <div class="gradeCol-width">
                                                        @if($show)
                                                                {{getGrades(@$factors['team_2_rating'])}}
                                                            @else
                                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 col-md-6">
                                <table class="table table-hover view-game-table" id="dataTable">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Position Matchups</th>
                                        <th style="text-align: right;">Grades</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($curl_data['team_influence_factors']['factor']))
                                        @foreach ($curl_data['team_influence_factors']['factor'] as  $factors)
                                            @if(@$factors['factor_id'] < 8)
                                                <tr>
                                                    <td>{{@$factors['factor_name']}}</td>
                                                    <td style="text-align: left;">
                                                        <div class="gradeCol-width">
                                                            @if($show)
                                                                {{getGrades(@$factors['team_1_rating'])}}
                                                            @else
                                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="row mb-4">
                            <div class="col-12 col-md-6">
                                <table class="table table-hover view-game-table" id="dataTable">
                                    <thead class="thead-dark">
                                    <tr><th>Team Rankings</th><th style="text-align: right;">Statistics/Ranks</th></tr>
                                    </thead>
                                    <tbody>
                                    <tr><td>Overall Rank</td><td style="text-align: right;">{{number_format(@$team2_ranks['over_all']/2)}}</td></tr>
                                    <tr><td>Offense Rank</td><td style="text-align: right;">{{@$team2_ranks['offense_rank']}}</td></tr>
                                    <tr><td>Defense Rank</td><td style="text-align: right;">{{@$team2_ranks['defense_rank']}}</td></tr>
                                    <tr><td>Total Points Per Game</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$offence_team2[0]->total_points_per_game, 1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr><td>Passing Yards Per Game</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$offence_team2[0]->passing_yards_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Rushing Yards Per Game</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$offence_team2[0]->rushing_yards_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Yards Per Game</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$offence_team2[0]->yards_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Total Points Per Game Allowed</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$defence_team2[0]->total_points_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Passing Yards Per Game Allowed</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$defence_team2[0]->passing_yards_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Rushing Yards Per Game Allowed</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$defence_team2[0]->rushing_yards_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Yards Per Game Allowed</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$defence_team2[0]->yards_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Turnover Differential</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$defence_team2[0]->diff,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 col-md-6">
                                <table class="table table-hover view-game-table" id="dataTable">
                                    <thead class="thead-dark">
                                    <tr><th>Team Rankings</th><th style="text-align: right;">Statistics/Ranks</th></tr>
                                    </thead>
                                    <tbody>
                                    <tr><td>Overall Rank</td><td style="text-align: right;">{{number_format(@$team1_ranks['over_all']/2)}}</td></tr>
                                    <tr><td>Offense Rank</td><td style="text-align: right;">{{@$team1_ranks['offense_rank']}}</td></tr>
                                    <tr><td>Defense Rank</td><td style="text-align: right;">{{@$team1_ranks['defense_rank']}}</td></tr>
                                    <tr><td>Total Points Per Game</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$offence_team1[0]->total_points_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr><td>Passing Yards Per Game</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$offence_team1[0]->passing_yards_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Rushing Yards Per Game</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$offence_team1[0]->rushing_yards_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Yards Per Game</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$offence_team1[0]->yards_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Total Points Per Game Allowed</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$defence_team1[0]->total_points_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Passing Yards Per Game Allowed</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$defence_team1[0]->passing_yards_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Rushing Yards Per Game Allowed</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$defence_team1[0]->rushing_yards_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Yards Per Game Allowed</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$defence_team1[0]->yards_per_game,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    <tr><td>Turnover Differential</td>
                                        <td style="text-align: right;">
                                            @if($show)
                                                {{number_format(@$defence_team1[0]->diff,1)}}
                                            @else
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            @endif
                                        </td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12 col-md-6">
                                <table class="table table-hover double-header view-game-table" id="dataTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>{{getLastWord(@$teams[$data->team_2_id])}} Injury Report</th>
                                            <th class="col-width" style="text-align: right;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $count2=0; @endphp
                                    @foreach($injuries as $injury)
                                        @if($data->team_2_id == $injury->team_id && $count2 < 5)
                                            @php $count2++; @endphp
                                            <tr>
                                                <td>{{$injury->player_name}}</td>
                                                <td class="col-width" style="text-align: left;"><div class="{{ in_array($injury->status, ['Out', 'Injured Reserve'])?'red-box':'yellow-box' }}"></div>{{$injury->status}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 col-md-6">
                                <table class="table table-hover double-header view-game-table" id="dataTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>{{getLastWord(@$teams[$data->team_1_id])}} Injury Report</th>
                                            <th class="col-width" style="text-align: right;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $count1=0; @endphp
                                    @foreach($injuries as $injury)
                                        @if($data->team_1_id == $injury->team_id && $count1 < 5)
                                            @php $count1++; @endphp
                                            <tr>
                                                <td>{{$injury->player_name}}</td>
                                                <td class="col-width" style="text-align: left;"><div class="{{ in_array($injury->status, ['Out', 'Injured Reserve'])?'red-box':'yellow-box' }}"></div>{{$injury->status}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-4">
                                <div class="scroll-on-mobile">
                                    <table class="table table-hover">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th style="text-align: right;">Week</th>
                                            <th style="text-align: right;">Points Scored</th>
                                            <th style="text-align: right;">Points Allowed</th>
                                            <th class="add-width" style="text-align: right;">Ranking</th>
                                            <th>Opponent</th>
                                            <th>Result</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($curl_data['team_history']))
                                            @foreach (@$curl_data['team_history']['team_2'] as $key => $teamData)
                                                @foreach($teamData as $key => $team)
                                                    <tr>
                                                        <td style="text-align: right;">{{ @$team['week_number'] }}</td>
                                                        <td style="text-align: right;">{{ @$team['points_scored'] }}</td>
                                                        <td style="text-align: right;">{{ @$team['points_allowed'] }}</td>
                                                        <td class="add-width" style="text-align: right;">{{ @$team['ranking'] }}</td>
                                                        <td>{{ getLastWord(@$teams[$team['opponent']]) }}</td>
                                                        @foreach($team_2_result as $key2 => $object)
                                                            @if($key == $key2)
                                                                <td>{{$object->Result}}</td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mb-4">
                                <div class="scroll-on-mobile">
                                    <table class="table table-hover">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th style="text-align: right;">Week</th>
                                            <th style="text-align: right;">Points Scored</th>
                                            <th style="text-align: right;">Points Allowed</th>
                                            <th class="add-width" style="text-align: right;">Ranking</th>
                                            <th>Opponent</th>
                                            <th>Result</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($curl_data['team_history']))
                                            @foreach (@$curl_data['team_history']['team_1'] as $key => $teamData)
                                                @foreach($teamData as $key => $team)
                                                    <tr>
                                                        <td style="text-align: right;">{{ @$team['week_number'] }}</td>
                                                        <td style="text-align: right;">{{ @$team['points_scored'] }}</td>
                                                        <td style="text-align: right;">{{ @$team['points_allowed'] }}</td>
                                                        <td class="add-width" style="text-align: right;">{{ @$team['ranking'] }}</td>
                                                        <td>{{ getLastWord(@$teams[$team['opponent']]) }}</td>
                                                        @foreach($team_1_result as $key2 => $object)
                                                            @if($key == $key2)
                                                                <td>{{$object->Result}}</td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12 col-md-6">
                                <button class="btn btn-outline-danger btn-red"><a style="text-decoration: none; color: white;" href="/#Package-list">Buy Package to View Game</a></button>
                            </div>
                        </div>
                    </div>
@endsection
