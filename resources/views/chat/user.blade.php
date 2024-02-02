<div class="card m-2">
    <div class="card-body p-2">
        <a href="{{ route("chat", ["chat_id"=>$user->id ]) }}"><button class="btn">{{ $user->name }}</button></a>
    </div>
</div>