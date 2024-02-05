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
                                <td>@foreach($user->subjects as $subject)
                                    {{$subject->subject->name}},
                                    @endforeach
                                </td>
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
        </div>
    </div>
</div>

<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Subjects') }}</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">name</th>
                            <th scope="col">Minimum mark</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $subject)
                            <tr>
                                <th scope="row">{{ $subject->id }}</th>
                                <td>{{ $subject->name }}</td>
                                <td>{{ $subject->min_mark }}</td>
                                <td>
                                    <button type="button" class="btn btn-secondary btn-sm assign-subject" data-subject-id="{{ $subject->id }}">Assign the subject</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>

<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-light">
                <div class="card-body">
                    <button type="button" class="btn btn-outline-secondary" id="create-user">Create User</button>
                    <button type="button" class="btn btn-outline-secondary" id="create-subject">Create Subject</button>
                    {{-- <a href="{{ route('subjects') }}">
                        <button type="button" class="btn btn-outline-secondary">Assign subject</button>
                    </a> --}}
                    <button type="button" class="btn btn-outline-secondary" id='set-mark'>Set Mark</button>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Delete modal --}}
<div class="modal" tabindex="-1" role="dialog" id="delete_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete user</h5>
              <button type="button" class="btn-close">
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
          <button type="button" class="btn btn-secondary button-close">Close</button>
        </div>
      </div>
    </div>
</div>

{{-- Register modal --}}
<div class="modal" tabindex="-1" role="dialog" id="register_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Create user</h5>
          <button type="button" class="btn-close">
        </button>
        </div>
        <div class="modal-body">
          @include("admin/user/create")
        </div>
      </div>
    </div>
</div>

{{-- Edit user modal --}}
<div class="modal" tabindex="-1" role="dialog" id="edit_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit user</h5>
          <button type="button" class="btn-close">
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
          <button type="button" class="btn-close">
        </button>
        </div>
        <div class="modal-body">
          @include("admin/subject/create")
        </div>
      </div>
    </div>
</div>


{{-- Assign subject modal --}}
<div class="modal" tabindex="-1" role="dialog" id="assign_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Assign subject</h5>
          <button type="button" class="btn-close">
        </button>
        </div>
        <div class="modal-body">
          @include("admin/subject/assign", ["users" => $users, "subjects" => $subjects])
        </div>
      </div>
    </div>
</div>

{{-- Set mark modal --}}
<div class="modal" tabindex="-1" role="dialog" id="set-mark-modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Set mark</h5>
          <button type="button" class="btn-close">
        </button>
        </div>
        <div class="modal-body">
          @include("admin/user/setmark")
        </div>
      </div>
    </div>
</div>

@endsection
@section("js")
<script>
const close_modal = () => {
    $(".modal").modal("hide")
}
$(".button-close").on("click", close_modal);
$('.btn-close').on('click', close_modal);
    
// Create user
$('#create-user').on("click", () => {
    $('#register_modal').modal('toggle');
});

// Delete user
$('.delete-user').on("click", (event) => {
    $("#delete_modal").modal("toggle");
    const userid = $(event.target).attr("data-user-id");
    $("#delete_modal").find('#formHandler').attr('action', "{{ route("user.delete") }}" + `/${userid}`);
});

// Edit user
$('.edit-user').on("click", (event) => {
  console.log("clicked")
    $("#edit_modal").modal("toggle");
    const userid = $(event.target).attr("data-user-id");
    const email = $(event.target).attr("data-email");
    const active = ($(event.target).attr("data-active") == "1") ? "1" : "0";
    console.log(active)
    const name = $(event.target).attr("data-name");
    $("#edit_modal").find('#formHandler').attr('action', "{{ route("user.edit") }}" + `/${userid}`);
    $("#edit_modal").find("#email").val(email);
    $("#edit_modal").find("#active").val(active);
    $("#edit_modal").find("#name").val(name);
    $("#edit_modal").find("#activehidden").val(this.checked ? 1 : 0);
});
$('#active').on('change', function () {
    $("#activehidden").val(this.checked ? 1 : 0);
});

// Create subject 
$('#create-subject').on("click", (event) => {
    $("#subject_modal").modal("toggle");
});

// Set mark
$("#set-mark").on("click", () => {
    $("#set-mark-modal").modal("toggle");
})
$('#select-mark-student').on('change', function () {
    var selectedStudent = $(this).find(':selected');
    var subjectsData = selectedStudent.data('subjects');

    if (subjectsData) {
        $('#select-subject').empty();
        $('#select-subject').append('<option selected>Select Subject</option>');

        $.each(subjectsData, function (index, subject) {
            $('#select-subject').append('<option value="' + subject.subject.id + '">' + subject.subject.name + '</option>');
        });

    } else {
        // Handle the case where there is no data-subjects attribute
        console.log('No subjects data available');
    }
});

// $('#select-mark-student').on('change', function () {
//     var studentId = $(this).val();
//     const data_subjects = $(event.target).attr("data-subjects");
//     console.log(data_subjects);
//     console.log(event.target);
    // if (studentId) {
    //     $.ajax({
    //         url: '/get-subjects/' + studentId, // Replace with your route to fetch subjects for the selected student
    //         type: 'GET',
    //         dataType: 'json',
    //         success: function (data) {
    //             $('#select-subject').empty();
    //             $('#select-subject').append('<option selected>Select Subject</option>');
    //             $.each(data, function (key, value) {
    //                 $('#select-subject').append('<option value="' + value.id + '">' + value.name + '</option>');
    //             });
    // //         }
    //     });
    // } else {
    //     $('#select-subject').empty();
    //     $('#select-subject').append('<option selected>Select Subject</option>');
    // }
// });

// Assign
$(".assign-subject").on("click", (event) => {
    console.log("Cliked");
    $("#assign_modal").modal("toggle");
    const id = $(event.target).attr("data-subject-id");
    
    $("#user-id").val(id);
})
</script>
@endsection