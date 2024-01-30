@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card text-center">
        <div class="card-header">
        User Information
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ $user->name }}</h5>
            <p class="card-text">{{ $user->email }}</p>
            @if ($user->active)
            <form action="{{ route('user.edit', [$user->id]) }}" id="myform" method="POST">
                @csrf
                <input type="hidden" value="0" name="active">
                <input type="submit" value="Inactive" class="btn btn-danger">
            </form>
            @else
            <form action="{{ route('user.edit', [$user->id]) }}" id="myform" method="POST">
                @csrf
                <input type="hidden" value="1" name="active">
                <input type="submit" value="Active" class="btn btn-success">
            </form>
            @endif
        </div>
    </div>
</div>
@endsection