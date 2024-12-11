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
            <div class="card-header-web">Custom NFL Predictive Model
                <span class="float-right">
                    @if (count($errors) > 0)
                        <a class="btn btn-outline-primary btn-sm btn-red" style="text-decoration: none; color: white;" href="{{ route('home') }}/#Package-list">Purchase</a>
                    @endif

                    @hasrole('admin')
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('dashboard') }}"><strong>Dashboard</strong></a>
                    @else
                        <a class="btn btn-outline-primary btn-sm" href="{{ url('/') }}"><strong>Home</strong></a>
                    @endhasrole
                </span>
            </div>

            <div class="card-body predictions">
                {!! Form::open(array('route' => 'predictions', 'method'=>'POST')) !!}

                <div class="row mb-4">
                    <div class="col-6">
                        <div class="form-group">
                            <h5 style="color: white;">Season:</h5>
                            <select name="season_id" id="season_id" class='form-select drop-down vodiapicker season' required>
                                @foreach($seasons as $seasonId => $seasonName)
                                    <option value="{{$seasonId}}">{{$seasonName}}</option>
                                @endforeach
                            </select>
                            <div class="main_div_season">
                                <div class="btn-select" id="select_season_id" value=""></div>
                                <div class="b" id="sub_div_season">
                                    <ul id="a" class="ul_season"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6" >
                        <div class="form-group">
                            <h5 style="color: white;">Week:</h5>
                            <select name="week_no" id="pre_week_number" class='form-select drop-down vodiapicker week' required>
                                @foreach($weeks as $weekId => $weekName)
                                    <option value="{{$weekId}}">{{$weekName}}</option>
                                @endforeach
                            </select>
                            <div class="main_div_week">
                                <div class="btn-select" id="select_week_id" value=""></div>
                                <div class="b" id="sub_div_week">
                                    <ul id="a" class="ul_week"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4 position-relative">
                    <div class="col-6" >
                        <div class="form-group ">
                            <h5 style="color: white;">Away Team:</h5>
                            <select name="away_team_id" id="away_team_id" class='vodiapicker teams' required>
                                @foreach($teams as $teamId => $teamName)
                                    <option value="{{$teamId}}" data-thumbnail="{{ asset('images/logos/'.@$logos[$teamId]) }}" >
                                        {{$teamName}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="team-select-away">
                                <div class="btn-select" id="away_team" value=""></div>
                                <div class="b" id="away">
                                    <ul id="a" class="aw"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6" >
                        <div class="form-group">
                            <h5 style="color: white;">Home Team:</h5>
                            <select name="home_team_id" id="home_team_id" class='form-select drop-down vodiapicker' required>
                                @foreach($teams as $teamId => $teamName)
                                    <option value="{{$teamId}}" data-thumbnail="{{ asset('images/logos/'.@$logos[$teamId]) }}" style="background-image: {{ asset('images/logos/'.@$logos[$teamId]) }};" >
                                        {{$teamName}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="team-select-away">
                                <div class="btn-select" id="home_team" value=""></div>
                                <div class="b" id="home">
                                    <ul id="a" class="ho"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="txt-sign">
                        <span>&nbsp;</span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-8" >
                        <div class="form-group">
                            <h5 style="color: white;">Ratings:</h5>
                            <select name="user_id" id="user_id" class='form-select drop-down vodiapicker user' required>
                                @foreach($users as $userId => $userName)
                                    <option value="{{$userId}}">{{$userName}}</option>
                                @endforeach
                            </select>
                            <div class="main_div_user">
                                <div class="btn-select" id="select_user_id" value=""></div>
                                <div class="b" id="sub_div_user">
                                    <ul id="a" class="ul_user"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row ">
                    <div class="d-flex col-xs-12 col-md-8" style="margin-top: 5px;">
                        <button type="submit" class="btn btn-outline-danger">Submit</button>&nbsp;
                        <a class="btn btn-outline-danger" href="/adjust-rating">Adjust Ratings</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div><br/><br/><br/>
            <script src="{!! asset('js/jquery.min.js') !!}"></script>
            <script>
                //***********************start here************//
                var weekNo = {{$week_no}};
                var langArray = [];
                var userArray = [];
                var seasonArray = [];
                var weekArray = [];
                $('.teams option').each(function(){
                    var img = $(this).attr("data-thumbnail");
                    var text = this.innerText;
                    var value = $(this).val();
                    var item = '<li><img src="'+ img +'" alt="" value="'+value+'"/><span>'+ text +'</span></li>';
                    langArray.push(item);
                });

                $('.user option').each(function(){
                    var text = this.innerText;
                    var value = $(this).val();
                    var item = '<li><span value="'+value+'">'+ text +'</span></li>';
                    userArray.push(item);
                });

                $('.season option').each(function(){
                    var text = this.innerText;
                    var value = $(this).val();
                    var item = '<li><span value="'+value+'">'+ text +'</span></li>';
                    seasonArray.push(item);
                });

                $('.week option').each(function(){
                    var text = this.innerText;
                    var value = $(this).val();
                    var item = '<li><span value="'+value+'">'+ text +'</span></li>';
                    weekArray.push(item);
                });

                $('.aw').html(langArray);
                $('.ho').html(langArray);
                $('.ul_user').html(userArray);
                $('.ul_week').html(weekArray);
                $('.ul_season').html(seasonArray);

                $('#home_team').html(langArray[0]);
                $('#away_team').html(langArray[1]);
                $('#select_user_id').html(userArray[0]);
                $('#select_week_id').html(weekArray[weekNo-1]);
                $('#select_season_id').html(seasonArray[0]);
                $("#home_team_id").val(1);
                $("#away_team_id").val(2);
                $("#user_id").val(0);
                $("#pre_week_number").val({{$week_no}});
                $("#season_id").val({{$season_id}});

                //change button stuff on click
                $('.aw li').click(function(){
                    var img = $(this).find('img').attr("src");
                    var value = $(this).find('img').attr('value');
                    var text = this.innerText;
                    var item = '<li><img src="'+ img +'" alt="" /><span>'+ text +'</span></li>';
                    $('#away_team').html(item);
                    $('#away_team').attr('value', value);
                    $("#away_team_id").val(value);
                    $("#away").toggle();
                    //console.log("sunny1:"+value);
                });

                $('.ho li').click(function(){
                    var img = $(this).find('img').attr("src");
                    var value = $(this).find('img').attr('value');
                    var text = this.innerText;
                    var item = '<li><img src="'+ img +'" alt="" /><span>'+ text +'</span></li>';
                    $('#home_team').html(item);
                    $('#home_team').attr('value', value);
                    $("#home_team_id").val(value);
                    $("#home").toggle();
                    //console.log("sunny:"+value);
                });

                $('.ul_user li').click(function(){
                    var text = this.innerText;
                    var item = '<li><span>'+ text +'</span></li>';
                    var value = $(this).find('span').attr('value');
                    $('#select_user_id').html(item);
                    $('#select_user_id').attr('value', value);
                    $("#user_id").val(value);
                    $("#sub_div_user").toggle();
                });

                $('.ul_week li').click(function(){
                    var text = this.innerText;
                    var item = '<li><span>'+ text +'</span></li>';
                    var value = $(this).find('span').attr('value');
                    $('#select_week_id').html(item);
                    $('#select_week_id').attr('value', value);
                    $("#pre_week_number").val(value);
                    $("#sub_div_week").toggle();
                });

                $('.ul_season li').click(function(){
                    var text = this.innerText;
                    var item = '<li><span>'+ text +'</span></li>';
                    var value = $(this).find('span').attr('value');
                    $('#select_season_id').html(item);
                    $('#select_season_id').attr('value', value);
                    $("#season_id").val(value);
                    $("#sub_div_season").toggle();
                });

                $(document).on("click", function(event){
                    var $away = $("#away_team");
                    var $home = $("#home_team");
                    var $user = $("#select_user_id");
                    var $week = $("#select_week_id");
                    var $season = $("#select_season_id");

                    if($away !== event.target && !$away.has(event.target).length){
                        $("#away").hide();
                    }else{
                        $("#away").show();
                    }

                    if($home !== event.target && !$home.has(event.target).length){
                        $("#home").hide();
                    }else{
                        $("#home").show();
                    }

                    if($user !== event.target && !$user.has(event.target).length){
                        $("#sub_div_user").hide();
                    }else{
                        $("#sub_div_user").show();
                    }

                    if($week !== event.target && !$week.has(event.target).length){
                        $("#sub_div_week").hide();
                    }else{
                        $("#sub_div_week").show();
                    }

                    if($season !== event.target && !$season.has(event.target).length){
                        $("#sub_div_season").hide();
                    }else{
                        $("#sub_div_season").show();
                    }
                });
                //*************end here*************
                if(getCookie('home_team_id') != ''){
                    $('#home_team').html(langArray[getCookie('home_team_id')-1]);
                    $('#away_team').html(langArray[getCookie('away_team_id')-1]);

                    $("#home_team_id").val(getCookie('home_team_id'));
                    $("#away_team_id").val(getCookie('away_team_id'));
                    $("#pre_week_number").val(getCookie('pre_week_number'));
                    $("#season_id").val(getCookie('pre_season_id'));
                }

                function getCookie(cname) {
                    let name = cname + "=";
                    let ca = document.cookie.split(';');
                    for(let i = 0; i < ca.length; i++) {
                        let c = ca[i];
                        while (c.charAt(0) == ' ') {
                            c = c.substring(1);
                        }
                        if (c.indexOf(name) == 0) {
                            return c.substring(name.length, c.length);
                        }
                    }
                    return "";
                }
            </script>
@endsection
