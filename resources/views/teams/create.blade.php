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
            <div class="card-header">Create Team
                <span class="float-right">
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('teams.index') }}">Teams</a>
                </span>
            </div>

            <div class="card-body">
                {!! Form::open(array('route' => 'teams.store', 'method'=>'POST', 'enctype'=>'multipart/form-data')) !!}
                    <div class="form-group" style="margin-bottom: 5px;">
                        <strong>Name:</strong>
                        {!! Form::text('name', null, array('placeholder' => 'Title','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group" style="margin-bottom: 5px;">
                        <strong>Ranking:</strong>
                        {!! Form::number('ranking', null, array('placeholder' => 'Ranking','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group" style="margin-bottom: 5px;">
                        <strong>Color:</strong>
                        {!! Form::text('colors', null, array('placeholder' => 'Color','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group" style="margin-bottom: 5px;">
                        <strong>Logo:</strong>
                        {!! Form::file('media', null, array('placeholder' => 'Color','class' => 'form-control')) !!}
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
