@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="justify-content-center">
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
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif
            <div class="card">
            <div class="card-header">Select 3 Game NFL Pass Games
                    <span class="float-right">
                        @hasrole('admin')
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('dashboard') }}"><strong>Dashboard</strong></a>
                        @else
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('home') }}"><strong>Home</strong></a>
                        @endhasrole
                    </span>
            </div>

            <div class="card-body">
                {!! Form::open(array('route' => 'save-nfl3-games', 'id'=>'myForm', 'onsubmit'=>"event.preventDefault(); validateMyForm();", 'method'=>'POST')) !!}
                <div class="d-flex col-xs-12" style="padding-left: 10px;">
                    <div class="d-inline-block"  style="float:left;width: 100px;">
                        <div class="form-group" style="text-align: left;">
                            <label for="season_id">Season</label>
                            {!! Form::select('season_id', $seasons, $season_id, array('class' => 'form-select drop-down','id'=>'season_id', 'onchange'=>'getData();')) !!}
                        </div>
                    </div>&nbsp;
                    <div class="d-inline-block"  style="float:left;">
                        <div class="form-group" style="text-align: left;">
                            <label for="week_no">Week</label><br/>
                            {!! Form::select('week_no', $weeks, $week_no, array('class' => 'form-select drop-down', 'id'=>'week_no', 'onchange'=>'getData();', 'required'=>'true')) !!}
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-hover table-borderless" id="dataTable">
                        <thead class="thead-dark">
                        <tr>
                            <th colspan="4">Games</th>
                        </tr>
                        </thead>
                        <tbody>
                                <?php
                                $indexCounter = 0;
                                $time = strtotime(@$games[0]->game_date);
                                $sunday = date('Y-m-d', strtotime('next sunday', $time));
                                $saturday = date('Y-m-d', strtotime('next saturday', $time));
                                ?>
                                @foreach($games as $game)
                                    @if($game->game_date == $sunday || $game->game_date == $saturday)
                                        @if($indexCounter%4 == 0)
                                            <tr>
                                                @endif
                                                <td style="background: none;">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" name="games[]" type="checkbox" {{(in_array($game->game_id ,$user_games)?'checked':'')}} {{((@$user_games[0] != "")?'disabled':'')}} id="game{{$game->game_id}}" value="{{$game->game_id}}" onchange="selectGames();">
                                                        <label class="form-check-label" for="inlineCheckbox1">{{getLastWord(@$teams[$game->team_2_id])}}&nbsp;@&nbsp;{{getLastWord(@$teams[$game->team_1_id])}}</label>
                                                    </div>
                                                </td>
                                                <?php $indexCounter++ ?>
                                                @if($indexCounter%4 == 4)
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                        </tbody>
                    </table>
                <div style="margin: 7px;">
                        <button type="btn btn-outline-primary btn-sm" class="btn btn-outline-primary btn-sm">Submit</button>
                </div>

                </div>
                {!! Form::close() !!}

                <script src="{!! asset('js/jquery.min.js') !!}"></script>
                <script>
                    function getData(){
                        $("#dataTable tbody").html("");
                        $.ajax({
                            type:'POST',
                            url:'/nfl3-games',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "season_id": $('#season_id').val(),
                                "week_number":$('#week_no').val(),
                            },
                            success:function(data) {
                                $("#dataTable > tbody").html(data);
                            }
                        });
                    }

                    function selectGames(packageId)
                    {
                        $('input[type=checkbox]').on('change', function (e) {
                            if ($('input[type=checkbox]:checked').length > 3) {
                                $(this).prop('checked', false);
                                alert("Only 3 games are allowed!");
                            }
                        });
                    }

                    function validateMyForm() {
                        if ($('input[type=checkbox]:checked').length < 3){
                            alert("Please select at least 3 games!");
                            return false;
                        }
                        document.getElementById("myForm").submit();
                    }
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
