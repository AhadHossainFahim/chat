<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }



    public function isDeveloper(): bool
    {
        return $this->role === 'developer';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function chatsAsDeveloper()
    {
        return $this->hasMany(Chat::class, 'developer_id');
    }

    public function chatsAsClient(): HasManyThrough
    {
        return $this->hasManyThrough(Chat::class, Ticket::class, 'client_id', 'ticket_id');
    }

    public function unreadMessagesCount(): int
    {
        $userId = $this->id;

        if ($this->isDeveloper()) {
            return Message::query()
                ->join('chats', 'messages.chat_id', '=', 'chats.id')
                ->where('chats.developer_id', $userId)
                ->where('messages.user_id', '!=', $userId)
                ->where(function ($query) {
                    $query->whereNull('chats.developer_last_read_at')
                        ->orWhereColumn('messages.created_at', '>', 'chats.developer_last_read_at');
                })
                ->count();
        }

        if ($this->isClient()) {
            return Message::query()
                ->join('chats', 'messages.chat_id', '=', 'chats.id')
                ->join('tickets', 'chats.ticket_id', '=', 'tickets.id')
                ->where('tickets.client_id', $userId)
                ->where('messages.user_id', '!=', $userId)
                ->where(function ($query) {
                    $query->whereNull('chats.client_last_read_at')
                        ->orWhereColumn('messages.created_at', '>', 'chats.client_last_read_at');
                })
                ->count();
        }

        return 0;
    }
}
