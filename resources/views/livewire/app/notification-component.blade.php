<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <button wire:click="sendNotification" class="btn btn-primary">
        Send Notification
    </button>
</div>
