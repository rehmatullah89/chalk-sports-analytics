@extends('layouts.web-app')

@section('content')
        <?php
        if(!is_null($free_games) && count($free_games) == 1){
            $game1= $free_games;
            $game2= $games;
        }else if(!is_null($free_games) && count($free_games) > 1){
            $game1= $free_games;
            $game2= $free_games;
        }else{
            $game1= $games;
            $game2= $games;
        }
        ?>
        <link rel="stylesheet" href="{{ asset('js/scrollbar') }}/jquery.mCustomScrollbar.min.css">
        <link rel="stylesheet" href="{{ asset('css') }}/all.css">
        <div class="banner">
            <img alt="banner image" src="{{ asset('images/banner-bg.png') }}" />
        </div>
        <div id="main">
            <div class="container-fluid">
                <div class="row margin-top">
                    <div class="col-6">
                        <div class="card">
                            <div class="ribbon ribbon-top-right"><span>Free Game</span></div>
                            <div class="d-flex align-items-center card-box">
                                <div class="team-info">
                                    <div class="team-logo">
                                        <img src="{{ asset('images/logos/'.@$logos[$game1[0]->team_2_id]) }}" />
                                    </div>
                                    <h3>{{@$teams[$game1[0]->team_2_id]}}</h3>
                                </div>
                                <div class="detail-info">
                                    <h2>{{date("l, F d", strtotime($game1[0]->game_date))}}</h2>
                                    <!-- <p>NEW YORKERS STADIUM</p> -->
                                    <div class="d-flex justify-content-center mt-3">
                                        <span class="badge bg-secondary">Opening - {{date("h:i", strtotime($game1[0]->game_time))}}</span>
                                        <span class="badge bg-secondary">Currrent - {{date("h:i")}} </span>
                                    </div>
                                </div>
                                <div class="team-info">
                                    <div class="team-logo">
                                        <img src="{{ asset('images/logos/'.@$logos[$game1[0]->team_1_id]) }}" />
                                    </div>
                                    <h3>{{@$teams[$game1[0]->team_1_id]}}</h3>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-warning btn-sm" href="{{ url('/view-game/'.$game1[0]->game_id) }}"><strong>View Game</strong></a>
                                <a class="btn btn-success btn-sm" href="{{ url('/adjust-rating') }}"><strong>Adjust Ratings</strong></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="ribbon ribbon-top-right"><span>Free Game</span></div>
                            <div class="d-flex align-items-center card-box">
                                <div class="team-info">
                                    <div class="team-logo">
                                        <img src="{{ asset('images/logos/'.@$logos[$game2[1]->team_2_id]) }}" />
                                    </div>
                                    <h3>{{@$teams[$game2[1]->team_2_id]}}</h3>
                                </div>
                                <div class="detail-info">
                                    <h2>{{date("l, F d", strtotime($game2[1]->game_date))}}</h2>
                                    <!-- <p>NEW YORKERS STADIUM</p> -->
                                    <div class="d-flex justify-content-center mt-3">
                                        <span class="badge bg-secondary">Opening - {{date("h:i", strtotime($game2[1]->game_time))}}</span>
                                        <span class="badge bg-secondary">Currrent - {{date("h:i")}} </span>
                                    </div>
                                </div>
                                <div class="team-info">
                                    <div class="team-logo">
                                        <img src="{{ asset('images/logos/'.@$logos[$game2[1]->team_1_id]) }}" />
                                    </div>
                                    <h3>{{@$teams[$game2[1]->team_1_id]}}</h3>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-warning btn-sm" href="{{ url('/view-game/'.$game2[1]->game_id) }}"><strong>View Game</strong></a>
                                <a class="btn btn-success btn-sm" href="{{ url('/adjust-rating') }}"><strong>Adjust Ratings</strong></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <h2 class="mb-4">NFL Schedule</h2>
                        <div class="card card-table" id="schedules">
                            <div class="nav tab-link">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">All Games</button>
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
                                    <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <ul>
                                            @foreach($games as $key => $game)
                                                <li class="d-flex align-items-center justify-content-between">
                                                    <div class="match-txt">
                                                        <p><strong>{{date("l, F d", strtotime($game->game_date))}}</strong></p>
                                                    </div>

                                                    <div class="d-flex align-items-center">
                                                        <div class="match-info add-style">
                                                            <div class="team-name">
                                                                <h3>{{@$teams[$game->team_2_id]}}</h3>
                                                            </div>
                                                            <div class="team-logo add-margin">
                                                                <img alt="{{@$teams[$game->team_2_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_2_id]) }}"  class="img-size"/>
                                                            </div>
                                                        </div>
                                                        <div class="match-info right">
                                                            <div class="team-logo">
                                                                <img alt="{{@$teams[$game->team_1_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_1_id]) }}"  class="img-size"/>
                                                            </div>
                                                            <div class="team-name">
                                                                <h3>{{@$teams[$game->team_1_id]}}</h3>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="match-txt">
                                                        <a class="btn btn-outline-primary btn-sm" href="{{ url('/view-game/'.$game->game_id) }}"><strong>View Game</strong></a>&nbsp;
                                                        <a class="btn btn-outline-primary btn-sm" href="{{ url('/adjust-rating') }}"><strong>Adjust Ratings</strong></a>
                                                    </div>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                    <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <ul>
                                            @foreach($user_games as $key => $game)
                                                <li class="d-flex align-items-center justify-content-between">
                                                    <div class="match-txt">
                                                        <p><strong>{{date("l, F d", strtotime($game->game_date))}}</strong></p>
                                                    </div>

                                                    <div class="d-flex align-items-center">
                                                        <div class="match-info add-style">
                                                            <div class="team-name">
                                                                <h3>{{@$teams[$game->team_2_id]}}</h3>
                                                            </div>
                                                            <div class="team-logo add-margin">
                                                                <img alt="{{@$teams[$game->team_2_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_2_id]) }}"  class="img-size"/>
                                                            </div>
                                                        </div>
                                                        <div class="match-info right">
                                                            <div class="team-logo">
                                                                <img alt="{{@$teams[$game->team_1_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_1_id]) }}"  class="img-size"/>
                                                            </div>
                                                            <div class="team-name">
                                                                <h3>{{@$teams[$game->team_1_id]}}</h3>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="match-txt">
                                                        <a class="btn btn-outline-primary btn-sm" href="{{ url('/view-game/'.$game->game_id) }}"><strong>View Game</strong></a>&nbsp;
                                                        <a class="btn btn-outline-primary btn-sm" href="{{ url('/adjust-rating') }}"><strong>Adjust Ratings</strong></a>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="tab-pane" id="oldgames" role="tabpanel" aria-labelledby="oldgames-tab">
                                        <ul>
                                            @foreach($past_games as $key => $game)
                                                <li class="d-flex align-items-center justify-content-between">
                                                    <div class="match-txt">
                                                        <p><strong>{{date("l, F d", strtotime($game->game_date))}}</strong></p>
                                                    </div>

                                                    <div class="d-flex align-items-center">
                                                        <div class="match-info add-style">
                                                            <div class="team-name">
                                                                <h3>{{@$teams[$game->team_2_id]}}</h3>
                                                            </div>
                                                            <div class="team-logo add-margin">
                                                                <img alt="{{@$teams[$game->team_2_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_2_id]) }}"  class="img-size" />
                                                            </div>
                                                        </div>
                                                        <div class="match-info right">
                                                            <div class="team-logo">
                                                                <img alt="{{@$teams[$game->team_1_id]}}" src="{{ asset('images/logos/'.@$logos[$game->team_1_id]) }}" class="img-size" />
                                                            </div>
                                                            <div class="team-name">
                                                                <h3>{{@$teams[$game->team_1_id]}}</h3>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="match-txt">
                                                        <a class="btn btn-outline-primary btn-sm" href="{{ url('/view-game/'.$game->game_id) }}"><strong>View Game</strong></a>&nbsp;
                                                        <a class="btn btn-outline-primary btn-sm" href="{{ url('/adjust-rating') }}"><strong>Adjust Ratings</strong></a>
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
                                    <button class="nav-link active" id="automatic-purchase" data-bs-toggle="tab" data-bs-target="#automatic" type="button" role="tab" aria-controls="automatic" aria-selected="false"><strong>Subscribe</strong></button>
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
                                                <p>{{ $package->detail }}</p>
                                            </div>
                                            <ul><li>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="flexRadioDefault6">
                                                            Automatic Purchase for next time
                                                            <h3 class="text-skyBlue">
                                                                ${{number_format($package->subscription_price/100, 2)}}
                                                            </h3>
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="match-txt d-flex align-items-center justify-content-between">
                                                    <a class="btn btn-outline-primary" title="Automatic Purchase for next time" href="{{ route('payment', [$package->id, 'subscribe']) }}"><strong>Subscribe</strong></a>
                                                <span class="badge" style="background: #F7002E; color: white;">
                                                    @if(in_array($package->id, [1,2]))
                                                        {{'Full Season'}}
                                                    @else
                                                        {{ucfirst($package->interval)}}
                                                    @endif
                                                </span>
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
                                                <!-- <span class="badge bg-success" style="color: white;">2 Free Games</span> -->
                                            </div>
                                            <div class="wrapp-div">
                                                <h2>{{$package->name}}</h2>
                                                <p>{{ $package->detail }}</p>
                                            </div>
                                            <ul>
                                                <li>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            One-Time Payment
                                                            <h3 class="text-skyBlue">
                                                                ${{number_format($package->price/100, 2)}}
                                                            </h3>
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="match-txt d-flex align-items-center justify-content-between">
                                                    <a class="btn btn-outline-primary btn-red" title="One-Time Payment" href="{{ route('payment', [$package->id, 'purchase']) }}"><strong>Buy Now</strong></a>
                                                <span class="badge" style="background: #F7002E; color: white;">{{ucfirst($package->interval)}}</span>
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
        <script src="{!! asset('js/scrollbar/jquery.mCustomScrollbar.concat.min.js') !!}"></script>
        <script>
            $(document).ready(function(){
                $(".burger2").click(function(){
                    $(".burger2").toggleClass("open");
                    $("#nav").toggleClass("slideMenu");
                });
            })

            var firstTabEl = document.querySelector('#myTab ul li:last-child a')
            var firstTab = new bootstrap.Tab(firstTabEl)
            firstTab.show()

        </script>
@endsection
