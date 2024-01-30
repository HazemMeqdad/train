@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">User Information</div>

                <div class="card-body">
                    E-mail: {{ Auth::user()->email }}
                    <br>
                    username: {{ Auth::user()->name }}
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Courses & Marks</div>

                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
