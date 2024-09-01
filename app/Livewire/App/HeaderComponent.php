<?php

namespace App\Livewire\App;

use App\Models\Message;
use App\Models\User;
use App\Notifications\UserApproved;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Events\MessageRead;
use App\Events\MessageSent;
class HeaderComponent extends Component
{
    public function  getListeners()
    {

        $auth_id = auth()->id();
        return [
            "echo-private:chat.{$auth_id},MessageSent" => 'broadcastedMessageReceived',
//            "echo-private:chat.{$auth_id},MessageSent" => '$refresh',
            "echo-private:chat.{$auth_id},MessageRead" => 'broadcastedMessageRead',
            'loadConversation', 'pushMessage', 'loadmore', 'updateHeight','broadcastMessageRead','resetComponent'
        ];
    }

    public function broadcastedMessageReceived($e)
    {
        $this->dispatch('broadcastedMessageReceived', sentEvent: $e);

            $this->dispatch('browserMessage', messageBody: $e['message'], userName: User::find($e['sender_id'])->name, link: route('app.chat'));



    }
    public function broadcastedMessageRead($e)
    {
        $this->dispatch('broadcastedMessageRead', e: $e);
    }
    public $locale;
    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));

    }
    public function updatedLocale()
    {
        session()->put('locale', $this->locale);
        return $this->redirect(url()->previous(), navigate:true);

    }
    #[On('header-reload')]
public function render()
    {
        $unReadMessageCount = Message::where('receiver_id', Auth::id())->where('read', 0)->count();
        return view('livewire.app.header-component', compact('unReadMessageCount'));
    }
}
