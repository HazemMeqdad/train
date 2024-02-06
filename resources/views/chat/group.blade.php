<div class="card m-2 user-card">
    <a href="{{ route("chat", ["chat_id"=>$chat->channel ]) }}" class="text-decoration-none">

        <div class="card-body d-flex align-items-center justify-content-between p-2">
            <div class="d-flex align-items-center">
                <img class="rounded-circle mr-2 user-img" src="{{ $chat->gravatar }}" alt="Avatar" width="40" height="40">
                <span class="h5 mb-0 text-secondary">{{ $chat->name }}</span>
            </div>
            {{-- <button class="btn btn-primary">Join</button> --}}
        </div>
    </a>
</div>
