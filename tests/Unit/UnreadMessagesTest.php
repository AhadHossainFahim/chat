<?php

use App\Models\Chat;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('tracks unread messages for both chat participants', function () {
    Carbon::setTestNow('2024-01-01 12:00:00');

    config(['broadcasting.default' => 'log']);

    $developer = User::factory()->create(['role' => 'developer']);
    $client = User::factory()->create(['role' => 'client']);

    $ticket = Ticket::factory()->create(['client_id' => $client->id]);
    $chat = Chat::factory()->create([
        'ticket_id' => $ticket->id,
        'developer_id' => $developer->id,
    ]);

    Message::create([
        'chat_id' => $chat->id,
        'user_id' => $client->id,
        'message' => 'Hello developer',
    ]);

    Message::create([
        'chat_id' => $chat->id,
        'user_id' => $developer->id,
        'message' => 'Hi client',
    ]);

    expect($chat->fresh()->unreadMessagesCountFor($developer))->toBe(1);
    expect($developer->unreadMessagesCount())->toBe(1);

    Carbon::setTestNow('2024-01-01 12:05:00');
    $chat->markAsReadFor($developer);

    expect($chat->fresh()->unreadMessagesCountFor($developer))->toBe(0);
    expect($developer->fresh()->unreadMessagesCount())->toBe(0);

    Carbon::setTestNow('2024-01-01 12:10:00');
    Message::create([
        'chat_id' => $chat->id,
        'user_id' => $developer->id,
        'message' => 'Another update',
    ]);

    expect($chat->fresh()->unreadMessagesCountFor($client))->toBe(2);
    expect($client->unreadMessagesCount())->toBe(2);

    Carbon::setTestNow('2024-01-01 12:15:00');
    $chat->markAsReadFor($client);

    expect($chat->fresh()->unreadMessagesCountFor($client))->toBe(0);
    expect($client->fresh()->unreadMessagesCount())->toBe(0);

    Carbon::setTestNow();
});
