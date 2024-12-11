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
            <div class="card-header">Adjust Team Ratings
                    <span class="float-right">
                        @hasrole('admin')
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('dashboard') }}"><strong>Dashboard</strong></a>
                        @else
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('home') }}"><strong>Home</strong></a>
                        @endhasrole
                    </span>
            </div>

            <div class="card-body">
                {!! Form::model($teams, ['route' => ['games.update', auth()->user()->id], 'onsubmit'=>"return confirm('Are you sure you want to save changes?');", 'method'=>'PATCH']) !!}
                <?php
                if(auth()->user()->hasRole('admin'))
                    $div_width = 25;
                else
                    $div_width = 33;
                ?>
                @hasrole('admin')
                <div class="d-flex col-xs-12" style="padding-left: 10px;">
                    <div class="d-inline-block"  style="float:left;width: 100px;">
                        <div class="form-group" style="text-align: left;">
                            <label for="season_id">Season</label>
                            {!! Form::select('season_id', $seasons, $season_id, array('class' => 'form-select drop-down','id'=>'season_id', 'onchange'=>'getData();')) !!}
                        </div>
                    </div>
                    <div class="d-inline-block"  style="float:left;">
                        <div class="form-group" style="text-align: left;">
                            <label for="week_no">Week&nbsp;&nbsp;(&nbsp;<input id="all_weeks" type="checkbox" >&nbsp;Select All Weeks)</label><br/>
                            {!! Form::select('week_no[]', $weeks, $week_no, array('class' => 'form-select drop-down multiselect-ui', 'multiple'=>'multiple', 'id'=>'week_no', 'onchange'=>'getData();', 'required'=>'true')) !!}
                        </div>
                    </div>&nbsp;
                    <div class="d-inline-block" style="float:left;">
                        <div class="form-group" style="text-align: left;">
                            <label for="home_team_id">Team</label>
                            <select name="home_team_id" id="home_team_id" onchange="getData();" class='form-select drop-down vodiapicker' required>
                                @foreach($teams as $teamId => $teamName)
                                    <option value="{{$teamId}}" data-thumbnail="{{ asset('images/logos/'.@$logos[$teamId]) }}" style="background-image: {{ asset('images/logos/'.@$logos[$teamId]) }};" >
                                        {{$teamName}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="team-select-away">
                                <div class="btn-select" id="home_team" style="height: 37px;" value=""></div>
                                <div class="b" id="home">
                                    <ul id="a" class="ho"></ul>
                                </div>
                            </div>
                        </div>
                    </div>&nbsp;&nbsp;&nbsp;
                    @else
                        <div class="d-inline-block" style="float:left;">
                            <div class="form-group" style="text-align: left;">
                                <label for="pwd">Team</label>
                                <select name="home_team_id" id="home_team_id" onchange="getData();" class='form-select drop-down vodiapicker' required>
                                    @foreach($teams as $teamId => $teamName)
                                        <option value="{{$teamId}}" data-thumbnail="{{ asset('images/logos/'.@$logos[$teamId]) }}" style="background-image: {{ asset('images/logos/'.@$logos[$teamId]) }};" >
                                            {{$teamName}}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="team-select-away">
                                    <div class="btn-select" id="home_team" style="height: 37px;" value=""></div>
                                    <div class="b" id="home">
                                        <ul id="a" class="ho"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="season_id" id="season_id" value="{{$season_id}}">
                        <input type="hidden" name="week_no[]" id="week_no" value="{{$week_no}}">&nbsp;&nbsp;
                 @endhasrole

                @hasrole('admin')
                    &nbsp;<div class="d-inline-block"  style="float:left;">
                        <div class="form-group">
                            <label for="user_id">Users&nbsp;&nbsp;(&nbsp;<input id="all_users" type="checkbox" >&nbsp;Select All Users)</label><br/>
                            {!! Form::select('user_id[]', $users, 0, array('class' => 'form-select drop-down multiselect-ui', 'style'=>'width:250px;', 'multiple'=>'multiple', 'id'=>'user_id', 'onchange'=>'getData();')) !!}
                        </div>
                    </div>
                @else
                    <input type="hidden" name="user_id[]" id="user_id" value="{{auth()->user()->id}}">
                @endhasrole
                </div>

                <div class="card-body">
                    <table class="table table-hover"  style="font-size: 16px;" id="dataTable">
                        <thead class="thead-dark">
                        <tr>
                            <th>Factor</th>
                            <th>Rating</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                <div style="margin: 7px;">
                    @if(auth()->user()->hasRole('admin') || $purchased >0)
                        <button type="btn btn-outline-primary btn-sm" class="btn btn-outline-primary btn-sm">Submit</button>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('prediction.create') }}">New Prediction</a>
                    @else
                        <a class="btn btn-outline-primary btn-sm" style="text-decoration: none; color: white;" href="/#Package-list">Buy Package to Adjust Ratings</a>
                    @endif
                </div>
                </div>
                {!! Form::close() !!}

                <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
                <script src="{!! asset('js/jquery.min.js') !!}"></script>
                <script src="{!! asset('js/select2.min.js') !!}"></script>

                <script>
                    $(document).ready(function() {
                        $('.multiselect-ui').select2({
                            allowClear: true
                        });

                        $("#all_users").click(function(){
                            if($("#all_users").is(':checked')){
                                $("#user_id > option").prop("selected", "selected");
                                $("#user_id").trigger("change");
                            } else {
                                $("#user_id > option").prop("selected",false);
                                $("#user_id").trigger("change");
                            }
                        });

                        $("#all_weeks").click(function(){
                            if($("#all_weeks").is(':checked')){
                                $("#week_no > option").prop("selected", "selected");
                                $("#week_no").trigger("change");
                            } else {
                                $("#week_no > option").prop("selected",false);
                                $("#week_no").trigger("change");
                            }
                        });
                    });

                    function getData(){
                        $("#dataTable tbody").html("");
                        $.ajax({
                            type:"GET",
                            url:"{{url('influence-factors')}}/"+$('#home_team_id').val()+"/"+$('#week_no').val()+"/"+$('#season_id').val()+"/"+$('#user_id').val(),
                            success: function(data) {
                                var html = ``;
                                data.forEach(function callback(value, index) {

                                    selectHtml = `<select name="list[`+index+`][winner]" style="width: 150px;">`;
                                    var selectList = value.winner_select;
                                    for(var i in selectList){
                                        var selectedVal = '';

                                        if(selectList[i] == value.winner){
                                            selectedVal = 'selected';
                                        }

                                        selectHtml += `<option value="`+selectList[i]+`" `+selectedVal+`>`+selectList[i]+`</option>`;
                                    }
                                    selectHtml += `</select>`;

                                    html += `<tr><td style="font-size: 16px;"><input type="hidden" name="list[`+index+`][influence_factor_id]" maxlength="4" size="4" value="`+value.influence_factor_id+`">`+value.influence_factor_name+`</td>
                                    <td><input type="number" style="border-radius: 10px; text-align: center; width: 50px;" name="list[`+index+`][influence_factor_value]" maxlength="3" size="2" min="0" max="100" id="influence_value_`+value.influence_factor_id+`" onchange="enabelReset(`+value.influence_factor_id+`)" value="`+value.influence_factor_value+`"><input type="hidden" name="unknown[]" id="influence_old_`+value.influence_factor_id+`" value="`+value.influence_factor_value+`">
                                        &nbsp;<i class="fa fa-refresh" title="Reset Value" style="font-size:20px;color:red;cursor:pointer;display:none;" id="reset_`+value.influence_factor_id+`" onclick="resetValue(`+value.influence_factor_id+`)"></i></td></tr>`;
                                });
                                $("#dataTable tbody").append(html);
                            }
                        });
                    }
                    getData();

                    function resetValue(id){
                        $('#influence_value_'+id).val($('#influence_old_'+id).val());
                        $('#reset_'+id).hide();
                    }
                    function enabelReset(id) {
                        $('#reset_'+id).show();
                    }

                    //***********************start here************//
                    var langArray = [];
                    $('.vodiapicker option').each(function(){
                        var img = $(this).attr("data-thumbnail");
                        var text = this.innerText;
                        var value = $(this).val();
                        var item = '<li><img src="'+ img +'" alt="" value="'+value+'"/><span>'+ text +'</span></li>';
                        langArray.push(item);
                    })

                    $('.ho').html(langArray);

                    $('#home_team').html(langArray[0]);
                    $("#home_team_id").val(1);

                    $('.ho li').click(function(){
                        var img = $(this).find('img').attr("src");
                        var value = $(this).find('img').attr('value');
                        var text = this.innerText;
                        var item = '<li><img src="'+ img +'" alt="" /><span>'+ text +'</span></li>';
                        $('#home_team').html(item);
                        $('#home_team').attr('value', value);
                        $("#home_team_id").val(value);
                        $("#home").toggle();
                        getData();
                        //console.log("sunny:"+value);
                    });

                    $(document).on("click", function(event){
                        var $home = $("#home_team");

                        if($home !== event.target && !$home.has(event.target).length){
                            $("#home").hide();
                        }else{
                            $("#home").show();
                        }
                    });
                    //*************end here*************
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
