@extends("layouts.app")
@section("css")
<link rel="stylesheet" href="/chat.css">
@endsection
@section("content")
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md chat">
      @foreach(Auth::user()->subjects as $sub)
        @include("chat.group", ["subject" => $sub->subject])
      @endforeach
    </div>
      <div class="col-md-8">
        <div class="chat">

          <!-- Header -->
          <div class="top">
            @if (!empty(Auth::user()->gravatar))
            <img src="{{ Auth::user()->gravatar }}" alt="Avatar">
            @endif
            <div>
              <p>{{ $subject->name }}</p>
              <small>Online</small>
            </div>
          </div>
          <!-- End Header -->

          <!-- Chat -->
          <div class="messages">
            @include('chat/receive', ["user" => Auth::user(), 'message' => "Hey! What's up!  👋"])
            @include('chat/receive', ["user" => Auth::user(), 'message' => "Ask a friend to open this link and you can chat with them!"])
          </div>
          <!-- End Chat -->
            <!-- Footer -->
            <div class="bottom">
              <form>
                <div class="input-group mb-3">
                  <input type="text" id="message" name="message" class="form-control" placeholder="Enter message..." aria-label="Enter message..." aria-describedby="button-addon2">
                  <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Send</button>
                </div>
                {{-- <input type="text" id="message" name="message" placeholder="Enter message..." autocomplete="off">
                <button type="submit"></button> --}}
              </form>
            </div>
            <!-- End Footer -->
        </div>
    </div>
  </div>
</div>

@endsection

@section("js")
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
  const pusher  = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster: '{{config('broadcasting.connections.pusher.options.cluster')}}'});
  const channel = pusher.subscribe('public');

  //Receive messages
  channel.bind('chat', function (data) {
    $.post("{{ route('chat.receive') }}", {
      _token:  '{{csrf_token()}}',
      message: data.message,
    })
     .done(function (res) {
       $(".messages > .message").last().after(res);
       $(document).scrollTop($(document).height());
     });
  });

  //Broadcast messages
  $("form").submit(function (event) {
    event.preventDefault();

    $.ajax({
      url:     "{{ route('chat.broadcast') }}",
      method:  'POST',
      headers: {
        'X-Socket-Id': pusher.connection.socket_id
      },
      data:    {
        _token:  '{{csrf_token()}}',
        message: $("form #message").val(),
      }
    }).done(function (res) {
      $(".messages > .message").last().after(res);
      $("form #message").val('');
      $(document).scrollTop($(document).height());
    });
  });

</script>
@endsection