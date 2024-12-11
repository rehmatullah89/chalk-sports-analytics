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
            <div class="card-header">Package
                @can('package-list')
                    <span class="float-right">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('packages.index') }}"><strong>Back</strong></a>
                    </span>
                @endcan
            </div>
            <div class="card-body">
                <div class="lead">
                    <strong>Name:</strong>
                    {{ $package->name }}
                </div>
            </div>
            <div class="card-body">
                <div class="lead">
                    <strong>One Time Price:</strong>
                    ${{ number_format($package->price/100, 2) }}
                </div>
            </div>
            <div class="card-body">
                <div class="lead">
                    <strong>Subscription Price:</strong>
                    ${{ number_format($package->subscription_price/100, 2) }}
                </div>
            </div>
            <div class="card-body">
                <div class="lead">
                    <strong>Status:</strong>
                    {{ $package->status }}
                </div>
            </div>
            <div class="card-body">
                <div class="lead">
                    <strong>Detail:</strong>
                    {{ $package->detail }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
