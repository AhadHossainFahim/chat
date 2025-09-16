<?php

namespace App\Livewire;

use App\Models\Chat;
use App\Models\Message;
use Livewire\Component;

class ChatMessageForm extends Component
{
    public Chat $chat;
    public string $message = '';

    protected $rules = [
        'message' => 'required|string|max:1000',
    ];

    public function mount(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function sendMessage()
    {
        $this->validate();

        Message::create([
            'chat_id' => $this->chat->id,
            'user_id' => auth()->id(),
            'message' => $this->message,
        ]);

        $this->reset('message');
        $this->dispatch('messageSent');
    }

    public function render()
    {
        return view('livewire.chat-message-form');
    }
}
