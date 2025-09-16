<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ChatController extends Controller
{
    public function index(): RedirectResponse
    {
        $chat = Chat::findOrFail(1);

        return redirect()->route('chats.show', $chat);
    }

    public function show(Chat $chat): View
    {
        // Ensure user has access to this chat
        $user = auth()->user();

        if (!$this->userCanAccessChat($user, $chat)) {
            abort(403, 'You do not have access to this chat.');
        }

        $chat->load('ticket', 'developer', 'ticket.client');

        return view('chats.show', compact('chat'));
    }

    private function userCanAccessChat($user, $chat): bool
    {
        if ($user->isDeveloper()) {
            return $chat->developer_id === $user->id;
        }

        if ($user->isClient() && $chat->ticket) {
            return $chat->ticket->client_id === $user->id;
        }

        return false;
    }
}
