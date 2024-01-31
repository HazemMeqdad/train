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
                                    <button type="button" class="btn btn-secondary" id="assign-subject" data-subject-id="{{ $subject->id }}">Assign the subject</button>
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
                    <a href="{{ route("admin") }}">
                        <button type="button" class="btn btn-outline-secondary">Admin Page</button>
                    </a>
                    <button type="button" class="btn btn-outline-secondary">Set Mark</button>
                </div>
            </div>
        </div>
    </div>
</div>

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

@endsection
@section("js")
<script>
$('.btn-close').on('click', function () {
    $(".modal").modal("toggle")
})
$("#assign-subject").on("click", (event) => {
    $("#assign_modal").modal("toggle");
    const id = $(event.target).attr("data-subject-id");
    
    $("#user-id").val(id);
})
</script>
@endsection
