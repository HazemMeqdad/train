@extends("layouts.app")
@section("css")
<link rel="stylesheet" href="/chat.css">
@endsection
@section("content")
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md chat">

    @foreach ($users as $usr) 
        @include("chat.user", ["user" => $usr])
    @endforeach

    </div>
      <div class="col-md-8">
        <div class="chat">

          <!-- Header -->
          <div class="top">
            <div>
              <p class="fs-1">{{ $user->name }} Student</p>
            </div>
          </div>
          <!-- End Header -->

          <!-- Chat -->
          <div class="messages">
            @foreach($messages as $message)
              @if ($message->user->id == Auth::user()->id)
                @include('chat/broadcast', ["user" => Auth::user(), 'message' => $message->message])
              @else
                @include('chat/receive', ["user" => $message->user, 'message' => $message->message])
              @endif
            @endforeach
          </div>
          <!-- End Chat -->
            <!-- Footer -->
            <div class="bottom">
              <form>
                <div class="input-group mb-3">
                  <input type="text" id="message" name="message" class="form-control" placeholder="Enter message..." aria-label="Enter message..." aria-describedby="button-addon2">
                  <input type="hidden" name="chat_id" id="chat_id" value="{{ $user->id }}">
                  <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Send</button>
                </div>
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
  
  const channel = pusher.subscribe('admin.{{ $user->id }}');

  //Receive messages
  channel.bind('chat', function (data) {
    console.log(data);
    $.post("{{ route('chat.admin.receive') }}", {
      _token:  '{{csrf_token()}}',
      message: data.message,
      // chat_id: data.message,
    })
     .done(function (res) {
        console.log(res);
       $(".messages > .message").last().after(res);
       $(document).scrollTop($(document).height());
     });
  });

  //Broadcast messages
  $("form").submit(function (event) {
    event.preventDefault();

    $.ajax({
      url:     "{{ route('chat.admin.broadcast') }}",
      method:  'POST',
      headers: {
        'X-Socket-Id': pusher.connection.socket_id
      },
      data:    {
        _token:  '{{csrf_token()}}',
        message: $("form #message").val(),
        chat_id: $("form #chat_id").val(),
      }
    }).done(function (res) {
      $(".messages > .message").last().after(res);
      $("form #message").val('');
      $(document).scrollTop($(document).height());
    });
  });

</script>
@endsection