<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}" />

    <title>Chalk Sports Analytics</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">

    <link rel="stylesheet" href="{{ asset('js/scrollbar') }}/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="{{ asset('css') }}/all.css">
</head>
<body>
<style>
@media screen and (max-width: 768px){
    .menu {
        display: block !important;
        position: fixed;
    }
}
</style>
<div id="wrapper">
    <div id="header">
        <div class="logo">
            <a href="{{ url('/home') }}">
                {{ config('app.name', 'Chalk Sports Analytics') }}
            </a>

            <a href="#home-tab">
                NFL
            </a>
        </div>
        <div id="nav" class="nav">
            <!--<div class="burger2 menu">
                <div class="icon"></div>
            </div>-->
            <div class="burger2 menu" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <div class="icon"></div>
            </div>
            <div class="nav-holder navbar-collapse" id="navbarSupportedContent">
                <ul>
                    <li class="{{(Route::current()->getName() != 'nfl-picks' && Route::current()->getName() != 'schedules')?'active':''}}"><a href="/">Home</a></li>
                    <!-- li><a href="#">About us</a></li -->
                    <li class="{{(Route::current()->getName() == 'nfl-picks')?'active':''}}"><a class="nav-link" href="{{ route('nfl-picks') }}">Your NFL Picks</a></li>
                    <li class="{{(Route::current()->getName() == 'schedules')?'active':''}}"><a class="nav-link"  href="{{ route('schedules', ['season_id'=>$season_id, 'week_number'=>$week_number]) }}">NFL Schedules</a></li>
                    <li ><a class="nav-link" href="{{url('/')}}#Package-list">Packages</a></li>
                </ul>
                <ul>
                    @guest
                        @if (Route::has('login'))
                            <li>
                                <a class="btn btn-primary btn-sm text-white" href="{{ route('login') }}"><strong>{{ __('Login') }}</strong></a>
                            </li>
                        @endif
                        @if (Route::has('register'))
                            <li>
                                <a class="btn btn-primary btn-sm text-white" href="{{ route('register') }}"><strong>{{ __('Register') }}</strong></a>
                            </li>
                        @endif
                    @else
                        @can('user-list')
                            <li><a class="nav-link" href="{{ route('users.index') }}">Users</a></li>
                        @endcan
                        @can('role-list')
                            <li><a class="nav-link" href="{{ route('roles.index') }}">Roles</a></li>
                        @endcan
                        @can('permission-list')
                            <li><a class="nav-link" href="{{ route('permissions.index') }}">Permission</a></li>
                        @endcan
                        @can('team-list')
                            <li><a class="nav-link" href="{{ route('teams.index') }}">Teams</a></li>
                        @endcan
                        @can('package-list')
                            <li><a class="nav-link" href="{{ route('packages.index') }}">Packages</a></li>
                        @endcan
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @can('prediction-create')
                                    <a class="dropdown-item" style="text-decoration: none;" href="{{ route('prediction.create') }}">Custom NFL Predictive Model</a>
                                @endcan
                                @can('influence-factor-update')
                                    <a  class="dropdown-item" href="/games/{{auth()->user()->id}}/edit">Adjust Team Ratings</a>
                                @endcan
                                @can('weight-factor-create')
                                    <a class="dropdown-item" style="text-decoration: none;" href="{{ route('games.create') }}">Update Weight Factors</a>
                                @endcan
                                @can('run-test')
                                    <a class="dropdown-item" style="text-decoration: none;" href="{{ route('run-test') }}">Run Test</a>
                                @endcan

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
    <div class="banner">
        <img alt="banner image" src="{{ asset('images/banner-bg.png') }}" />
    </div>
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
    <div id="main">
        <div class="container-fluid">
            <div class="row margin-top">
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
                                {{-- <div class="d-flex mt-3">
                                    <span class="badge bg-secondary">Opening - {{date("h:i", strtotime($game1[0]->game_time))}}</span>
                                    <span class="badge bg-secondary">Currrent - {{date("h:i")}} </span>
                                </div> --}}
                                @if(isset($market_ats[@$game1[0]->week_number][@$game1[0]->team_2_id][@$game1[0]->team_1_id]))
                                    <div class="d-flex justify-content-center mt-3">
                                        <span class="badge bg-secondary"><em>Spread</em> <em>{{(@$market_ats[$game1[0]->week_number][$game1[0]->team_2_id][$game1[0]->team_1_id]['home_money_line'] < @$market_ats[$game1[0]->week_number][$game1[0]->team_2_id][$game1[0]->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game1[0]->week_number][$game1[0]->team_2_id][$game1[0]->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game1[0]->week_number][$game1[0]->team_2_id][$game1[0]->team_1_id]['spread'])}}</em></span>
                                        <span class="badge bg-secondary"><em>Spread</em> <em>{{(@$market_ats[$game1[0]->week_number][$game1[0]->team_2_id][$game1[0]->team_1_id]['home_money_line'] > @$market_ats[$game1[0]->week_number][$game1[0]->team_2_id][$game1[0]->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game1[0]->week_number][$game1[0]->team_2_id][$game1[0]->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game1[0]->week_number][$game1[0]->team_2_id][$game1[0]->team_1_id]['spread'])}}</em></span>
                                    </div>

                                    <div class="d-flex justify-content-center mt-3">
                                        <span class="badge bg-secondary"><em>Money Line</em> <em>{{addPlusSymbol(@$market_ats[$game1[0]->week_number][$game1[0]->team_2_id][$game1[0]->team_1_id]['away_money_line'])}}</em></span>
                                        <span class="badge bg-secondary"><em>Money Line</em> <em>{{addPlusSymbol(@$market_ats[$game1[0]->week_number][$game1[0]->team_2_id][$game1[0]->team_1_id]['home_money_line'])}}</em></span>
                                    </div>
                                    <div class="d-flex justify-content-center mt-3">
                                        <span class="badge bg-secondary"><em>Over/Under</em> <em>{{@$market_ats[$game1[0]->week_number][$game1[0]->team_2_id][$game1[0]->team_1_id]['over_under']}}</em></span>
                                        <span class="badge bg-secondary"><em>Over/Under</em> <em>{{@$market_ats[$game1[0]->week_number][$game1[0]->team_2_id][$game1[0]->team_1_id]['over_under']}}</em></span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-center mt-3 hide-on-mobile">
                                    <a class="btn btn-outline-primary btn-sm" href="{{ url('/view-game/'.@$game1[0]->game_id) }}"><strong>View Game</strong></a>
                                    <a class="btn btn-outline-primary btn-sm" href="{{ url('/predictions') }}"><strong>Adjust Ratings</strong></a>
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
                                <div class="d-flex align-items-center">
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
                                            <img src="{{ asset('images/logos/'.@$logos[$game1[0]->team_1_id]) }}"/>
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
                                    <div class="d-flex justify-content-center mt-3">
                                        <a class="btn btn-outline-primary btn-sm" href="{{ url('/view-game/'.@$game1[0]->game_id) }}"><strong>View Game</strong></a>
                                        <a class="btn btn-outline-primary btn-sm" href="{{ url('/adjust-rating') }}"><strong>Adjust Ratings</strong></a>
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
                                {{-- <div class="d-flex mt-3 justify-content-center">
                                    <span class="badge bg-secondary">Opening - {{date("h:i", strtotime($game2[1]->game_time))}}</span>
                                    <span class="badge bg-secondary">Currrent - {{date("h:i")}} </span>
                                </div> --}}
                                @if(isset($market_ats[@$game2[1]->week_number][@$game2[1]->team_2_id][@$game2[1]->team_1_id]))
                                    <div class="d-flex mt-3 justify-content-center">
                                        <span class="badge bg-secondary"><em>Spread</em> <em>{{(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['home_money_line'] < @$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['spread'])}}</em></span>
                                        <span class="badge bg-secondary"><em>Spread</em> <em>{{(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['home_money_line'] > @$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['spread'])}}</em></span>
                                    </div>

                                    <div class="d-flex mt-3 justify-content-center">
                                        <span class="badge bg-secondary"><em>Money Line</em> <em>{{addPlusSymbol(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['away_money_line'])}}</em></span>
                                        <span class="badge bg-secondary"><em>Money Line</em> <em>{{addPlusSymbol(@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['home_money_line'])}}</em></span>
                                    </div>
                                    <div class="d-flex mt-3 justify-content-center">
                                        <span class="badge bg-secondary"><em>Over/Under</em> <em>{{@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['over_under']}}</em></span>
                                        <span class="badge bg-secondary"><em>Over/Under</em> <em>{{@$market_ats[$game2[1]->week_number][$game2[1]->team_2_id][$game2[1]->team_1_id]['over_under']}}</em></span>
                                    </div>
                                @endif
                                <div class="d-flex mt-3 justify-content-center hide-on-mobile">
                                    <a class="btn btn-outline-primary btn-sm" href="{{ url('/view-game/'.@$game2[1]->game_id) }}"><strong>View Game</strong></a>
                                    <a class="btn btn-outline-primary btn-sm" href="{{ url('/predictions') }}"><strong>Adjust Ratings</strong></a>
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
                                    <div class="d-flex mt-3 justify-content-center">
                                    <a class="btn btn-outline-primary btn-sm" href="{{ url('/view-game/'.@$game2[1]->game_id) }}"><strong>View Game</strong></a>
                                    <a class="btn btn-outline-primary btn-sm" href="{{ url('/adjust-rating') }}"><strong>Adjust Ratings</strong></a>
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
                    <div class="card card-table">
                        <div class="nav tab-link">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Week {{$week_number}}</button>
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
                                                        <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff; min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_team_ats'], '-0')}}</span>
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
                                                            <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff; min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_team_ats'], '-0')}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="d-flex align-items-center">
                                                <div class="match-info add-style">
                                                    <div class="team-name">
                                                        <h3 class="hide-on-mobile">{{@$teams[$game->team_2_id]}}</h3>
                                                        <h3 class="show-on-mobile">{{getLastWord(@$teams[$game->team_2_id])}}</h3>
                                                        <div class="d-flex hide-on-mobile">
                                                            @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">
                                                                    @if(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'] == 0)
                                                                        {{'Even'}}
                                                                    @else
                                                                        {{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] < @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}
                                                                    @endif
                                                                </span>
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span><span style="font-size: 12px; color:#fff; min-width: 70px;" class="badge bg-secondary">ATS &nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_team_ats'], '-0')}}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="team-logo add-margin">
                                                        <img alt="{{@$teams[$game->team_2_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_2_id]) }}"  class="img-size"/>
                                                    </div>
                                                    <div class="team-score">{{$game->team_2_score>0?$game->team_2_score:' '}}</div>
                                                </div>
                                                <div class="match-info right">
                                                    <div class="team-score">{{$game->team_1_score>0?$game->team_1_score:' '}}</div>
                                                    <div class="team-logo">
                                                        <img alt="{{@$teams[$game->team_1_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_1_id]) }}"  class="img-size"/>
                                                    </div>
                                                    <div class="team-name">
                                                        <h3 class="hide-on-mobile">{{@$teams[$game->team_1_id]}}</h3>
                                                        <h3 class="show-on-mobile">{{getLastWord(@$teams[$game->team_1_id])}}</h3>
                                                        <div class="d-flex hide-on-mobile">
                                                            @if(isset($market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]))
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">
                                                                    @if(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'] == 0)
                                                                        {{'Even'}}
                                                                    @else
                                                                        {{(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'] > @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread']):addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['spread'])}}
                                                                    @endif
                                                                </span>
                                                                <span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{addPlusSymbol(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_money_line'])}}</span><span style="font-size: 12px; color:#fff;" class="badge bg-secondary">{{@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['over_under']}}</span> <span style="font-size: 12px; color:#fff; min-width: 70px;" class="badge bg-secondary">ATS &nbsp;&nbsp;{{rtrim(@$market_ats[$game->week_number][$game->team_2_id][$game->team_1_id]['home_team_ats'], '-0')}}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="match-txt">
                                                <a class="btn btn-outline-primary btn-sm" href="{{ url('/view-game/'.$game->game_id) }}"><strong>View Game</strong></a>&nbsp;
                                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('predictions') }}"><strong>Adjust Ratings</strong></a>
                                            </div>
                                        </li>
                                        @endforeach

                                    </ul>
                                </div>
                                @else
                                    <h2 style="text-align: center;">No Games Available.</h2>
                                @endif
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
                                <div class="card">
                                    <div class="d-flex justify-content-between">
                                        <span class="package-title">{{-- Package {{$package->id}} --}}</span>
                                        <!-- <span class="badge bg-success" style="color: white;">2 Free Games</span> -->
                                    </div>
                                    <div class="wrapp-div">
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
                                        <a class="btn btn-outline-primary btn-red" title="Automatic Recurring Payment" href="{{ route('payment', [$package->id, 'subscribe']) }}"><strong>Subscribe</strong></a>
                                       {{-- <span class="badge" style="background: #F7002E; color: white;">{{ucfirst($package->interval)}} Payment</span> --}}
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="tab-pane" id="one-time" role="tabpanel" aria-labelledby="one-time-purchase">
                            <div class="packages-card-holder">

                                @foreach ($packages as $key => $package)
                                    <div class="card">
                                        <div class="d-flex justify-content-between">
                                            <span class="package-title">{{-- Package {{$package->id}} --}}</span>
                                          <!--  <span class="badge bg-success"  style="color: white;">2 Free Games</span> -->
                                        </div>
                                        <div class="wrapp-div">
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
                                        <div class="match-txt  d-flex align-items-center justify-content-between">
                                            <a class="btn btn-outline-primary btn-red" title="One-Time Payment" href="{{ route('payment', [$package->id, 'purchase']) }}"><strong>Buy Now</strong></a>
                                                @if(in_array($package->id, [1,2]))
                                                {{--<span class="badge" style="background: #F7002E; color: white;">
                                                    {{'Full Season Payment'}}
                                                </span>--}}
                                            @endif
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
    <!-- Footer -->
    <footer id="footer" class="text-center text-lg-start text-white" style="background-color: #3e4551;" >
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6">
                        <div class="footer-txt">
                            <h5>The Ultimate Football Predictive Model</h5>
                            <p>This program uses historical statistics along with multiple influence factors to generate win probabilities for future games.</p>
                            <ul class="d-flex">
                                <li><a class="btn btn-outline-light btn-floating m-1" target="_blank" href="https://twitter.com/ChalkSports" role="button"><i class="fab fa-twitter"></i></a></li>
                                <li><a class="btn btn-outline-light btn-floating m-1" target="_blank" href="https://www.tiktok.com/@chalksportsanalytics?lang=en" role="button" ><i class="fab fa-tiktok"></i></a></li>
                                <li><a class="btn btn-outline-light btn-floating m-1" target="_blank" href="https://www.instagram.com/chalk.sports.analytics/" role="button"><i class="fab fa-instagram"></i></a></li>
                                <li><a class="btn btn-outline-light btn-floating m-1" target="_blank" href="https://www.youtube.com/channel/UC0MKkx_-x4F48KceIdxLkxA" role="button"><i class="fab fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="footer-txt">
                            <h5>Web Pages</h5>
                            <ul class="list-unstyled mb-0">
                                <li style="margin-bottom: 5px;">
                                    <a class="text-white" style="text-decoration: none;" href="{{ url('/') }}">Home</a>
                                </li>
                                <li style="margin-bottom: 5px;">
                                    <a class="text-white" style="text-decoration: none;" href="{{ route('nfl-picks') }}">Your NFL Picks</a>
                                </li>
                                <li style="margin-bottom: 5px;">
                                    <a class="text-white" style="text-decoration: none;" href="{{ route('games.index') }}">NFL Schedules</a>
                                </li>
                                <li style="margin-bottom: 5px;">
                                    <a class="text-white" style="text-decoration: none;" href="{{ url('/') }}#Package-list">Packages</a>
                                </li>
                                <li style="margin-bottom: 5px;">
                                    <a class="text-white" style="text-decoration:none;" href="mailto:chalksportsanalytics@gmail.com">Contact Us</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)" >© {{date('Y')}} Copyright:
                <a class="text-white" href="https://chalksportsanalytics.com/" >ChalkSportsAnalytics.com</a>
            </div>
        </footer>
        <footer
            class="text-center text-lg-start text-white"
            style="background-color: #3e4551; display: none"
        >
            <!-- Grid container -->
            <div class="container p-4 pb-0">
                <!-- Section: Links -->
                <section class="">
                    <!--Grid row-->
                    <div class="row justify-content-md-center">
                        <!--Grid column-->
                        <div class="col-lg-6 col-md-8 mb-4 mb-md-0">
                            <h5 class="text-uppercase">The Ultimate Football Predictive Model</h5>

                            <p>
                                This program uses historical statistics along with multiple influence factors to generate win probabilities for future games.
                            </p>
                        </div>
                        <!--Grid column-->

                        <!--Grid column-->
                        <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                            <h5 class="text-uppercase">Web Pages</h5>

                            <ul class="list-unstyled mb-0">
                                <li style="margin-bottom: 5px;">
                                    <a class="text-white" style="text-decoration: none;" href="{{ url('/') }}">Home</a>
                                </li>
                                <li style="margin-bottom: 5px;">
                                    <a class="text-white" style="text-decoration: none;" href="{{ route('nfl-picks') }}">Your NFL Picks</a>
                                </li>
                                <li style="margin-bottom: 5px;">
                                    <a class="text-white" style="text-decoration: none;" href="{{ route('schedules', ['season_id'=>$season_id, 'week_number'=>$week_number]) }}">NFL Schedules</a>
                                </li>
                                <li style="margin-bottom: 5px;">
                                    <a class="text-white" style="text-decoration: none;" href="#Package-list">Packages</a>
                                </li>
                                <li style="margin-bottom: 5px;">
                                    <a class="text-white" style="text-decoration:none;" href="mailto:chalksportsanalytics@gmail.com">Contact Us</a>
                                </li>
                            </ul>
                        </div>
                        <!--Grid column-->

                        <!--Grid column-->
{{--                        <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                            <h5 class="text-uppercase">User Section</h5>

                            <ul class="list-unstyled mb-0">
                                <li style="margin-bottom: 5px;">
                                    <a class="text-white" style="text-decoration: none;" href="{{ route('prediction.create') }}">Prediction</a>
                                </li>
                                <li style="margin-bottom: 5px;">
                                    <a class="text-white" style="text-decoration: none;" href="{{ url('games/1/edit') }}">Simulation</a>
                                </li>
                            </ul>
                        </div> --}}
                        <!--Grid column-->

                    </div>
                    <!--Grid row-->
                </section>
                <!-- Section: Links -->

                <hr class="mb-4" />
            </div>
            <!-- Grid container -->

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)" >© {{date('Y')}} Copyright:
                <a class="text-white" href="https://chalksportsanalytics.com/" >ChalkSportsAnalytics.com</a>
            </div>
            <!-- Copyright -->
        </footer>
        <!-- Footer -->
</div>
<script src="{!! asset('js/jquery.min.js') !!}"></script>
<script src="{!! asset('js/scrollbar/jquery.mCustomScrollbar.concat.min.js') !!}"></script>
<script src="{!! asset('js/bootstrap.min.js') !!}"></script>
<script>
    $(document).ready(function(){
        $(".burger2").click(function(){
            $(".burger2").toggleClass("open");
            $("#nav").toggleClass("slideMenu");
        });
    })

    $('.nav-holder ul li').on('click', 'li', function() {
        $('.nav-holder li.active').removeClass('active');
        $(this).addClass('active');
    });

    function setPreCookie(team1,team2){
        setCookie('home_team_id',team1,1);
        setCookie('away_team_id',team2,1);
    }

    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    var firstTabEl = document.querySelector('#myTab ul li:last-child a')
    //var firstTab = new bootstrap.Tab(firstTabEl)
    //firstTab.show()

</script>
</body>
</html>
