<form id="formHandler" method="POST" action="{{ route('subject.assign') }}">
    @csrf
    <div id="errors-list"></div>
    <div class="row mb-3">
        <div class="col-md-6 offset-md-4">
            <select id="select-assign-subject" class="form-select form-control" name="subject_id">
                <option selected>Select subject</option>
                @foreach ($subjects as $sub)
                    <option value="{{ $sub->id }}" data-students="{{$sub->users}}">{{ $sub->name }}</option>
                @endforeach 
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6 offset-md-4">
            <select id="select-user" class="form-select form-control" name="student_id">
                <option selected>Select student</option>
            </select>
        </div>
    </div>
    <div class="row mb-0">
        <div class="col-md-6 offset-md-4">
            <input type="submit" class="btn btn-primary" value="Assign">
        </div>
    </div>
</form>