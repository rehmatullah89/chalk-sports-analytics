<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}" />

    <title>Chalk Sports Analytics</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Font Awesome -->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="{{ asset('js/scrollbar') }}/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="{{ asset('css') }}/all.css">
    <link rel="stylesheet" href="{{ asset('css') }}/all-app.css">
</head>
<body>
@php
    if(!isset($season_id) || !isset($week_number)){
        $season_id = 0;
        $week_number = 0;
    }
@endphp
<div id="wrapper" style="min-height: 100%;">
    <div id="header">
        <div class="logo">
            <a href="{{ url('/') }}">
                {{ config('app.name', 'Chalk Sports Analytics') }}
            </a>
            <a href="{{ url('/') }}">NFL</a>
        </div>
        <div id="nav" class="nav">
            <div class="burger2 menu">
                <div class="icon"></div>
            </div>
            <div class="nav-holder">
                <ul>
                    <li class="{{(Route::current()->getName() != 'nfl-picks' && Route::current()->getName() != 'schedules')?'active':''}}"><a href="/">Home</a></li>
                    <!-- li><a href="#">About us</a></li -->
                    <li class="{{(Route::current()->getName() == 'nfl-picks')?'active':''}}"><a class="nav-link" href="{{ route('nfl-picks') }}">Your NFL Picks</a></li>
                    <li class="{{(Route::current()->getName() == 'schedules')?'active':''}}"><a class="nav-link"  href="{{ route('schedules', ['season_id'=>$season_id, 'week_number'=>$week_number]) }}">NFL Schedules</a></li>
                    <li><a class="nav-link" href="{{url('/')}}#Package-list">Packages</a></li>
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

            <div id="main" class="inner-page">
                <div class="container-fluid">
                                    @yield('content')
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
        style="background-color: #2B3138; display: none;"
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

    @if(Route::current()->getName() !== 'nfl-picks' && Route::current()->getName() != 'schedules')

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

    @endif
</script>
</body>
</html>
