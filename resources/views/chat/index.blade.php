@extends("layouts.app")

@section("css")
  <link rel="stylesheet" href="/chat.css">
@endsection

@section("content")
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md chat msger">
            @foreach($chats as $me_chat)
                @if ($me_chat->id != Auth::user()->id)
                    @include("chat.group", ["chat" => $me_chat])
                @endif
            @endforeach
        </div>

        <div class="col-md-8">
            <section class="msger">
                <header class="msger-header">
                    <div class="msger-header-title">
                        <i class="fas fa-comment-alt"></i> {{$chat->name}}
                    </div>
                    <div class="msger-header-options">
                        <span><i class="fas fa-cog"></i></span>
                    </div>
                </header>

                <main>
                  <div class="overflow-auto msger-chat" id="chat-messages" style="height: 300px;">
                    @foreach($messages as $message)
                      @if ($message->senderUser->id == Auth::user()->id)
                        @include('chat.broadcast', ["user" => $message->senderUser, 'message' => $message->content])
                      @else
                        @include('chat.receive', ["user" => $message->senderUser, 'message' => $message->content])
                      @endif
                    @endforeach
                  </div>
                </main>

                <form class="msger-inputarea">
                    <input type="text" class="msger-input" id="message" name="message" placeholder="Enter your message...">
                    <input type="hidden" name="chat_id" id="chat_id" value="{{ $channel }}">
                    <button type="submit" class="msger-send-btn">Send</button>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection



@section("js")
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
  function scrollToBottom() {
    var chatMessages = document.getElementById('chat-messages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  // Call scrollToBottom function when the page loads
  window.onload = function() {
    scrollToBottom();
  };
  const pusher  = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster: '{{config('broadcasting.connections.pusher.options.cluster')}}'});
  
  const channel = pusher.subscribe('channel.{{ $channel }}');

  //Receive messages
  channel.bind('chat', function (data) {
    console.log(data);
    $.post("{{ route('chat.receive') }}", {
      _token:  '{{csrf_token()}}',
      message: data.message,
      chat_id: `{{$channel}}`,
    })
     .done(function (res) {
        console.log(res);
       $(".msger-chat > .msg").last().after(res);
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
        chat_id: $("form #chat_id").val(),
      }
    }).done(function (res) {
      $(".msger-chat > .msg").last().after(res);
      $("form #message").val('');
      $(document).scrollTop($(document).height());
    });
  });

</script>
@endsection