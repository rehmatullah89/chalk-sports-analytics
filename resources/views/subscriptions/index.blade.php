@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="justify-content-center">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>{{ \Session::get('success') }}</p>
            </div>
        @endif
        <div class="card">
            <div class="card-header">Subscriptions
                <span class="float-right">
                    @hasrole('admin')
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('dashboard') }}"><strong>Dashboard</strong></a>
                    @else
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('home') }}"><strong>Home</strong></a>
                    @endhasrole
                </span>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">Active Subscriptions</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cancel-tab" data-bs-toggle="tab" data-bs-target="#cancel" type="button" role="tab" aria-controls="cancel" aria-selected="true">Cancel Subscriptions</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    {{-- Active Tab --}}
                    <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                        <table class="table" id="all_subscriptions">
                            <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Package</th>
                                <th>User</th>
                                <th>Status</th>
                                <th width="280px">Cancel Subscription</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $key => $subscription)
                                <tr>
                                    <td>{{ $subscription->id }}</td>
                                    <td>{{ @$packages[$subscription->stripe_price] }}</td>
                                    <td>{{ $subscription->user->name }}</td>
                                    <td>{{ $subscription->stripe_status }}</td>
                                    <td>
                                        @if(is_null($subscription->ends_at))
                                            @can('subscription-cancel')
                                                {!! Form::open(['method' => 'DELETE', 'onsubmit'=>'return confirm("Please confirm you want to Cancel this Subscription!");', 'route' => ['subscriptions.destroy', $subscription->stripe_id],'style'=>'display:inline']) !!}
                                                <button type="submit" title='Cancel Subscription' class="btn btn-sm"><i class="fa fa-trash"></i></button>
                                                {!! Form::close() !!}
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Cancel Tab --}}
                    <div class="tab-pane fade show" id="cancel" role="tabpanel" aria-labelledby="cancel-tab">
                        <table class="table" id="cancel_subscriptions">
                            <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Package</th>
                                <th>User</th>
                                <th>Status</th>
                                <th width="280px">Cancel Subscription</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($cancel as $key => $subscription)
                                <tr>
                                    <td>{{ $subscription->id }}</td>
                                    <td>{{ @$packages[$subscription->stripe_price] }}</td>
                                    <td>{{ $subscription->user->name }}</td>
                                    <td>{{ $subscription->stripe_status }}</td>
                                    <td>
                                            @can('subscription-cancel')
                                                {!! Form::open(['method' => 'DELETE', 'onsubmit'=>'return confirm("Please confirm you want to Cancel this Subscription!");', 'route' => ['subscriptions.destroy', $subscription->stripe_id],'style'=>'display:inline']) !!}
                                                <button type="submit" title='Delete' class="btn btn-sm"><i class="fa fa-trash"></i></button>
                                                {!! Form::close() !!}
                                            @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
                        <style>
                            .dataTables_length label{
                                display: none;
                            }
                            .dataTables_wrapper .dataTables_paginate .paginate_button{
                                color: black !important;
                                border: none;
                                background-color: white !important;
                            }
                            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                                color: white !important;
                                border: none;
                                background-color: #585858;
                                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #585858), color-stop(100%, #111));
                                /* Chrome,Safari4+ */
                                background: -webkit-linear-gradient(top, #585858 0%, #111 100%);
                                /* Chrome10+,Safari5.1+ */
                                background: -moz-linear-gradient(top, #585858 0%, #111 100%);
                                /* FF3.6+ */
                                background: -ms-linear-gradient(top, #585858 0%, #111 100%);
                                /* IE10+ */
                                background: -o-linear-gradient(top, #585858 0%, #111 100%);
                                /* Opera 11.10+ */
                                background: linear-gradient(to bottom, #585858 0%, #111 100%);
                                /* W3C */
                            }
                        </style>
                        <script src="{!! asset('js/jquery.min.js') !!}"></script>
                        <script src="{!! asset('js/jquery.dataTables.min.js') !!}"></script>
                        <script>
                            $(document).ready(function(){

                                $('#all_subscriptions').DataTable({
                                    "searching": false,
                                });
                                $('#cancel_subscriptions').DataTable({
                                    "searching": false,
                                });

                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
