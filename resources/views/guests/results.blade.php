@extends('layouts.web-app')
@section('content')
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif

            <div class="card1">
                <div class="card-header-web">
                    Predictive Model Results &nbsp;&nbsp;<span style="font-size: 18px;"><strong>{{getLastWord(@$teams[$data['teams']['team_2_id']])}}</strong> <span style="color: #6c757d; font-size: 24px;">@</span> <strong>{{getLastWord(@$teams[$data['teams']['team_1_id']])}}</strong>&nbsp; |&nbsp;Week {{$week_no}}</span>
                    @if($free_combination == 1)
                        <span class="float-right">
                            <a class="btn btn-outline-primary btn-sm btn-red" style="text-decoration: none; color: white;" href="{{ url('/') }}/#Package-list">Purchase</a>
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    @if($free_combination == 1)
                        <table class="table table-hover" id="dataTable"  >
                            <tbody>
                            <tr>
                                <td><div class="message-table">Please Buy Package to View Full Predictions.</div></td>
                            </tr>
                            </tbody>
                        </table>
                    @endif
                    <table class="table table-hover"  >
                        <thead class="thead-dark">
                        <tr>
                            <th>&nbsp;</th>
                            <th colspan="2"  style="text-align: right;">&nbsp;</th>
                            <th style="text-align: center;">Winning Team</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td style="text-align: right;">
                                @if($free_combination == 1)
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                @else
                                    <img src="{{asset('images/logos/'.@$logos[$data['teams']['team_1_id']])}}"  class="img-size">
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($free_combination == 1)
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                @else
                                    <img src="{{asset('images/logos/'.@$logos[$data['teams']['team_2_id']])}}"  class="img-size">
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($free_combination == 1)
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                @else
                                    @if(@$data['team_1_influenced_predicted_score']['score'] > @$data['team_2_influenced_predicted_score']['score'])
                                        <img src="{{asset('images/logos/'.@$logos[$data['teams']['team_1_id']])}}"  class="img-size"><br/>
                                        <strong>{{@$teams[$data['teams']['team_1_id']]}}</strong>
                                    @else
                                        <img src="{{asset('images/logos/'.@$logos[$data['teams']['team_2_id']])}}"  class="img-size"><br/>
                                        <strong>{{@$teams[$data['teams']['team_2_id']]}}</strong>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Spread	</td>
                            <td style="text-align: right;">
                                @if($free_combination == 1)
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                @else
                                    {{number_format(@$data['team_1_moneyline']['team_1_spread'], 2)}}
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($free_combination == 1)
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                @else
                                    {{number_format(@$data['team_2_moneyline']['team_2_spread'], 2)}}
                                @endif
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Money Line	</td>
                            <td style="text-align: right;">
                                @if($free_combination == 1)
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                @else
                                    {{@$data['team_1_moneyline']['team_1_moneyline']}}
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($free_combination == 1)
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                @else
                                    {{@$data['team_2_moneyline']['team_2_moneyline']}}
                                @endif
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Over/Under	</td>
                            <td style="text-align: right;">
                                @if($free_combination == 1)
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                @else
                                    {{@$data['team_1_moneyline']['team_1_over_under']}}
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($free_combination == 1)
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                @else
                                    {{@$data['team_2_moneyline']['team_2_over_under']}}
                                @endif
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Win Probability	</td>
                            <td style="text-align: right;">
                                @if($free_combination == 1)
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                @else
                                    <div class="progress">
                                        <div class="skills" style="color:black; text-align:center; width: {{(@$data['team_1_moneyline']['team_1_probability']>0 && @$data['team_1_moneyline']['team_1_probability']<8)?8:@$data['team_1_moneyline']['team_1_probability']}}%; background-color: {{(@$data['team_1_moneyline']['team_1_probability'] < 40? '#7fff00' : (@$data['team_1_moneyline']['team_1_probability'] < 60 ? '#4cbb17' : '#228b22'))}};">{{number_format(@$data['team_1_moneyline']['team_1_probability'], 2)}}%</div>
                                    </div>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($free_combination == 1)
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                @else
                                    <div class="progress">
                                        <div class="skills" style="color:black; text-align:center; width: {{(@$data['team_2_moneyline']['team_2_probability']>0 && @$data['team_2_moneyline']['team_2_probability']<8)?8:@$data['team_2_moneyline']['team_2_probability']}}%; background-color: {{(@$data['team_2_moneyline']['team_2_probability'] < 40? '#7fff00' : (@$data['team_2_moneyline']['team_2_probability'] < 60 ? '#4cbb17' : '#228b22'))}};">{{number_format(@$data['team_2_moneyline']['team_2_probability'], 2)}}%</div>
                                    </div>
                                @endif
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br/>
            <div class="card1">
                <div class="card-header-web">Team Ratings
                </div>
                <div class="card-body">
                <div class="scroll-on-mobile">
                    <table class="table table-hover" style="table-layout: fixed;">
                        <thead class="thead-dark">
                        <tr>
                            <th>Factors</th>
                            <th style="text-align: right;">{{@$teams[$data['teams']['team_1_id']]}} Ratings</th>
                            <th style="text-align: right;">{{@$teams[$data['teams']['team_2_id']]}} Ratings</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($data['team_influence_factors']['factor']))
                            @foreach ($data['team_influence_factors']['factor'] as  $factors)
                                <tr>
                                    <td>{{@$factors['factor_name']}}</td>
                                    <td style="text-align: right;">
                                        @if($free_combination == 1)
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        @else
                                            {{getGrades(@$factors['team_1_rating'])}}
                                        @endif
                                    </td>
                                    <td style="text-align: right;">
                                        @if($free_combination == 1)
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        @else
                                            {{getGrades(@$factors['team_2_rating'])}}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
            </br>

            <div class="card1">
                <div class="card-header-web">{{@$teams[$data['teams']['team_1_id']]}}
                    <img src="{{asset('images/logos/'.@$logos[$data['teams']['team_1_id']])}}" class="img-size">
                </div>
                <div class="card-body">
                    <div class="scroll-on-mobile">
                        <table class="table table-hover data-scroll-table" style="table-layout: fixed;">
                        <thead class="thead-dark">
                        <tr>
                            <th style="text-align: right;">Week</th>
                            <th>Dates</th>
                            <th style="text-align: right;">Points Scored</th>
                            <th style="text-align: right;">Points Allowed</th>
                            <th style="text-align: right;">Ranking</th>
                            <th style="text-align: right;">Offense Ranking</th>
                            <th class="add-width" style="text-align: right;">Defense Ranking</th>
                            <th>Opponent</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($data['team_history']))
                            @foreach (@$data['team_history']['team_1'] as $key => $teamData)
                                @foreach($teamData as $key => $team)
                                    <tr>
                                        <td style="text-align: right;">{{ @$team['week_number'] }}</td>
                                        <td>{{ date('m-d-Y', strtotime(@$team['game_date'])) }}</td>
                                        <td style="text-align: right;">{{ @$team['points_scored'] }}</td>
                                        <td style="text-align: right;">{{ @$team['points_allowed'] }}</td>
                                        <td style="text-align: right;">{{ @$team['ranking'] }}</td>
                                        <td style="text-align: right;">{{ @$team['offensive_ranking'] }}</td>
                                        <td class="add-width" style="text-align: right;">{{ @$team['defensive_ranking'] }}</td>
                                        <td>{{ @$teams[$team['opponent']] }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            </br>
            <div class="card1">
                <div class="card-header-web">{{@$teams[$data['teams']['team_2_id']]}}
                    <img src="{{asset('images/logos/'.@$logos[$data['teams']['team_2_id']])}}"  class="img-size">
                    @can('role-create')
                        <span class="float-right">
                    </span>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="scroll-on-mobile">
                        <table class="table table-hover data-scroll-table" style="table-layout: fixed;">
                        <thead class="thead-dark">
                        <tr>
                            <th style="text-align: right;">Week</th>
                            <th>Dates</th>
                            <th style="text-align: right;">Points Scored</th>
                            <th style="text-align: right;">Points Allowed</th>
                            <th style="text-align: right;">Ranking</th>
                            <th style="text-align: right;">Offense Ranking</th>
                            <th class="add-width" style="text-align: right;">Defense Ranking</th>
                            <th>Opponent</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($data['team_history']))
                            @foreach ($data['team_history']['team_2'] as $key => $teamData)
                                @foreach($teamData as $key => $team)
                                    <tr>
                                        <td style="text-align: right;">{{ @$team['week_number'] }}</td>
                                        <td>{{ date('m-d-Y', strtotime(@$team['game_date'])) }}</td>
                                        <td style="text-align: right;">{{ @$team['points_scored'] }}</td>
                                        <td style="text-align: right;">{{ @$team['points_allowed'] }}</td>
                                        <td style="text-align: right;">{{ @$team['ranking'] }}</td>
                                        <td style="text-align: right;">{{ @$team['offensive_ranking'] }}</td>
                                        <td class="add-width" style="text-align: right;">{{ @$team['defensive_ranking'] }}</td>
                                        <td>{{ @$teams[$team['opponent']] }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
@endsection
