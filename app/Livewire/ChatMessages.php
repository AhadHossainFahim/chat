<?php

namespace App\Livewire;

use App\Models\Chat;
use Livewire\Component;

class ChatMessages extends Component
{
    public Chat $chat;
    public $messages;

    public function mount(Chat $chat)
    {
        $this->chat = $chat;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = $this->chat->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $this->dispatch('scroll-to-bottom');
    }

    public function getListeners(): array
    {
        return [
            "echo-private:App.Models.Chat.{$this->chat->id},.MessageCreated" => 'refresh',
        ];
    }

    public function refresh()
    {
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.chat-messages');
    }
}
