{{-- <div class="right message">
  <div class="card-title p-2">
    <div class="row">
      <div class="col d-flex align-items-center justify-content-end">
        <span>
          <h6 class="font-weight-bold mb-0">
            {{ $user->name }}
          </h6>
        </span>
        <div class="col-auto">
          <img class="rounded-circle" src="{{ $user->gravatar }}" alt="Avatar" height="40" width="40">
        </div>
      </div>
    </div>
    <p class="mt-1 mb-0">
      {{ $message }}
    </p>
  </div>
</div> --}}

<div class="msg right-msg">
  <div
   class="msg-img"
   style="background-image: url({{ $user->gravatar }})"
  ></div>

  <div class="msg-bubble">
    <div class="msg-info">
      <div class="msg-info-name">{{ $user->name }}</div>
      {{-- <div class="msg-info-time">{{ $message->created_at->format('h:i A') }}</div> --}}
    </div>

    <div class="msg-text">
      {{ $message }}
    </div>
  </div>
</div>