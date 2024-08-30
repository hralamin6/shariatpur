<?php

namespace App\Livewire\App;

use App\Events\MessageRead;
use App\Events\MessageSendEvent;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;
//use function Symfony\Component\Translation\t;

class ChatComponent extends Component
{
    public $selectedConversation;
    public $receiver;
    public $messages;
    public $paginateVar = 10;
    public $height;
    public $body;
    public $createdMessage;
    use LivewireAlert;


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
            $this->messages = $this->data;
            $this->scrollBottom(true);
            Message::where('conversation_id',$this->selectedConversation->id)
                ->where('receiver_id',auth()->user()->id)->update(['read'=> 1]);
            broadcast(new MessageRead($this->selectedConversation->id, $this->getChatUserInstance($this->selectedConversation, $name = 'id')))->toOthers();
            $this->dispatch('browserMessage', ['messageBody'=>$sentEvent['message'],'userName' => User::find($sentEvent['sender_id'])->name, 'link'=> route('app.dashboard')]);
            $this->alert('success', __('New message from '.User::find($sentEvent['sender_id'])->name).' '.$sentEvent['message']);


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

        $this->messages = Message::where('conversation_id',  $this->selectedConversation->id)
            ->skip($messages_count -  $this->paginateVar)
            ->take($this->paginateVar)->get();

        $msd_id = $this->messages->slice(-$this->paginateVar+10, 1)->first()->id;
        $this->dispatch('updatedHeight', ($msd_id));
        # code...
    }
    public function loadConversation(Conversation $conversation, User $receiver)
    {
        $this->selectedConversation =  $conversation;
        $this->receiver =  $receiver->id;
        $this->messages = $this->data;
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
            $this->messages = $this->data;

        }

    }
    public function scrollBottom($isTrue)
    {
        if ($this->selectedConversation && $this->messages->first()){
            $msg_id = $this->messages->last()->id;
            $this->dispatch('scrollBottom', message_id: "$msg_id", new_message: $isTrue);
//            $this->dispatch('scrollBottom', dataId: "item-id-$var");

        }
    }
    public function sendMessage()
    {
        if ($this->body == null) {
            $this->alert('error', __('Message can not be empty'));
        }else{
            $this->createdMessage = Message::create([
                'conversation_id' => $this->selectedConversation->id,
                'sender_id' => auth()->id(),
                'receiver_id' => $this->receiver,
                'body' => $this->body,
            ]);
            $body = $this->body;
            $this->selectedConversation->last_time_message = $this->createdMessage->created_at;
            $this->selectedConversation->save();
            $this->messages = $this->data;
            $this->scrollBottom(true);
            $this->reset('body');

            broadcast(new MessageSent(auth()->id(), $this->selectedConversation->id, $this->receiver, $body))->toOthers();
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
    public function render()
    {
//        dd($this->data);
        $conversations = $this->conversations;
        return view('livewire.app.chat-component', compact('conversations'));
    }
}
