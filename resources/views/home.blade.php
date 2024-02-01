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
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Subject name</th>
                            <th scope="col">mark</th>
                            <th scope="col">Mark of success</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(Auth::user()->subjects as $subject)
                            <tr>
                                <th scope="row">{{ $subject->id }}</th>
                                <td>{{ $subject->subject->name }}</td>
                                <td>
                                    @if ($subject->mark == 0)
                                    empty
                                    @else
                                    {{$subject->mark}}
                                    @endif
                                </td>
                                <td>{{ $subject->subject->min_mark }}</td>

                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
