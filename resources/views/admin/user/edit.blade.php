<form id="formHandler" method="POST" action="{{ route('user.edit') }}">
    @csrf
    <div id="errors-list"></div>

    <div class="row mb-3">
        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

        <div class="col-md-6">
            <input id="name" type="text" class="form-control" name="name" value="" required autocomplete="name" autofocus>
        </div>
    </div>

    <div class="row mb-3">
        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

        <div class="col-md-6">
            <input id="email" type="email" class="form-control" name="email" value="" required autocomplete="email">
        </div>
    </div>
    <div class="row mb-3">
        <label for="active" class="col-md-4 col-form-label text-md-end">{{ __('Active') }}</label>

        <div class="col-md-6">
            <input class="form-check-input" type="checkbox" id="active" value="0">
            <input type='hidden' value='0' name='active' id="activehidden"> 
        </div>
    </div>

    <div class="row mb-0">
        <div class="col-md-6 offset-md-4">
            <input type="submit" class="btn btn-primary" value="Edit">
        </div>
    </div>
</form>