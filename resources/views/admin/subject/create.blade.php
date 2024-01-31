<form id="formHandler" method="POST" action="{{ route('subject.create') }}">
    @csrf
    <div id="errors-list"></div>

    <div class="row mb-3">
        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

        <div class="col-md-6">
            <input id="name" type="text" class="form-control" name="name" value="" required autocomplete="name" autofocus>
        </div>
    </div>

    <div class="row mb-3">
        <label for="min-mark" class="col-md-4 col-form-label text-md-end">{{ __('Minimum mark') }}</label>

        <div class="col-md-6">
            <input id="min-mark" type="number" class="form-control" name="min_mark" value="" required>
        </div>
    </div>

    <div class="row mb-0">
        <div class="col-md-6 offset-md-4">
            <input type="submit" class="btn btn-primary" value="Create">
        </div>
    </div>
</form>