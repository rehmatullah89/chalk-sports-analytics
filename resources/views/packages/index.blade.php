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
            <div class="card-header">Packages
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Import Package Prices</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="import-csv-form" method="POST"  action="{{ url('import-prices') }}" accept-charset="utf-8" enctype="multipart/form-data">
                                <div class="modal-body">
                                        @csrf
                                        <div class="btn-group" role="group">
                                            <input type="file" accept=".csv" name="file" placeholder="Choose file">
                                        </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary btn-sm" id="submit">Import CSV</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @can('role-create')
                    <span class="float-right">
                        <a class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><strong>Import CSV</strong></a>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('packages.create') }}"><strong>New Package</strong></a>
                    </span>
                @endcan
            </div>
            <div class="card-body">
                <div class="scroll-on-mobile">
                    <table class="table table-hover" id="pkg_table">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Detail</th>
                            <th width="100">Recurring Payment</th>
                            <th width="100">OneTime Payment</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tablecontents">
                        @foreach ($data as $key => $package)
                            <tr class="row1" data-id="{{ $package->id }}">
                                <td>{{ $package->id }}</td>
                                <td>{{ $package->name }}</td>
                                <td>{{ $package->status }}</td>
                                <td>{{ $package->detail }}</td>
                                <td>
                                    @if(in_array($package->id,$subscriptions))
                                        {!! Form::model($package, ['route' => ['subscriptions.update', $package->subscription_id],'onsubmit'=>'return confirm("Are you sure, You want to cancel Subscription for this Package!");', 'method'=>'PATCH']) !!}
                                                <button type="submit" class="btn btn-primary btn-sm">UnSubscribe</button>
                                        {!! Form::close() !!}
                                    @else
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-warning btn-sm" style="min-width: 102px;" title="Automatic Purchase for next time" href="{{ route('payment', [$package->id, 'subscribe']) }}"><strong>${{number_format($package->subscription_price/100)}}</strong></a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if(in_array($package->id,$single_payments))
                                        <strong style="color:red; ">Purchased</strong>
                                    @else
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-info btn-sm" style="min-width: 50px;" title="One time Purchase" href="{{ route('payment', [$package->id, 'purchase']) }}"><strong>${{number_format($package->price/100)}}</strong></a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-sm" title="View Details" href="{{ route('packages.show',$package->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                    @can('role-edit')
                                        <a class="btn btn-sm" title="Edit Details" href="{{ route('packages.edit',$package->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    @endcan
                                    @can('role-delete')
                                        {!! Form::open(['method' => 'DELETE', 'onsubmit'=>'return confirm("Please confirm you want to delete this package!");', 'route' => ['packages.destroy', $package->id],'style'=>'display:inline']) !!}
                                        <button type="submit" title='Delete' class="btn btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        {!! Form::close() !!}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
            <link href="{{ asset('css/jquery.dataTables.min.css') }}" type="text/css" rel="stylesheet">
            <script src="{!! asset('js/jquery.min.js') !!}"></script>
            <script src="{!! asset('js/jquery-ui.min.js') !!}"></script>
            <script src="{!! asset('js/jquery.dataTables.min.js') !!}"></script>
            <script>
                $(function ()
                {
                    $("#pkg_table").DataTable({
                        "searching": false,
                        "bSort": false,
                        "paging": false,
                        "info": false,
                    });
                    $( "#tablecontents" ).sortable({
                        items: "tr",
                        cursor: 'move',
                        opacity: 0.6,
                        update: function() {
                            sendOrderToServer();
                        }
                    });
                    function sendOrderToServer() {
                        var order = [];
                        var token = $('meta[name="csrf-token"]').attr('content');
                        $('tr.row1').each(function(index,element) {
                            order.push({
                                id: $(this).attr('data-id'),
                                position: index+1
                            });
                        });
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "{{ url('sort-datatable') }}",
                            data: {
                                order: order,
                                _token: token
                            },
                            success: function(response) {
                                if (response.status == "success") {
                                    console.log(response);
                                } else {
                                    console.log(response)
                                }
                            }
                        });
                    }
                });
            </script>
    </div>
</div>
@endsection
