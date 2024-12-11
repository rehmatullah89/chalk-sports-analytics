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
            <div class="card-header">Create Package
                <span class="float-right">
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('packages.index') }}">Packages</a>
                </span>
            </div>
            <div class="card-body">
                {!! Form::open(array('route' => 'packages.store','method'=>'POST')) !!}
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group"style="margin-bottom: 5px;">
                        <strong>One Time Price $(In Cents):</strong>
                        {!! Form::number('price', null, array('placeholder' => 'One Time Price','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group"style="margin-bottom: 5px;">
                        <strong>Subscription Price $(In Cents):</strong>
                        {!! Form::number('subscription_price', null, array('placeholder' => 'Subscription Price','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group"style="margin-bottom: 5px;">
                        <strong>Status:</strong>
                        {!! Form::select('status', ['Active'=>'Active','InActive'=>'In-Active'], '2', array('class' => 'form-select')) !!}
                    </div>
                    <div class="form-group" style="margin-bottom: 5px;">
                        <strong>Detail:</strong>
                        {!! Form::textarea('detail', null, array('placeholder' => 'Body','class' => 'form-control')) !!}
                    </div>
                <div style="margin-top: 5px;"><button type="submit" class="btn btn-outline-primary btn-sm">Submit</button></div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
