<div class="card m-2">
    <div class="card-body p-2">
        <a href="{{ route("chat", ["chat_id"=>$subject->id ]) }}"><button class="btn">{{ $subject->name }}</button></a>
    </div>
</div>