<?php

namespace App\Livewire\App;

use App\Events\MessageRead;
use App\Events\LiveMessageSent;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageSentNotification;
use App\Notifications\UserApproved;
use Illuminate\Support\Facades\Cache;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

//use function Symfony\Component\Translation\t;

class ChatComponent extends Component
{
    public $selectedConversation;
    public $receiver;
    public $currentMessages;
    public $paginateVar = 10;
    public $height;
    public $body;

    public $photo=[];
    public $image_url;
    public $createdMessage;
    use LivewireAlert, WithFileUploads;


    public function resetComponent()
    {

        $this->selectedConversation= null;
        $this->receiverInstance= null;

        # code...
    }
    #[On('broadcastedMessageRead')]
    public function broadcastedMessageRead($e)
    {
        if($this->selectedConversation){

            if((int) $this->selectedConversation->id === (int) $e['conversation_id']){
                Message::where('conversation_id',$this->selectedConversation->id)
                    ->where('receiver_id',auth()->user()->id)->update(['read'=> 1]);
            }
        }
    }
    #[On('broadcastedMessageReceived')]
    function broadcastedMessageReceived($sentEvent)
    {
//        dd($sentEvent);

        if ($this->selectedConversation && ($sentEvent['sender_id']==$this->receiver)) {
            $this->currentMessages = $this->data;
            $this->scrollBottom(true);
            Message::where('conversation_id',$this->selectedConversation->id)
                ->where('receiver_id',auth()->user()->id)->update(['read'=> 1]);
            broadcast(new MessageRead($this->selectedConversation->id, $this->getChatUserInstance($this->selectedConversation, $name = 'id')))->toOthers();
//            $this->alert('success', __('New message from '.User::find($sentEvent['sender_id'])->name).' '.$sentEvent['message']);


        }

    }
    public function getChatUserInstance(Conversation $conversation, $request)
    {
        $this->auth_id = auth()->id();
        if ($conversation->sender_id == $this->auth_id) {
            $this->receiverInstance = User::firstWhere('id', $conversation->receiver_id);
        } elseif ($conversation->receiver_id == $this->auth_id) {
            $this->receiverInstance = User::firstWhere('id', $conversation->sender_id);
        }
        if (isset($request)) {
            return $this->receiverInstance->$request;
        }
    }
    function loadMore()
    {
        $this->paginateVar = $this->paginateVar + 10;
        $messages_count = Message::where('conversation_id', $this->selectedConversation->id)->count();

        $this->currentMessages = Message::where('conversation_id',  $this->selectedConversation->id)
            ->skip($messages_count -  $this->paginateVar)
            ->take($this->paginateVar)->get();

        $msd_id = $this->currentMessages->slice(-$this->paginateVar+10, 1)->first()->id;
        $this->dispatch('updatedHeight', ($msd_id));
        # code...
    }
    public function loadConversation(Conversation $conversation, User $receiver)
    {
        $this->selectedConversation =  $conversation;
        $this->receiver =  $receiver->id;
        $this->currentMessages = $this->data;
        Message::where('conversation_id',$this->selectedConversation->id)
            ->where('receiver_id',auth()->user()->id)->where('read', 0)->update(['read'=> 1]);
        broadcast(new MessageRead($this->selectedConversation->id, $this->receiver));
        $this->scrollBottom(true);
        $this->dispatch('header-reload');

    }
    public function mount()
    {
        if ($this->conversations->count()>0){
            $updatedConversation =  Conversation::where('sender_id', auth()->id())
                ->orWhere('receiver_id', auth()->id())->orderBy('last_time_message', 'DESC')->first();
            $this->receiver =  $this->getChatUserInstance($updatedConversation, $name = 'id');
            $this->loadConversation($updatedConversation, User::find($this->receiver));
            $this->currentMessages = $this->data;

        }

    }
    public function scrollBottom($isTrue)
    {
        if ($this->selectedConversation && $this->currentMessages->first()){
            $msg_id = $this->currentMessages->last()->id;
            $this->dispatch('scrollBottom', message_id: "$msg_id", new_message: $isTrue);
//            $this->dispatch('scrollBottom', dataId: "item-id-$var");

        }
    }
    public function sendMessage()
    {
        $this->authorize('app.chats.create');
        $this->validate([
            'body' => 'required|max:2555',
            'photo.*' => 'nullable|image|max:2048', // 2MB Max
            'image_url' => 'nullable|url', // 2MB Max
        ]);

            $this->createdMessage = Message::create([
                'conversation_id' => $this->selectedConversation->id,
                'sender_id' => auth()->id(),
                'receiver_id' => $this->receiver,
                'body' => $this->body,
            ]);
            if ($this->image_url!=null) {
                $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION);
                $media =  $this->createdMessage->addMediaFromUrl($this->image_url)->usingFileName($this->createdMessage->id. '.' . $extension)->toMediaCollection('chatImages');
                $path = storage_path("app/public/Message/".$media->id.'/'. $media->file_name);
                if (file_exists($path)) {
                    unlink($path);
                }

            }elseif($this->photo!=null){
                foreach ($this->photo as $p) {
                    $media = $this->createdMessage->addMedia($p->getRealPath())->usingFileName($this->createdMessage->id. '.' . $p->extension())->toMediaCollection('chatImages');
                    $path = storage_path("app/public/Message/".$media->id.'/'. $media->file_name);
                    if (file_exists($path)) {
                        unlink($path);
                    }            }

            }
            $body = $this->body;
            $this->selectedConversation->last_time_message = $this->createdMessage->created_at;
            $this->selectedConversation->save();
            $this->currentMessages = $this->data;
            $this->scrollBottom(true);
        $this->dispatch('whisperTypingEnd');
        $this->reset('body', 'image_url', 'photo');
            broadcast(new MessageSent(auth()->id(), $this->selectedConversation->id, $this->receiver, $body))->toOthers();
        $isOnline = Cache::has('user-is-online-' . $this->receiver);

        if (!$isOnline) {
            // User has been inactive for more than 30 seconds, send notification
            $user = User::find($this->receiver);
            $user->notify(new MessageSentNotification($user->name, $body, $user));
        }

    }
    public function getDataProperty()
    {
        $message_count = Message::where('conversation_id',  $this->selectedConversation->id)->count();
        return Message::where('conversation_id',  $this->selectedConversation->id)->skip($message_count-$this->paginateVar)->take($this->paginateVar)->get();
    }
    public function getConversationsProperty()
    {
        return Conversation::where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())->orderBy('last_time_message', 'DESC')->get();
    }

    public function messageDelete(Message $message)
    {
        $this->authorize('app.chats.delete');

        $message->delete();
        $this->currentMessages = $this->data;
        $this->alert('success', __('Message deleted successfully!'));
    }
    public function render()
    {
        $this->authorize('app.chats.index');
        $conversations = $this->conversations;
        return view('livewire.app.chat-component', compact('conversations'));
    }
}
