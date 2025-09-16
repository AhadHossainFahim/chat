<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Models.Chat.{id}', function (User $user, int $id) {
    $chat = Chat::find($id);

    if (!$chat) {
        return false;
    }

    // Check if user is part of this chat
    return $chat->developer_id === $user->id || ($chat->ticket && $chat->ticket->client_id === $user->id);
});
