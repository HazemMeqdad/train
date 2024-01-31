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
                                    <button type="button" class="btn btn-secondary btn-sm edit-user" data-user-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-active="{{ $user->active }}">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm delete-user" data-user-id="{{ $user->id }}">Delete</button>
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
                    <button type="button" class="btn btn-outline-secondary" id="create-user">Create User</button>
                    <button type="button" class="btn btn-outline-secondary" id="create-subject">Create Subject</button>
                    <button type="button" class="btn btn-outline-secondary">Assign the subject</button>
                    <button type="button" class="btn btn-outline-secondary">Set Mark</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="delete_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete user</h5>
              <button type="button" class="btn-close close-delete">
          </button>
        </div>
        <div class="modal-body">
          <p>Please confirm this order.</p>
        </div>
        <div class="modal-footer">
            <form action="{{ route("user.delete") }}" method="POST" id="formHandler">
                @csrf
                {{ method_field('DELETE') }}
                <input type="submit" value="Delete" class="btn btn-danger">
            </form>
          <button type="button" class="btn btn-secondary close-delete">Close</button>
        </div>
      </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="register_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Create user</h5>
          <button type="button" class="btn-close close-register">
        </button>
        </div>
        <div class="modal-body">
          @include("admin/user/create")
        </div>
      </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="edit_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit user</h5>
          <button type="button" class="btn-close close-edit">
        </button>
        </div>
        <div class="modal-body">
          @include("admin/user/edit")
        </div>
      </div>
    </div>
</div>

{{-- Subject modal --}}
<div class="modal" tabindex="-1" role="dialog" id="subject_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Create subject</h5>
          <button type="button" class="btn-close close-subject">
        </button>
        </div>
        <div class="modal-body">
          @include("admin/subject/create")
        </div>
      </div>
    </div>
</div>
@endsection
@section("js")
<script>
// Create user
$('.btn-close').on('click', function () {
    // Remove all modals
    $(".modal").remove();
    // Remove modal backdrop
    $(".modal-backdrop").remove();})
$('#create-user').on("click", () => {
    $('#register_modal').modal('toggle');
});

// Delete user
$('.delete-user').on("click", (event) => {
    $("#delete_modal").modal("toggle");
    const userid = $(event.target).attr("data-user-id");
    $("#delete_modal").find('#formHandler').attr('action', "{{ route("user.delete") }}" + `/${userid}`);
});

$(".close-delete").on('click', function () {
    $('#delete_modal').modal('toggle');
})

// Edit user
$(".close-edit").on('click', function () {
    $('#edit_modal').modal('toggle');
})
$('.edit-user').on("click", (event) => {
    $("#edit_modal").modal("toggle");
    const userid = $(event.target).attr("data-user-id");
    const email = $(event.target).attr("data-email");
    const active = $(event.target).attr("data-active");
    const name = $(event.target).attr("data-name");
    $("#edit_modal").find('#formHandler').attr('action', "{{ route("user.edit") }}" + `/${userid}`);
    $("#edit_modal").find("#email").val(email);
    $("#edit_modal").find("#active").val(active);
    $("#edit_modal").find("#name").val(name);
});

// Create subject 
// $(".close-subject").on('click', function () {
//     $('#create-subject').modal('toggle');
// })
$('#create-subject').on("click", (event) => {
    $("#subject_modal").modal("toggle");
});
</script>
@endsection