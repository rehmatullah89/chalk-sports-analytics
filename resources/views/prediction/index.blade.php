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
                <div class="card-header">
                    Predictive Model Results&nbsp;&nbsp;&nbsp;<span style="font-size: 16px;">
                    <strong>{{getLastWord(@$teams[$data['teams']['team_2_id']])}}</strong> <span style="color: #6c757d; font-size: 24px;">@</span> <strong>{{getLastWord(@$teams[$data['teams']['team_1_id']])}}</strong>&nbsp; |&nbsp;Week {{$week_no}}</span>

                    @can('role-create')
                        @if(auth()->user()->hasRole('admin'))
                            <span class="float-right">
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('dashboard') }}"><strong>Dashboard</strong></a>
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('prediction.create') }}"><strong>New Prediction</strong></a>
                            </span>
                        @endif
                    @endcan
                    @if($free_combination == 1)
                        <span class="float-right">
                            <a class="btn btn-outline-primary btn-sm btn-red" style="text-decoration: none; color: white;" href="{{ route('home') }}/#Package-list">Purchase</a>
                        </span>
                    @endif
                </div>
                <div class="card-body">
                     @if($free_combination == 1)
                        <table class="table table-hover" id="dataTable">
                            <tbody>
                            <tr>
                                <td><div class="message-table">Please Buy Package to View Full Predictions.</div></td>
                            </tr>
                            </tbody>
                        </table>
                    @endif
                    <table class="table table-hover" >
                        <thead class="thead-dark">
                        <tr>
                            <th>&nbsp;</th>
                            <th colspan="2" >&nbsp;</th>
                            <th style="text-align: center;">Winning Team</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td style="text-align: right;">
                                    <img src="{{asset('images/logos/'.@$logos[$data['teams']['team_1_id']])}}"  class="img-size">
                            </td>
                            <td style="text-align: right;">
                                    <img src="{{asset('images/logos/'.@$logos[$data['teams']['team_2_id']])}}"  class="img-size">
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
                        @hasrole('admin')
                        <tr>
                            <td>Predicted Score Based On History	</td>
                            <td style="text-align: right;">{{@$data['predicted_score_based_on_history']['team_1']['score']}}</td>
                            <td style="text-align: right;">{{@$data['predicted_score_based_on_history']['team_2']['score']}}</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Range	</td>
                            <td style="text-align: right;">{{@$data['predicted_score_based_on_history']['team_1']['low_range']}} to {{@$data['predicted_score_based_on_history']['team_1']['high_range']}}</td>
                            <td style="text-align: right;">{{@$data['predicted_score_based_on_history']['team_2']['low_range']}} to {{@$data['predicted_score_based_on_history']['team_2']['high_range']}}</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Final Predicted Scores	</td>
                            <td style="text-align: right;">{{@$data['team_1_influenced_predicted_score']['score']}}</td>
                            <td style="text-align: right;">{{@$data['team_2_influenced_predicted_score']['score']}}</td>
                            <td>&nbsp;</td>
                        </tr>
                        @endhasrole
                        </tbody>
                    </table>
                </div>
            </div>
<br/>
            <div class="card">
                <div class="card-header">Team Ratings
                </div>
                <div class="card-body">
                    <div class="scroll-on-mobile">
                        <table class="table table-hover data-scroll-table"  style="table-layout: fixed;">
                        <thead class="thead-dark">
                        <tr>
                            <th>Factors</th>
                            @hasrole('admin')
                            <th style="text-align: right;">Weight</th>
                            @endhasrole
                            <th>{{@$teams[$data['teams']['team_1_id']]}} Rating</th>
                            <th>{{@$teams[$data['teams']['team_2_id']]}} Rating</th>
                            @hasrole('admin')
                            <th style="text-align: right;">Difference</th>
                            <th style="text-align: right;">Percent Change</th>
                            <th style="text-align: right;">Score Change</th>
                            @endhasrole
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($data['team_influence_factors']['factor']))
                            @foreach ($data['team_influence_factors']['factor'] as  $factors)
                                <tr>
                                    <td>{{@$factors['factor_name']}}</td>
                                    @hasrole('admin')
                                    <td style="text-align: right;">{{@$factors['factor_weight']}}</td>
                                    @endhasrole
                                    <td>
                                        @if($free_combination == 1)
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        @else
                                            {{getGrades(@$factors['team_1_rating'])}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($free_combination == 1)
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        @else
                                            {{getGrades(@$factors['team_2_rating'])}}
                                        @endif
                                    </td>
                                    @hasrole('admin')
                                    <td style="text-align: right;">{{@$factors['diffference']}}</td>
                                    <td style="text-align: right;">{{@$factors['percent_change']}}</td>
                                    <td style="text-align: right;">{{number_format(@$factors['score_change'])}}</td>
                                    @endhasrole
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            </br>

            <div class="card">
                <div class="card-header">{{@$teams[$data['teams']['team_1_id']]}}
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
            <div class="card">
                <div class="card-header">{{@$teams[$data['teams']['team_2_id']]}}
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
            </div></br>

    </div>
</div>
@endsection
