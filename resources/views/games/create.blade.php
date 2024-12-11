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
            <div class="card-header">Update Weight Factors
                @if(auth()->user()->hasRole('admin'))
                    <span class="float-right">
                        @hasrole('admin')
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('dashboard') }}"><strong>Dashboard</strong></a>
                        @else
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('home') }}"><strong>Home</strong></a>
                        @endhasrole
                    </span>
                @endif
            </div>

            <div class="card-body">
                {!! Form::open(array('route' => 'games.store','onsubmit'=>"return confirm('Are you sure you want to save changes?');", 'method'=>'POST')) !!}

                <div class="card-body">
                    <table class="table table-hover" id="dataTable">
                        <thead class="thead-dark">
                        <tr>
                            <th>Key</th>
                            <th>Influence Factor</th>
                            <th>Weight</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $object)
                                <tr>
                                    <td>{{$object->influence_factor_id}}<input type="hidden" name="list[{{$key}}][influence_factor_id]" value="{{$object->influence_factor_id}}"></td>
                                    <td>{{ucwords(str_replace('_',' ',$object->influence_factor_name))}}</td>
                                    <td><input type="text" style="border-radius: 10px; text-align: center;" name="list[{{$key}}][influence_factor_weight]" maxlength="4" size="4" value="{{$object->influence_factor_weight}}"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 5px;"><button type="submit" class="btn btn-outline-primary btn-sm">Submit</button></div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
