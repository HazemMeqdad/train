<form id="formHandler" method="POST" action="{{ route('subject.assign') }}">
    @csrf
    <div id="errors-list"></div>

    <div class="row mb-3">
        <div class="col-md-6 offset-md-4">
            <select id="select-menu" class="form-select form-control" name="student_id" aria-label="Default select example">
                <option selected>Select student</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <input type="hidden" value="" name="id" id="user-id">
    <div class="row mb-0">
        <div class="col-md-6 offset-md-4">
            <input type="submit" class="btn btn-primary" value="Assign">
        </div>
    </div>
</form>