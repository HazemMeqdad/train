@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        {{ _('This account is inactive, please wait for the administrator to activate your account') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection