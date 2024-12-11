<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Chalk Sports Analytics') }}</title>
    @if(Route::current()->getName() == 'home')
        <link rel="stylesheet" href="{{ asset('js/scrollbar') }}/jquery.mCustomScrollbar.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    @else
        <script src="{{ asset('js/app.js') }}" defer></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all-app.css') }}" rel="stylesheet">
    {{-- Loads Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
   </head>
<body>
    <div id="app" style="height: 100%;">
                <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm heading-bar" id="header">
            <div class="container-fluid" style="text-align: left; padding: 0;">
                <div class="navbar-brand logo" >
                    @if(!auth()->user()->hasRole('admin'))
                         <a href="{{ url('/home') }}">
                    @else
                        <a href="{{ url('/dashboard') }}">
                    @endif
                        {{ config('app.name', 'Chalk Sports Analytics') }}
                        </a>
                    <a>NFL</a>
                </div>
                <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button> -->
                <div class="burger2 menu" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <div class="icon"></div>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto"></ul>
                    <ul class="navbar-nav ml-auto" style="margin-left: auto;">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @hasrole('admin')
                                <li class="{{(Route::current()->getName() == 'home')?'active':''}}">
                                    <a class="nav-link" href="{{ url('/home') }}">Home</a>
                                </li>
                                @can('user-list')
                                    <li class="{{(request()->segment(1) == 'users')?'active':''}}">
                                        <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                                    </li>
                                @endcan
                                @can('role-list')
                                    <li class="{{(request()->segment(1) == 'roles')?'active':''}}">
                                        <a class="nav-link" href="{{ route('roles.index') }}">Roles</a>
                                    </li>
                                @endcan
                                @can('permission-list')
                                    <li class="{{(request()->segment(1) == 'permissions')?'active':''}}">
                                        <a class="nav-link" href="{{ route('permissions.index') }}">Permission</a>
                                    </li>
                                @endcan
                                @can('team-list')
                                    <li class="{{(request()->segment(1) == 'teams')?'active':''}}">
                                        <a class="nav-link" href="{{ route('teams.index') }}">Teams</a>
                                    </li>
                                @endcan
                            @endhasrole

                            @if(!auth()->user()->hasRole('admin'))
                                <li  class="{{(Route::current()->getName() == 'home')?'active':''}}"><a class="nav-link" href="/">Home</a>
                                <li  class="{{(request()->segment(1) == 'prediction' && request()->segment(3) == 'edit')?'active':''}}"><a class="nav-link" href="{{ route('prediction.edit',auth()->user()->id) }}">Your NFL Picks</a></li>
                                <li  class="{{(Route::current()->getName() == 'games.index')?'active':''}}"><a class="nav-link" href="{{ route('games.index') }}">NFL Schedules</a>
                            @endif

                            @can('package-list')
                                @if(!auth()->user()->hasRole('admin'))
                                    <li><a class="nav-link" href="/home#Package-list">Packages</a></li>
                                @else
                                    <li class="{{(request()->segment(1) == 'packages')?'active':''}}"><a class="nav-link" href="{{ route('packages.index') }}">Packages</a></li>
                                @endif
                            @endcan
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div id="dropdown-menu" class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @can('prediction-create')
                                        <a class="dropdown-item" style="text-decoration: none;" href="{{ route('prediction.create') }}">Custom NFL Predictive Model</a>
                                    @endcan
                                    @can('influence-factor-update')
                                        <a  class="dropdown-item" href="/games/{{auth()->user()->id}}/edit">Adjust Team Ratings</a>
                                    @endcan

                                @if(!auth()->user()->hasRole('admin'))
                                    @can('team-list')
                                        <a class="dropdown-item" style="text-decoration: none;" href="{{ route('teams.index') }}">Teams</a>
                                    @endcan
                                    @can('prediction-list')
                                        <a class="dropdown-item" style="text-decoration: none;" href="{{ route('prediction.index') }}">NFL Picks Leaderboard</a>
                                    @endcan
                                @endif
                                    @can('weight-factor-create')
                                        <a class="dropdown-item" style="text-decoration: none;" href="{{ route('games.create') }}">Update Weight Factors</a>
                                    @endcan
                                    @can('run-test')
                                        <a class="dropdown-item" style="text-decoration: none;" href="{{ route('run-test') }}">View Test Report</a>
                                    @endcan
                                    @can('subscription-cancel')
                                        <a class="dropdown-item" style="text-decoration: none;" href="{{ route('subscriptions.index') }}">Cancel Subscriptions</a>
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
        </nav>
                @if(Route::current()->getName() == 'home')
                    <main id="main" style="background: #343a40; min-height: 55%;">
                @else
                    <main id="main" class="inner-page" style="background: #343a40; min-height: 55%;">
                @endif
                @yield('content')
            </main>
            <!-- Footer #3e4551-->
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
                                        <a class="text-white" style="text-decoration: none;" href="{{ route('prediction.edit',auth()->user()->id) }}">Your NFL Picks</a>
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
                    class="text-center text-lg-start text-white " style="background-color: #3e4551; display: none;" >
                    <!-- Grid container -->



                    <div class="container p-4 pb-0">
                        <!-- Section: Links -->
                        <section class="">
                            <!--Grid row-->
                            <div class="row justify-content-md-center">
                                <!--Grid column-->
                                <div class="col-lg-6 col-md-8 mb-4 mb-md-0">
                                    <h5 class="text-uppercase" style="font-weight: 500; font-size: 20px;">The Ultimate Football Predictive Model</h5>

                                    <p style="font-weight: 400; font-size: 16px;">
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
                                            <a class="text-white" style="text-decoration: none;" href="{{ route('prediction.edit',auth()->user()->id) }}">Your NFL Picks</a>
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
                                        <!-- <li style="margin-bottom: 5px;">
                                            <a class="text-white" style="text-decoration: none;" href="#">FAQs</a>
                                        </li> -->
                                    </ul>
                                </div>
                                <!--Grid column-->

                                <!--Grid column-->
{{--                                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
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
<script>
    $(".burger2").click(function(){
        $(".burger2").toggleClass("open");
        $("#navbarSupportedContent").toggleClass("slideMenu");
    });

    $('.nav-holder ul li').on('click', 'li', function() {
        $('.nav-holder li.active').removeClass('active');
        $(this).addClass('active');
    });
</script>
</body>
</html>
