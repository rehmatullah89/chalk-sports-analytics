@extends('layouts.app')
@section('content')
    <style>
        .StripeElement {
            box-sizing: border-box;
            height: 40px;
            padding: 10px 12px;
            border: 1px solid transparent;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }
        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }
        .StripeElement--invalid {
            border-color: #fa755a;
        }
        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
    </style>
    <div class="container-fluid">
        <div class="justify-content-center">
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif
            <div class="card">
                <div class="card-header">Payment Details
                    <span class="float-right">
                        @hasrole('admin')
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('packages.index') }}"><strong>Back</strong></a>
                        @endhasrole
                    </span>
                </div>

                <div class="card-body" bis_skin_checked="1">

                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif

                    <form
                        role="form"
                        action="{{ route('save-payment') }}"
                        method="post"
                        class="require-validation"
                        data-cc-on-file="false"
                        data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                        id="payment-form">
                        @csrf
                        <div class="d-flex col-xs-12">
                            <div class="d-inline-block" style="width: 48%; float:left;border-radius: 20px;">

                                <input type="hidden" name="identifier" id="plan" value="{{ $package->identifier }}">
                                <div class="form-group"style="margin-bottom: 5px;">
                                    <label><strong>Name on Card:</strong></label>
                                    {!! Form::text('name', null, array('placeholder' => 'Name', 'id'=>'card-holder-name', 'required'=>true, 'class' => 'form-control card-name required')) !!}
                                </div>

                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label for=""><strong>Card details</strong></label>
                                    <div id="card-element"></div>
                                </div>

                            </div>
                            <div class="d-inline-block" style="width: 4%;"></div>
                            <div class="d-inline-block" style="width: 48%; float:right; background-color: #373C45 !important; padding: 10px;border-radius: 20px;">
                                <div style="color: white;">
                                    <h3 style="border-bottom: 1px inset lightgray;">{{$package->name}}</h3>
                                    <p>{{$package->detail}}</p>
                                    <span style="font-size: 16px;">{{ (request('type') == 'purchase'?'One-Time Payment':'Automatic Recurring Payment') }}</span>
                                    <div style="float: right; color: #0DCAF0;">
                                        <h5>
                                            Price: ${{number_format((request('type') == 'purchase'?$package->price:$package->subscription_price)/100, 2)}}
                                        </h5>
                                    </div>
                                    <div style="font-size: 16px; width: 100%;" class="d-flex col-xs-12">
                                        <div class="d-inline-block" style="width: 40%;">Free Games This Week:</div>
                                        <div class="d-inline-block" style="color: #0DCAF0; text-align: right; width: 60%;">
                                            @foreach($free_games as $ind => $game)
                                                {{getLastWord(@$teams[$game->team_2_id])}}&nbsp;@&nbsp;{{getLastWord(@$teams[$game->team_1_id])}}
                                                @if($ind != count($free_games)-1)
                                                    &nbsp; <i style="color: #5c636a;">&</i> &nbsp;
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($package->id != 1)
                            <div class="form-group">
                                <button type="button" class="collapsible" style="border-radius: 20px;">View Games</button>
                                <div class="collapse-content">

                                    @if($package->id == 8)
                                        <div class="form-group" style="text-align: left; margin-bottom: 10px;">
                                            <label for="week_no">Week</label><br/>
                                            {!! Form::select('week_no', $weeks, $week_no, array('class' => 'form-select drop-down', 'id'=>'week_no', 'onchange'=>'getWeekData();', 'required'=>'true')) !!}
                                            <input type="hidden" name="season_id" value="{{$season_id}}" id="season_id" />
                                        </div>
                                    @endif
                                    <table id="dataTable" class="table table-borderless" style="border: 1px solid white; width: 100%;">
                                        <tbody>
                                        <?php
                                        $indexCounter = 0;
                                        $gameCounter = count($games);?>
                                        @foreach($games as $game)
                                            @if($indexCounter%4 == 0)
                                                <tr>
                                                    @endif
                                                    <td style="background: none;">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" name="games[]" type="checkbox" id="game{{$game->game_id}}" value="{{$game->game_id}}" onchange="selectGames({{$package->id}});">
                                                            <label class="form-check-label" for="inlineCheckbox1">{{getLastWord(@$teams[$game->team_2_id])}}&nbsp;@&nbsp;{{getLastWord(@$teams[$game->team_1_id])}}</label>
                                                        </div>
                                                    </td>
                                                    <?php $indexCounter++ ?>
                                                    @if($indexCounter%4 == 4)
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif


                        <div class='form-row row'>
                            <div class='col-md-12 error form-group hide' style="display: none;">
                                <div class='alert-danger alert'>Please correct the errors and try again.</div>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-xs-12">
                                <button class="btn btn-outline-primary btn-sm" type="submit" id="card-button" data-secret="{{ $intent->client_secret }}">Pay Now (${{ (request('type') == 'purchase')?floatval($package->price/100):floatval($package->subscription_price/100)}})</button>
                                <input type="hidden" name="price" value="{{ (request('type') == 'purchase')?$package->price:$package->subscription_price }}">
                                <input type="hidden" name="pkg_type" value="{{ request('type') }}">
                            </div>
                        </div>
                    </form>

                </div>
                <script src="{!! asset('js/jquery.min.js') !!}"></script>
                <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
                <script>
                    const stripe = Stripe('{{ config('cashier.key') }}')
                    const elements = stripe.elements()
                    let style = {
                        base: {
                            color: '#32325d',
                            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                            fontSmoothing: 'antialiased',
                            fontSize: '16px',
                            '::placeholder': {
                                color: '#aab7c4'
                            }
                        },
                        invalid: {
                            color: '#fa755a',
                            iconColor: '#fa755a'
                        }
                    }
                    const cardElement = elements.create('card', {style: style})
                    $('.has-error').removeClass('has-error');
                    cardElement.mount('#card-element')

                    const form = document.getElementById('payment-form')
                    const cardBtn = document.getElementById('card-button')
                    const cardHolderName = document.getElementById('card-holder-name')

                    form.addEventListener('submit', async (e) => {

                        var packageId = {{$package->id}};
                        if(packageId != 1){
                            var numberOfChecked = $('input:checkbox:checked').length;

                            if(numberOfChecked < 1){
                                alert("Please select at least 1 game to proceed.");
                                e.preventDefault();
                                return false;
                            }
                        }

                        var $form         = $("#payment-form"),
                            inputSelector = ['input[type=email]', 'input[type=password]',
                                'input[type=text]', 'input[type=file]',
                                'textarea'].join(', '),
                            $inputs       = $form.find('.required').find(inputSelector),
                            $errorMessage = $form.find('div.error'),
                            valid         = true;
                        $errorMessage.addClass('hide');

                        $('.has-error').removeClass('has-error');
                        $inputs.each(function(i, el) {
                            var $input = $(el);
                            if ($input.val() === '') {
                                $input.parent().addClass('has-error');
                                $errorMessage.removeClass('hide');
                                e.preventDefault();
                            }
                        });

                        e.preventDefault();
                        cardBtn.disabled = true
                        const { setupIntent, error } = await stripe.confirmCardSetup(
                            cardBtn.dataset.secret, {
                                payment_method: {
                                    card: cardElement,
                                    billing_details: {
                                        name: cardHolderName.value
                                    }
                                }
                            }
                        )

                        if(error) {
                            cardBtn.disable = false
                        } else {
                            let token = document.createElement('input')

                            token.setAttribute('type', 'hidden')
                            token.setAttribute('name', 'token')
                            token.setAttribute('value', setupIntent.payment_method)

                            form.appendChild(token)

                            form.submit();
                        }
                    });

                    function selectGames(packageId)
                    {
                        if(packageId != 8){
                            $("input[type=checkbox]").prop('checked', $(this).prop('checked', true));
                        }else {
                            $('input[type=checkbox]').on('change', function (e) {
                                if ($('input[type=checkbox]:checked').length > 3) {
                                    $(this).prop('checked', false);
                                    alert("Only 3 games are allowed!");
                                }
                            });
                        }

                    }

                    var i;
                    var coll = document.getElementsByClassName("collapsible");
                    for (i = 0; i < coll.length; i++) {
                        coll[i].addEventListener("click", function() {
                            this.classList.toggle("active");
                            var content = this.nextElementSibling;
                            if (content.style.display === "block") {
                                content.style.display = "none";
                            } else {
                                content.style.display = "block";
                            }
                        });
                    }

                    selectGames({{$package->id}});

                    function getWeekData() {
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
                </script>
            </div>
        </div>
    </div>
@endsection
