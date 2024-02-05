<form id="formHandler" method="POST" action="{{ route('user.mark') }}">
    @csrf
    <div id="errors-list"></div>

    <div class="row mb-3">
        <label for="select-student" class="col-md-4 col-form-label text-md-end">Student</label>

        <div class="col-md-6">
            <select id="select-mark-student" class="form-select form-control" name="student_id" aria-label="Default select example" required>
                <option selected>Select student</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" data-subjects="{{$user->subjects}}">{{ $user->name }}</option>
                @endforeach
            </select>        </div>
    </div>

    <div class="row mb-3">
        <label for="select-subject" class="col-md-4 col-form-label text-md-end">Subject</label>

        <div class="col-md-6">
            <select id="select-subject" class="form-select form-control" name="subject_id" aria-label="Default select example" required>
                <option selected>Select Subject</option>
                {{-- @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach --}}
            </select>        </div>
    </div>

    <div class="row mb-3">
        <label for="mark" class="col-md-4 col-form-label text-md-end">Mark</label>

        <div class="col-md-6">
            <input id="mark" type="number" class="form-control" name="mark" value="" required>
        </div>
    </div>

    <div class="row mb-0">
        <div class="col-md-6 offset-md-4">
            <input type="submit" class="btn btn-primary" value="Set mark">
        </div>
    </div>
</form>