{{-- <div class="left message">
  <div class="card-title p-2">
    <div class="row">
      <div class="col">
        <div class="d-flex align-items-center">
          <div class="col-auto">
            <img class="rounded-circle" src="{{ $user->gravatar }}" alt="Avatar" height="40" width="40">
          </div>
          <h6 class="font-weight-bold mb-0 mr-2">
            {{ $user->name }}
          </h6>
          <span class="text-muted small">{{ date('h:i A') }}</span>
        </div>
        <p class="mt-2 mb-0"> <!-- Added mb-0 class to remove margin bottom -->
          {{ $message }}
        </p>
      </div>
    </div>
  </div>
</div> --}}


<div class="msg left-msg">
  <div
   class="msg-img"
   style="background-image: url({{ $user->gravatar }})"
  ></div>

  <div class="msg-bubble">
    <div class="msg-info">
      <div class="msg-info-name">{{$user->name}}</div>
      {{-- <div class="msg-info-time">{{ $message->created_at->format('h:i A') }}</div> --}}
    </div>

    <div class="msg-text">
      {{ $message }}
    </div>
  </div>
</div>