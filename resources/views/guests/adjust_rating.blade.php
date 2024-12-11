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
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif
            <div class="card-header-web">Adjust Team Ratings</div>

            <div class="card-body">
                        <div class="d-inline-block">
                            <div class="form-group" style="width: 250px; text-align: left;">
                                <label for="home_team_id">Team</label>
                                <select name="home_team_id" id="home_team_id" onchange="getData();" class='form-select drop-down vodiapicker' required>
                                    @foreach($teams as $teamId => $teamName)
                                        <option value="1" data-thumbnail="{{ asset('images/logos/'.@$logos[$teamId]) }}" style="background-image: {{ asset('images/logos/'.@$logos[$teamId]) }};" >
                                            {{$teamName}}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="team-select-away">
                                    <div class="btn-select" id="home_team" style="height: 34px !important;" value=""></div>
                                    <div class="b" id="home">
                                        <ul id="a" class="ho"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
             </div>

                <div class="card-body">
                    <table class="table table-hover" id="dataTable">
                        <thead class="thead-dark">
                        <tr>
                            <th>Factor</th>
                            <th>Ratings</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $obj)
                            <tr>
                                <td>{{$obj['influence_factor_name']}}</td>
                                <td><input type="number" style="border-radius: 10px; text-align: center; width:50px;" maxlength="3" min="1" max="100" readonly="" size="2" value="{{$obj['influence_factor_value']}}"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                <div class="center" style="margin: 7px;">
                    <button class="btn btn-outline-danger btn-red maring-mb"><a style="text-decoration: none; color: white;" href="/#Package-list">Buy Package to Adjust Ratings</a></button>
                    <a  class="btn btn-outline-danger maring-mb" href="{{route('predictions')}}">Custom NFL Predictive Model</a>
                </div>
                </div>
    <script src="{!! asset('js/jquery.min.js') !!}"></script>
    <script>
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
@endsection
