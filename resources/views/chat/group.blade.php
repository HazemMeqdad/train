<div class="card m-2">
    <div class="card-body p-2">
        {{-- {{$chat}} --}}
        <a href="{{ route("chat", ["chat_id"=>$chat->id ]) }}"><button class="btn">{{ $chat->name }}</button></a>
    </div>
</div>