<?php
$is1FreeGame = 0;
$is2FreeGame = 0;
if(!is_null($free_games) && count($free_games) == 1){
    $game1= $free_games;
    $game2= $games;
    $is1FreeGame = 1;
}else if(!is_null($free_games) && count($free_games) > 1){
    $game1= $free_games;
    $game2= $free_games;
    $is1FreeGame = 1;
    $is2FreeGame = 1;
}else{
    $game1= $games;
    $game2= $games;
}
?>
<link rel="stylesheet" href="{{ asset('js/scrollbar') }}/jquery.mCustomScrollbar.min.css">
<link rel="stylesheet" href="{{ asset('css') }}/all.css">
<style>
    .py-4{
        padding-top: 0rem !important;
    }
    @media screen and (min-width: 768px){
        .navbar-expand-md .navbar-nav .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
        }
    }
    @media screen and (max-width: 768px){
        .menu {
            display: block !important;
            position: fixed;
        }
    }
</style>
<div class="banner">
    <img alt="banner image" src="{{ asset('images/banner-bg.png') }}" />
</div>
<div id="main">
    <div class="container-fluid">
        <div class="row margin-top">
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif
            @if(isset($game1[0]->team_2_id) && @$game1[0]->team_2_id>0)
                <div class="col-6">
                <div class="card">
                    @if($is1FreeGame)
                    <div class="ribbon ribbon-top-right"><span>Free Game</span></div>
                    @endif
                        <div class="d-flex align-items-center card-box">
                        <div class="team-info hide-on-mobile">
                            <div class="team-logo">
                                <img src="{{ asset('images/logos/'.@$logos[$game1[0]->team_2_id]) }}" />
                            </div>
                            <div class="team-name">
                                <h3>{{@$teams[$game1[0]->team_2_id]}}</h3>
                                @if(@$game1[0]->team_1_score || @$game1[0]->team_2_score)
                                    <span style="font-size: 20px; color: white; font-weight: lighter;">{{@$game1[0]->team_2_score}}</span>
                                @else
                                    @foreach($team_records as $record)
                                        @if($record->team_id == @$game1[0]->team_2_id)
                                            <span style="font-size: 14px; color: white; font-weight: lighter;">{{@$record->wins}} - {{@$record->losses}} {{@$record->ties > 0?' - '.@$record->ties:''}}</span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="detail-info">
                            <h2>{{date("l, F d", strtotime(@$game1[0]->game_date))}}</h2>
                            <!-- <p>NEW YORKERS STADIUM</p> -->
                            {{--<div class="d-flex justify-content-center mt-3">
                                <span class="badge bg-secondary">Opening - {{date("h:i", strtotime(@$game1[0]->game_time))}}</span>
                                <span class="badge bg-secondary">Currrent - {{date("h:i")}} </span>
                            </div>--}}
                            @if(isset($market_ats[@$game1[0]->week_number][@$game1[0]->team_2_id][@$game1[0]->team_1_id]))
                                <div class="d-flex justify-content-center mt-3">
                                    <span class="badge bg-secondary"><em>Spread</em> <em>{{(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['home_money_line'] < @$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['spread'])}}</em></span>
                                    <span class="badge bg-secondary"><em>Spread</em> <em>{{(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['home_money_line'] > @$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['spread'])}}</em></span>
                                </div>

                                <div class="d-flex justify-content-center mt-3">
                                    <span class="badge bg-secondary"><em>Money Line</em> <em>{{addPlusSymbol(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['away_money_line'])}}</em></span>
                                    <span class="badge bg-secondary"><em>Money Line</em> <em>{{addPlusSymbol(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['home_money_line'])}}</em></span>
                                </div>
                                <div class="d-flex justify-content-center mt-3">
                                    <span class="badge bg-secondary"><em>Over/Under</em> <em>{{@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['over_under']}}</em></span>
                                    <span class="badge bg-secondary"><em>Over/Under</em> <em>{{@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['over_under']}}</em></span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-center mt-3 hide-on-mobile">
                                <a class="btn btn-warning btn-sm" href="/game-report/{{@$game1[0]->game_id}}"><strong>View Game</strong></a>
                                <a class="btn btn-success btn-sm" onclick="setPreCookie({{@$game1[0]->team_1_id}},{{@$game1[0]->team_2_id}})" href="/prediction/create"><strong>Adjust Ratings</strong></a>
                            </div>
                        </div>
                        <div class="team-info hide-on-mobile">
                            <div class="team-logo">
                                <img src="{{ asset('images/logos/'.@$logos[$game1[0]->team_1_id]) }}" />
                            </div>
                            <div class="team-name">
                                <h3>{{@$teams[$game1[0]->team_1_id]}}</h3>
                                @if(@$game1[0]->team_1_score || @$game1[0]->team_2_score)
                                    <span style="font-size: 20px; color: white; font-weight: lighter;">{{@$game1[0]->team_1_score}}</span>
                                @else
                                    @foreach($team_records as $record)
                                        @if($record->team_id == @$game1[0]->team_1_id)
                                            <span style="font-size: 14px; color: white; font-weight: lighter;">{{@$record->wins}} - {{@$record->losses}} {{@$record->ties > 0?' - '.@$record->ties:''}}</span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                            <div class="show-on-mobile">
                                <div class="d-flex justify-content-center">
                                    <div class="team-info">
                                        <div class="team-logo">
                                            <img src="{{ asset('images/logos/'.@$logos[$game1[0]->team_2_id]) }}" />
                                        </div>
                                        <div class="team-name">
                                            <h3>{{getLastWord(@$teams[$game1[0]->team_2_id])}}</h3>
                                            @if(@$game1[0]->team_1_score || @$game1[0]->team_2_score)
                                                <span style="font-size: 20px; color: white; font-weight: lighter;">{{@$game1[0]->team_2_score}}</span>
                                            @else
                                                @foreach($team_records as $record)
                                                    @if($record->team_id == @$game1[0]->team_2_id)
                                                        <span style="font-size: 14px; color: white; font-weight: lighter;">{{@$record->wins}} - {{@$record->losses}} {{@$record->ties > 0?' - '.@$record->ties:''}}</span>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="team-info">
                                        <div class="team-logo">
                                            <img src="{{ asset('images/logos/'.@$logos[$game1[0]->team_1_id]) }}" />
                                        </div>
                                        <div class="team-name">
                                            <h3>{{getLastWord(@$teams[$game1[0]->team_1_id])}}</h3>
                                            @if(@$game1[0]->team_1_score || @$game1[0]->team_2_score)
                                                <span style="font-size: 20px; color: white; font-weight: lighter;">{{@$game1[0]->team_1_score}}</span>
                                            @else
                                                @foreach($team_records as $record)
                                                    @if($record->team_id == @$game1[0]->team_1_id)
                                                        <span style="font-size: 14px; color: white; font-weight: lighter;">{{@$record->wins}} - {{@$record->losses}} {{@$record->ties > 0?' - '.@$record->ties:''}}</span>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <a class="btn btn-warning btn-sm" href="/game-report/{{@$game1[0]->game_id}}"><strong>View Game</strong></a>
                                    <a class="btn btn-success btn-sm" onclick="setPreCookie({{@$game1[0]->team_1_id}},{{@$game1[0]->team_2_id}})" href="/prediction/create"><strong>Adjust Ratings</strong></a>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            @endif
            @if(isset($game2[1]->team_2_id) && @$game2[1]->team_2_id>0)
                <div class="col-6">
                <div class="card">
                    @if($is2FreeGame)
                    <div class="ribbon ribbon-top-right"><span>Free Game</span></div>
                    @endif
                        <div class="d-flex align-items-center card-box">
                        <div class="team-info hide-on-mobile">
                            <div class="team-logo">
                                <img src="{{ asset('images/logos/'.@$logos[$game2[1]->team_2_id]) }}" />
                            </div>
                            <div class="team-name">
                                <h3>{{@$teams[$game2[1]->team_2_id]}}</h3>
                                @if(@$game2[1]->team_1_score || @$game2[1]->team_2_score)
                                    <span style="font-size: 20px; color: white; font-weight: lighter;">{{@$game2[1]->team_2_score}}</span>
                                @else
                                    @foreach($team_records as $record)
                                        @if($record->team_id == @$game2[1]->team_2_id)
                                            <span style="font-size: 14px; color: white; font-weight: lighter;">{{@$record->wins}} - {{@$record->losses}} {{@$record->ties > 0?' - '.@$record->ties:''}}</span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="detail-info">
                            <h2>{{date("l, F d", strtotime(@$game2[1]->game_date))}}</h2>
                            <!-- <p>NEW YORKERS STADIUM</p> -->
                            {{-- <div class="d-flex justify-content-center mt-3">
                                <span class="badge bg-secondary"><em>Opening - {{date("h:i", strtotime(@$game2[1]->game_time))}}</span>
                                <span class="badge bg-secondary"><em>Currrent - {{date("h:i")}} </span>
                            </div> --}}
                            @if(isset($market_ats[@$game2[1]->week_number][@$game2[1]->team_2_id][@$game2[1]->team_1_id]))
                                <div class="d-flex justify-content-center mt-3">
                                    <span class="badge bg-secondary"><em>Spread</em> <em>{{(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['home_money_line'] < @$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['spread'])}}</em></span>
                                    <span class="badge bg-secondary"><em>Spread</em> <em>{{(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['home_money_line'] > @$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['spread'])}}</em></span>
                                </div>

                                <div class="d-flex justify-content-center mt-3">
                                    <span class="badge bg-secondary"><em>Money Line</em> <em>{{addPlusSymbol(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['away_money_line'])}}</em></span>
                                    <span class="badge bg-secondary"><em>Money Line</em> <em>{{addPlusSymbol(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['home_money_line'])}}</em></span>
                                </div>
                                <div class="d-flex justify-content-center mt-3">
                                    <span class="badge bg-secondary"><em>Over/Under</em> <em>{{@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['over_under']}}</em></span>
                                    <span class="badge bg-secondary"><em>Over/Under</em> <em>{{@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['over_under']}}</em></span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-center mt-3 hide-on-mobile">
                                <a class="btn btn-warning btn-sm" href="/game-report/{{@$game2[1]->game_id}}"><strong>View Game</strong></a>
                                <a class="btn btn-success btn-sm" onclick="setPreCookie({{@$game2[1]->team_1_id}},{{@$game2[1]->team_2_id}})" href="/prediction/create"><strong>Adjust Ratings</strong></a>
                            </div>
                        </div>
                        <div class="team-info hide-on-mobile">
                            <div class="team-logo">
                                <img src="{{ asset('images/logos/'.@$logos[$game2[1]->team_1_id]) }}" />
                            </div>
                            <div class="team-name">
                                <h3>{{@$teams[$game2[1]->team_1_id]}}</h3>
                                @if(@$game2[1]->team_1_score || @$game2[1]->team_2_score)
                                    <span style="font-size: 20px; color: white; font-weight: lighter;">{{@$game2[1]->team_1_score}}</span>
                                @else
                                    @foreach($team_records as $record)
                                        @if($record->team_id == @$game2[1]->team_1_id)
                                            <span style="font-size: 14px; color: white; font-weight: lighter;">{{@$record->wins}} - {{@$record->losses}} {{@$record->ties > 0?' - '.@$record->ties:''}}</span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                            <div class="show-on-mobile">
                                <div class="d-flex justify-content-center">
                                    <div class="team-info">
                                        <div class="team-logo">
                                            <img src="{{ asset('images/logos/'.@$logos[$game2[1]->team_2_id]) }}" />
                                        </div>
                                        <div class="team-name">
                                            <h3>{{getLastWord(@$teams[$game2[1]->team_2_id])}}</h3>
                                            @if(@$game2[1]->team_1_score || @$game2[1]->team_2_score)
                                                <span style="font-size: 20px; color: white; font-weight: lighter;">{{@$game2[1]->team_2_score}}</span>
                                            @else
                                                @foreach($team_records as $record)
                                                    @if($record->team_id == @$game2[1]->team_2_id)
                                                        <span style="font-size: 14px; color: white; font-weight: lighter;">{{@$record->wins}} - {{@$record->losses}} {{@$record->ties > 0?' - '.@$record->ties:''}}</span>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="team-info">
                                        <div class="team-logo">
                                            <img src="{{ asset('images/logos/'.@$logos[$game2[1]->team_1_id]) }}" />
                                        </div>
                                        <div class="team-name">
                                            <h3>{{getLastWord(@$teams[$game2[1]->team_1_id])}}</h3>
                                            @if(@$game2[1]->team_1_score || @$game2[1]->team_2_score)
                                                <span style="font-size: 20px; color: white; font-weight: lighter;">{{@$game2[1]->team_1_score}}</span>
                                            @else
                                                @foreach($team_records as $record)
                                                    @if($record->team_id == @$game2[1]->team_1_id)
                                                        <span style="font-size: 14px; color: white; font-weight: lighter;">{{@$record->wins}} - {{@$record->losses}} {{@$record->ties > 0?' - '.@$record->ties:''}}</span>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <a class="btn btn-warning btn-sm" href="/game-report/{{@$game2[1]->game_id}}"><strong>View Game</strong></a>
                                    <a class="btn btn-success btn-sm" onclick="setPreCookie({{@$game2[1]->team_1_id}},{{@$game2[1]->team_2_id}})" href="/prediction/create"><strong>Adjust Ratings</strong></a>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-12 col-lg-12">
                <h2 class="mb-4">NFL Schedule</h2>
                <div class="card card-table" id="schedules">
                    <div class="nav tab-link">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Week {{$week_number}}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">My Games</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="oldgames-tab" data-bs-toggle="tab" data-bs-target="#oldgames" type="button" role="tab" aria-controls="oldgames" aria-selected="false">Past Games</button>
                            </li>
                        </ul>
                    </div>
                    <div class="table-frame mCustomScrollbar" data-mcs-theme="dark">
                        <div class="tab-content">
                            @if(count($games)>0)
                                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <ul>
                                    @foreach($games as $key => $game)
                                        <li class="d-flex align-items-center justify-content-between">
                                            <!--- new struct add start---->
                                            <div class="detail-info match-txt">
                                                <p>{{date("l, F d", strtotime($game->game_date))}}</p>
                                                <div class="show-on-mobile">
                                                    <div class="d-flex justify-content-center mt-3">
                                                        @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">
                                                                @if(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'] == 0)
                                                                    {{'Even'}}
                                                                @else
                                                                {{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] < @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}
                                                                @endif
                                                            </span>
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_team_ats'], '-0')}}</span>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex justify-content-center mt-3">
                                                        @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">
                                                                @if(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'] == 0)
                                                                    {{'Even'}}
                                                                @else
                                                                {{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] > @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}
                                                                @endif
                                                            </span>
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_team_ats'], '-0')}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <!--- new struct add end---->

                                            <div class="d-flex align-items-center">
                                                <div class="match-info add-style">
                                                    <div class="team-name">
                                                        <h3 class="hide-on-mobile">{{@$teams[$game->team_2_id]}}</h3>
                                                        <h3 class="show-on-mobile">{{getLastWord(@$teams[$game->team_2_id])}}</h3>
                                                        <div class="hide-on-mobile">
                                                        @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">
                                                                @if(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'] == 0)
                                                                    {{'Even'}}
                                                                 @else
                                                                {{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] < @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}
                                                                 @endif
                                                            </span>
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_team_ats'], '-0')}}</span>
                                                        @endif
                                                        </div>
                                                    </div>
                                                    <div class="team-logo add-margin">
                                                        <img alt="{{@$teams[$game->team_2_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_2_id]) }}" class="img-size" />
                                                    </div>
                                                    <div class="team-score">{{$game->team_2_score>0?$game->team_2_score:''}}</div>
                                                </div>
                                                <div class="match-info right">
                                                    <div class="team-score">{{$game->team_1_score>0?$game->team_1_score:''}}</div>
                                                    <div class="team-logo">
                                                        <img alt="{{@$teams[$game->team_1_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_1_id]) }}" class="img-size" />
                                                    </div>
                                                    <div class="team-name">
                                                        <h3 class="hide-on-mobile">{{@$teams[$game->team_1_id]}}</h3>
                                                        <h3 class="show-on-mobile">{{getLastWord(@$teams[$game->team_1_id])}}</h3>
                                                        <div class="hide-on-mobile">
                                                            @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">
                                                                    @if(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'] == 0)
                                                                        {{'Even'}}
                                                                    @else
                                                                    {{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] > @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}
                                                                    @endif
                                                                </span>
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_team_ats'], '-0')}}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="match-txt">
                                                <a class="btn btn-outline-primary btn-sm" href="/game-report/{{$game->game_id}}"><strong>View Game</strong></a>&nbsp;
                                                <a class="btn btn-outline-primary" onclick="setPreCookie({{$game->team_1_id}},{{$game->team_2_id}})" href="/prediction/create"><strong>Adjust Ratings</strong></a>
                                            </div>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                            @else
                                <h2 style="text-align: center;">No Games Available</h2>
                            @endif
                            <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <ul>
                                    @foreach($user_games as $key => $game)
                                        <li class="d-flex align-items-center justify-content-between">

                                            <!--- new struct add start---->
                                            <div class="detail-info match-txt">
                                                <p>{{date("l, F d", strtotime($game->game_date))}}</p>
                                                <div class="show-on-mobile">
                                                    <div class="d-flex justify-content-center mt-3">
                                                        @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] < @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_team_ats'], '-0')}}</span>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex justify-content-center mt-3">
                                                        @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] > @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_team_ats'], '-0')}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <!--- new struct add end---->

                                            <div class="d-flex align-items-center">
                                                <div class="match-info add-style">
                                                    <div class="team-name">
                                                        <h3 class="hide-on-mobile">{{@$teams[$game->team_2_id]}}</h3>
                                                        <h3 class="show-on-mobile">{{getLastWord(@$teams[$game->team_2_id])}}</h3>
                                                        <div class="hide-on-mobile">
                                                        @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">
                                                                @if(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'] == 0)
                                                                    {{'Even'}}
                                                                @else
                                                                {{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] < @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}
                                                                @endif
                                                            </span>
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_team_ats'], '-0')}}</span>
                                                        @endif
                                                        </div>
                                                    </div>
                                                    <div class="team-logo add-margin">
                                                        <img alt="{{@$teams[$game->team_2_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_2_id]) }}" class="img-size" />
                                                    </div>
                                                    <div class="team-score">{{$game->team_2_score>0?$game->team_2_score:''}}</div>
                                                </div>
                                                <div class="match-info right">
                                                    <div class="team-score">{{$game->team_1_score>0?$game->team_1_score:''}}</div>
                                                    <div class="team-logo">
                                                        <img alt="{{@$teams[$game->team_1_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_1_id]) }}" class="img-size" />
                                                    </div>
                                                    <div class="team-name">
                                                        <h3 class="hide-on-mobile">{{@$teams[$game->team_1_id]}}</h3>
                                                        <h3 class="show-on-mobile">{{getLastWord(@$teams[$game->team_1_id])}}</h3>
                                                        <div class="hide-on-mobile">
                                                            @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">
                                                                    @if(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'] == 0)
                                                                     {{'Even'}}
                                                                    @else
                                                                    {{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] > @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}
                                                                    @endif
                                                                </span>
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_team_ats'], '-0')}}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="match-txt">
                                                <a class="btn btn-outline-primary btn-sm" href="/game-report/{{$game->game_id}}"><strong>View Game</strong></a>&nbsp;
                                                <a class="btn btn-outline-primary btn-sm" onclick="setPreCookie({{$game->team_1_id}},{{$game->team_2_id}})" href="/prediction/create"><strong>Adjust Ratings</strong></a>
                                            </div>
                                        </li>
                                    @endforeach
                                    @foreach($free_games as $game)
                                            <li class="d-flex align-items-center justify-content-between">
                                                <!--- new struct add start---->
                                                <div class="detail-info match-txt">
                                                    <p>{{date("l, F d", strtotime($game->game_date))}}</p>
                                                    <div class="show-on-mobile">
                                                        <div class="d-flex justify-content-center mt-3">
                                                            @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] < @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_team_ats'], '-0')}}</span>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex justify-content-center mt-3">
                                                            @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] > @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_team_ats'], '-0')}}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--- new struct add end---->

                                                <div class="d-flex align-items-center">
                                                    <div class="match-info add-style">
                                                        <div class="team-name">
                                                            <h3 class="hide-on-mobile">{{@$teams[$game->team_2_id]}}</h3>
                                                            <h3 class="show-on-mobile">{{getLastWord(@$teams[$game->team_2_id])}}</h3>
                                                            <div class="hide-on-mobile">
                                                                @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                                    <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">
                                                                        @if(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'] == 0)
                                                                            {{'Even'}}
                                                                        @else
                                                                        {{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] < @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}
                                                                        @endif
                                                                    </span>
                                                                    <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_team_ats'], '-0')}}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="team-logo add-margin">
                                                            <img alt="{{@$teams[$game->team_2_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_2_id]) }}" class="img-size" />
                                                        </div>
                                                        <div class="team-score">{{$game->team_2_score>0?$game->team_2_score:''}}</div>
                                                    </div>
                                                    <div class="match-info right">
                                                        <div class="team-score">{{$game->team_1_score>0?$game->team_1_score:''}}</div>
                                                        <div class="team-logo">
                                                            <img alt="{{@$teams[$game->team_1_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_1_id]) }}" class="img-size" />
                                                        </div>
                                                        <div class="team-name">
                                                            <h3 class="hide-on-mobile">{{@$teams[$game->team_1_id]}}</h3>
                                                            <h3 class="show-on-mobile">{{getLastWord(@$teams[$game->team_1_id])}}</h3>
                                                            <div class="hide-on-mobile">
                                                                @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                                    <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">
                                                                        @if(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'] == 0)
                                                                            {{'Even'}}
                                                                         @else
                                                                        {{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] > @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}
                                                                         @endif
                                                                    </span>
                                                                    <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_team_ats'], '-0')}}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="match-txt">
                                                    <a class="btn btn-outline-primary btn-sm" href="/game-report/{{$game->game_id}}"><strong>View Game</strong></a>&nbsp;
                                                    <a class="btn btn-outline-primary btn-sm" onclick="setPreCookie({{$game->team_1_id}},{{$game->team_2_id}})" href="/prediction/create"><strong>Adjust Ratings</strong></a>
                                                </div>
                                            </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="tab-pane" id="oldgames" role="tabpanel" aria-labelledby="oldgames-tab">
                                <ul>
                                    @foreach($past_games as $key => $game)
                                        <li class="d-flex align-items-center justify-content-between">
                                            <!--- new struct add start---->
                                            <div class="detail-info match-txt">
                                                <p>{{date("l, F d", strtotime($game->game_date))}}</p>
                                                <div class="show-on-mobile">
                                                    <div class="d-flex justify-content-center mt-3">
                                                        @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] < @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_team_ats'], '-0')}}</span>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex justify-content-center mt-3">
                                                        @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">
                                                                @if(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'] == 0)
                                                                    {{'Even'}}
                                                                @else
                                                                    {{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] > @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}
                                                                @endif
                                                            </span>
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_team_ats'], '-0')}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <!--- new struct add end---->

                                            <div class="d-flex align-items-center">
                                                <div class="match-info add-style">
                                                    <div class="team-name">
                                                        <h3 class="hide-on-mobile">{{@$teams[$game->team_2_id]}}</h3>
                                                        <h3 class="show-on-mobile">{{getLastWord(@$teams[$game->team_2_id])}}</h3>
                                                        <div class="hide-on-mobile">
                                                            @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">
                                                                    @if(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'] == 0)
                                                                        {{'Even'}}
                                                                     @else
                                                                    {{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] < @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}
                                                                     @endif
                                                                </span>
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_team_ats'], '-0')}}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="team-logo add-margin">
                                                        <img alt="{{@$teams[$game->team_2_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_2_id]) }}" class="img-size" />
                                                    </div>
                                                    <div class="team-score">{{$game->team_2_score>0?$game->team_2_score:''}}</div>
                                                </div>
                                                <div class="match-info right">
                                                    <div class="team-score">{{$game->team_1_score>0?$game->team_1_score:''}}</div>
                                                    <div class="team-logo">
                                                        <img alt="{{@$teams[$game->team_1_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_1_id]) }}" class="img-size" />
                                                    </div>
                                                    <div class="team-name">
                                                        <h3 class="hide-on-mobile">{{@$teams[$game->team_1_id]}}</h3>
                                                        <h3 class="show-on-mobile">{{getLastWord(@$teams[$game->team_1_id])}}</h3>
                                                        <div class="hide-on-mobile">
                                                            @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">
                                                                    @if(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'] == 0)
                                                                        {{'Even'}}
                                                                    @else
                                                                        {{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] > @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}
                                                                    @endif
                                                                </span>
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff;min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_team_ats'], '-0')}}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="match-txt">
                                                <a class="btn btn-outline-primary btn-sm" href="/game-report/{{$game->game_id}}"><strong>View Game</strong></a>&nbsp;
                                                <a class="btn btn-outline-primary btn-sm" onclick="setPreCookie({{$game->team_1_id}},{{$game->team_2_id}})" href="/prediction/create"><strong>Adjust Ratings</strong></a>
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
        <div class="row">
            <div class="col-12 col-lg-12">
                <h2 class="mb-4" id="Package-list">NFL Packages</h2>
                <div class="nav tab-link">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="automatic-purchase" data-bs-toggle="tab" data-bs-target="#automatic" type="button" role="tab" aria-controls="automatic" aria-selected="false">Subscribe</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="one-time-purchase" data-bs-toggle="tab" data-bs-target="#one-time" type="button" role="tab" aria-controls="one-time" aria-selected="true">One-Time Payment</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="automatic" role="tabpanel" aria-labelledby="automatic-purchase">
                        <div class="packages-card-holder">

                            @foreach ($packages as $key => $package)
                                <div class="card" style="height: 390px !important;">
                                    <div class="d-flex justify-content-between">
                                        <span class="package-title">{{-- Package {{$package->id}} --}}
                                            @if(in_array($package->id,$subscriptions))
                                                {!! Form::model($package, ['route' => ['subscriptions.update', $package->subscription_id],'onsubmit'=>'return confirm("Are you sure, You want to cancel Subscription for this Package!");', 'method'=>'PATCH']) !!}
                                                    <button type="submit" class="btn btn-outline-primary btn-sm" style="border-radius: 0px !important; font-size: 12px; margin: 0px; line-height: 8px !important;">UnSubscribe</button>
                                                {!! Form::close() !!}
                                            @else
                                                <br/>
                                            @endif
                                        </span>
                                      <!--  <span class="badge bg-success" style="color: white;">2 Free Games</span> -->
                                    </div>
                                    <div class="wrapp-div" style="min-height: 200px;">
                                        <h2>{{$package->name}}</h2>
                                        <p title="{{ $package->detail }}">{{ $package->detail }}</p>
                                    </div>
                                    <ul><li>
                                            <div class="form-check">
                                                <label class="form-check-label" for="flexRadioDefault6">
                                                    {{ucfirst($package->interval)}} Payment
                                                    <h3 class="text-skyBlue">
                                                        ${{number_format($package->subscription_price/100, 2)}}
                                                    </h3>
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="match-txt d-flex align-items-center justify-content-between">
                                        @if(in_array($package->id,$subscriptions))
                                            <strong style="color:red;"><i class="fa fa-check" style="color: #29CC6A; font-size: 24px;"></i>Subscribed</strong>
                                        @else
                                            <a class="btn btn-outline-primary btn-red" title="Automatic Recurring Payment" href="{{ route('payment', [$package->id, 'subscribe']) }}"><strong>Subscribe</strong></a>
                                        @endif

                                    @if(in_array($package->id,$subscriptions) && $package->id == 8)
                                        <span class="badge" style="background: #F7002E; color: white;">
                                            <a style="text-decoration: none;" href="{{ route('nfl3-games') }}">Select Games</a>
                                        </span>
                                    @endif
                                    </div>

                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="tab-pane" id="one-time" role="tabpanel" aria-labelledby="one-time-purchase">
                        <div class="packages-card-holder">

                            @foreach ($packages as $key => $package)
                                <div class="card" style="height: 390px !important;">
                                    <div class="d-flex justify-content-between">
                                        <span class="package-title">{{-- Package {{$package->id}} --}}</span>
                                       <!--  <span class="badge bg-success" style="color: white;">2 Free Games</span> -->
                                    </div>
                                    <div class="wrapp-div" style="min-height: 200px;">
                                        <h2>{{$package->name}}</h2>
                                        <p title="{{ $package->detail }}">{{ $package->detail }}</p>
                                    </div>
                                    <ul>
                                        <li>
                                            <div class="form-check">
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    <h3 class="text-skyBlue">
                                                        ${{number_format($package->price/100, 2)}}
                                                    </h3>
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="match-txt d-flex align-items-center justify-content-between">
                                        @if(in_array($package->id,$single_payments))
                                            <strong style="color:red;"><i class="fa fa-check" style="color: #29CC6A; font-size: 24px;"></i>Purchased</strong>
                                        @else
                                            <a class="btn btn-outline-primary btn-red" title="One-Time Payment" href="{{ route('payment', [$package->id, 'purchase']) }}"><strong>Buy Now</strong></a>
                                        @endif

                                        {{--@if(in_array($package->id, [1,2]))
                                            <span class="badge" style="background: #F7002E; color: white;">
                                                {{'Full Season Payment'}}
                                            </span>
                                        @endif--}}
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{!! asset('js/jquery.min.js') !!}"></script>
<script src="{!! asset('js/bootstrap.min.js') !!}"></script>
<script>

    $("#navbarDropdown").click(function(){
        $("#dropdown-menu").toggleClass("show");
    });

    function setPreCookie(team1,team2){
        setCookie('home_team_id',team1,1);
        setCookie('away_team_id',team2,1);
        setCookie('pre_week_number',{{$week_number}},1);
        setCookie('pre_season_id',{{$season_id}},1);
    }

    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    var firstTabEl = document.querySelector('#myTab ul li:last-child a')
     var firstTab = new bootstrap.Tab(firstTabEl)
     firstTab.show()

</script>
