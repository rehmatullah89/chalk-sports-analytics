@extends('layouts.app')
@section('content')
<style>
    .hiddenRow {
        display: none;
    }
    .read-only{
        pointer-events: none;
    }
    [type=radio] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    [type=radio] + img, [type=radio] + span {
        cursor: pointer;
        margin-top: -8px;
    }
    .outline-txt-info span span{
        outline: none;
        border: 2px solid transparent;
        border-radius: 8px;
        margin: 0 0 0;
        padding: 10px 15px;
        width: 100%;
        min-height: 40px;
        max-height: 40px;
    }
    [type=radio] + img{
        margin: 0 0 0;
        width: 100%;
    }
    [type=radio]:checked + img{
        outline: 2px solid #a09a9a;
        background: #6C7277;
        border-radius: 8px;
        margin: 0 0 0;
        width: 100%;
    }
    [type=radio]:checked + span  {
        background: #6C7277;
        outline: none;
        border: 2px solid #a09a9a;
        border-radius: 8px;
        margin: 0 0 0;
        padding: 10px 15px;
        width: 100%;
        min-height: 40px;
        max-height: 40px;
    }
</style>
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
            <div class="card-header">Your NFL Picks
                    <span class="float-right">
                        @hasrole('admin')
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('dashboard') }}"><strong>Dashboard</strong></a>
                        @else
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('home') }}"><strong>Home</strong></a>
                        @endhasrole
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('prediction.index') }}"><strong>NFL Picks Leaderboard</strong></a>
                    </span>
            </div>

            <div class="card-body">
                {!! Form::model($teams, ['route' => ['prediction.update', auth()->user()->id], 'id'=>'prediction-form', 'method'=>'PATCH']) !!}

                <div class="d-flex col-xs-12">
                    <div class="d-inline-block" style="width: 100px; float:left;">
                        <div class="form-group" style="text-align: center;">
                            <label for="pwd">Season</label>
                            {!! Form::select('season_id', $seasons, $season_id, array('class' => 'form-select drop-down', 'id'=>'season_id', 'onchange'=>'getData();')) !!}
                        </div>
                    </div>&nbsp;
                    <div class="d-inline-block"  style="width: 100px; float:left;">
                        <div class="form-group" style="text-align: center;">
                            <label for="week_no">Week</label>
                            {!! Form::select('week_no', $weeks, $week_no, array('class' => 'form-select drop-down','id'=>'week_no', 'onchange'=>'getData();')) !!}
                        </div>
                    </div>
                </div>

                <div class="card-body afterLogin">
                <div class="scroll-on-mobile">
                    <table class="table new-style disable-hover" id="dataTable" style="border-collapse:collapse;">

                        <thead class="thead-dark">
                            <tr>
                                <th>NFL</th>
                                <th style="text-align: center;">Spread</th>
                                <th style="text-align: center;">Total</th>
                                <th style="text-align: center;">Winner</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                </div>
                {{-- <div style="margin-top: 5px;"><button type="submit" class="btn btn-outline-primary btn-sm">Submit</button></div> --}}
                {!! Form::close() !!}

                <script src="{!! asset('js/jquery.min.js') !!}"></script>
                <script>

                    function saveNFLPicks(seasonId, weekNumber, gameId, fieldName, fieldValue)
                    {
                        //console.log("Season:"+seasonId+"Week:"+weekNumber+"Game:"+gameId+"Field:"+fieldName+"Value:"+fieldValue);
                        var url = "{{URL('prediction/'.auth()->user()->id)}}";
                        var id=
                            $.ajax({
                                url: url,
                                type: "PATCH",
                                cache: false,
                                data:{
                                    _token:'{{ csrf_token() }}',
                                    season_id: seasonId,
                                    week_number: weekNumber,
                                    game_id: gameId,
                                    field_name: fieldName,
                                    field_value: fieldValue
                                },
                                success: function(dataResult){
                                    console.log('Success');
                                    {{-- window.location = "{{url('prediction/'.auth()->user()->id.'/edit')}}"; --}}
                                }
                            });
                    }

                    function json2array(json){
                        var result = [];
                        var keys = Object.keys(json);
                        keys.forEach(function(key){
                            result.push(json[key]);
                        });
                        return result;
                    }

                    function getData(){
                        $("#dataTable tbody").html("");
                        var weekNo = $('#week_no').val();
                        var seasonId = $('#season_id').val();

                        if(weekNo == null || seasonId == null){
                            weekNo = 9;
                            seasonId = 1;
                        }

                        $.ajax({
                            type:"GET",
                            url:"{{url('prediction')}}/"+weekNo+"_"+seasonId,
                            success: function(data) {
                                var html = ``;
                                console.log(data);

                                if(typeof data === 'object'){
                                    data = json2array(data);
                                }

                                data.forEach(function callback(value, index) {

                                    var spreadSymbol1 = '';
                                    var spreadSymbol2 = '';
                                    var symbolTeam1 = '';
                                    var symbolTeam2 = '';

                                    if(value.team_1_money_line > 0 || value.team_2_money_line > 0){
                                        symbolTeam1 = '+';
                                        symbolTeam2 = '-';
                                        spreadSymbol1 = (value.team_1_money_line>value.team_2_money_line?'+':'-');
                                        spreadSymbol2 = (value.team_1_money_line<value.team_2_money_line?'+':'-');
                                    }

                                    var radioTeam1Logo = `<label><input name="list[`+index+`][winner]" `+value.disable+` onchange="saveNFLPicks(`+value.season_id+`,`+value.week_number+`,`+value.game_id+`,'`+'winner'+`','`+value.team_2_name+`')" value="`+value.team_2_name+`" `+(value.winner_name == value.team_2_name ? 'checked' : '')+` class="input-style" type="radio"><span><img  class="img-size" style="margin-top: -10px;" src="{{ asset('images/logos') }}/` + value.team_2_logo + `"></span></label>`;
                                    var radioTeam2Logo = `<label><input name="list[`+index+`][winner]" `+value.disable+` onchange="saveNFLPicks(`+value.season_id+`,`+value.week_number+`,`+value.game_id+`,'`+'winner'+`','`+value.team_1_name+`')" class="input-style" value="`+value.team_1_name+`" `+(value.winner_name == value.team_1_name ? 'checked' : '')+` type="radio"><span><img  class="img-size" style="margin-top: -10px;" src="{{ asset('images/logos') }}/` + value.team_1_logo + `"></span></label>`;

                                    var radioTeam1Spread = `<label><input name="list[`+index+`][spread]" `+value.disable+` onchange="saveNFLPicks(`+value.season_id+`,`+value.week_number+`,`+value.game_id+`,'`+'spread'+`','`+value.team_2_id+`')" value="`+value.team_2_id+`" `+(value.team_2_id == value.user_spread ? 'checked' : '')+` class="input-style" type="radio"><span>`+(value.spread == 0?'Even': spreadSymbol2+value.spread)+`</span></label>`;
                                    var radioTeam2Spread = `<label><input name="list[`+index+`][spread]" `+value.disable+` onchange="saveNFLPicks(`+value.season_id+`,`+value.week_number+`,`+value.game_id+`,'`+'spread'+`','`+value.team_1_id+`')" value="`+value.team_1_id+`" `+(value.team_1_id == value.user_spread ? 'checked' : '')+` class="input-style" type="radio"><span>`+(value.spread == 0?'Even': spreadSymbol1+value.spread)+`</span></label>`;

                                    var radioOverUnder1 = `<label><input name="list[`+index+`][over_under]" `+value.disable+` onchange="saveNFLPicks(`+value.season_id+`,`+value.week_number+`,`+value.game_id+`,'`+'over_under'+`','`+value.team_2_id+`')" value="`+value.team_2_id+`" `+(value.team_2_id == value.user_overunder ? 'checked' : '')+` class="input-style" type="radio"><span>O&nbsp;&nbsp;`+symbolTeam1+value.over_under+`</span></label>`;
                                    var radioOverUnder2 = `<label><input name="list[`+index+`][over_under]" `+value.disable+` onchange="saveNFLPicks(`+value.season_id+`,`+value.week_number+`,`+value.game_id+`,'`+'over_under'+`','`+value.team_1_id+`')" value="`+value.team_1_id+`" `+(value.team_1_id == value.user_overunder ? 'checked' : '')+` class="input-style" type="radio"><span>U&nbsp;&nbsp;`+symbolTeam2+value.over_under+`</span></label>`;

                                    html += `<tr><td class="nfl-picks"><input type="hidden" name="list[`+index+`][game_id]" maxlength="4" size="4" value="`+value.game_id+`"><div class="d-flex align-items-start"><div class="match-info add-style"><div class="team-logo add-margin"><img  class="img-size" src="{{ asset('images/logos') }}/` + value.team_2_logo + `"></div><div class="team-name"><h3 class="hide-on-mobile">`+value.team_2_name+`</h3><h3 class="show-on-mobile">`+value.team_2_name_short+`</h3></div></div><div class="match-info right"><div class="team-logo"><img  class="img-size" src="{{ asset('images/logos') }}/` + value.team_1_logo + `"></div><div class="team-name"><h3 class="hide-on-mobile">`+value.team_1_name+`</h3><h3 class="show-on-mobile">`+value.team_1_name_short+`</h3><div class="date hide-on-mobile">`+value.game_date+`</div><div class="date show-on-mobile">`+value.game_date_short+`</div></div></div></div></td>
                                        <td><div class="d-flex"><div class="outline-txt-info"><span>`+radioTeam1Spread+`</span></div><div class="outline-txt-info"><span>`+radioTeam2Spread+`</span></div></div></td>
                                        <td><div class="d-flex"><div class="outline-txt-info"><span>`+radioOverUnder1+`</span></div><div class="outline-txt-info"><span>`+radioOverUnder2+`</span></div></div></td>
                                        <td><div class="d-flex"><div class="outline-txt-info"><span>`+radioTeam1Logo+`</span></div><div class="outline-txt-info"><span>`+radioTeam2Logo+`</span></div></div></td></tr>`;
                                    });
                                $("#dataTable tbody").append(html);
                            }
                        });
                    }
                    getData();
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
