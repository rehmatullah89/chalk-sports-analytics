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
        <div class="card">
            <div class="card-header">Create permission
                <span class="float-right">
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('permissions.index') }}">Permissions</a>
                </span>
            </div>
            <div class="card-body">
                {!! Form::open(array('route' => 'permissions.store','method'=>'POST')) !!}
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                <div style="margin-top: 5px;"><button type="submit" class="btn btn-outline-primary btn-sm">Submit</button></div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
