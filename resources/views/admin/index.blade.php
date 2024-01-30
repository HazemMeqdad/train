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
                            <th scope="col">Subjects</th>
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
                                    <button type="button" class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="delete_user({{ $user->id }})">Delete</button>
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
                    <button type="button" class="btn btn-outline-secondary" onclick="user_create_window()">Create User</button>
                    <button type="button" class="btn btn-outline-secondary">Create Subject</button>
                    <button type="button" class="btn btn-outline-secondary">Assign the subject</button>
                    <button type="button" class="btn btn-outline-secondary">Set Mark</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="delete_user">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete user</h5>
              <button type="button" class="btn-close" href="javascript:void(0);" onclick="close_modal()">
          </button>
        </div>
        <div class="modal-body">
          <p>Please confirm this order.</p>
        </div>
        <div class="modal-footer">
            <form action="" method="POST" id="delete">
                @csrf
                {{ method_field('DELETE') }}
                <input type="submit" value="Delete" class="btn btn-danger">
        </form>
          <button type="button" class="btn btn-secondary" href="javascript:void(0);" onclick="close_modal()">Close</button>
        </div>
      </div>
    </div>
  </div>
<script src='https://code.jquery.com/jquery-1.8.2.js'></script>
<script>
function edit_window(id){
    window.open(`/admin/user/${id}`,'mywindow','width=800,height=400')
};
function user_create_window(id){
    window.open(`/admin/user`,'mywindow','width=800,height=400')
};
function delete_user(id) {
    element = document.getElementById("delete_user");
    element.classList.add("d-block");
    delete_form = document.getElementById("delete")
    delete_form.action = `{{ route('user.delete') }}/${id}`
}
function close_modal(){
    element = document.getElementById("delete_user");
    element.classList.remove("d-block");
}
</script>

@endsection