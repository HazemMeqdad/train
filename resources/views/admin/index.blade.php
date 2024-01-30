@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">username</th>
                            <th scope="col">e-mail</th>
                            {{-- <th scope="col">Subjects</th> --}}
                            <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <th scope="row">{{ $user->id }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>math, english</td>
                                <td>
                                    <button id="edit" type="button" class="btn btn-secondary btn-sm" onclick="edit_window({{ $user->id }})">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                </div>
            </div>
            <br>
            <div class="card bg-light">
                <div class="card-body">
                    <button type="button" class="btn btn-outline-secondary">Create User</button>
                    <button type="button" class="btn btn-outline-secondary">Create Subject</button>
                    <button type="button" class="btn btn-outline-secondary">Assign the subject</button>
                    <button type="button" class="btn btn-outline-secondary">Set Mark</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src='https://code.jquery.com/jquery-1.8.2.js'></script>
<script>
function edit_window(id){
    window.open(`/admin/user/${id}`,'mywindow','width=800,height=400')
};
</script>

@endsection