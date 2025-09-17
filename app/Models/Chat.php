<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'developer_id', 'developer_last_read_at', 'client_last_read_at'];

    protected $casts = [
        'developer_last_read_at' => 'datetime',
        'client_last_read_at' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function unreadMessagesCountFor(User $user): int
    {
        if (! $column = $this->columnForUser($user)) {
            return 0;
        }

        $lastReadAt = $this->{$column};

        return $this->messages()
            ->where('user_id', '!=', $user->id)
            ->when($lastReadAt, fn ($query) => $query->where('created_at', '>', $lastReadAt))
            ->count();
    }

    public function markAsReadFor(User $user): void
    {
        if (! $column = $this->columnForUser($user)) {
            return;
        }

        $now = now();

        if ($this->{$column} instanceof Carbon && $this->{$column}->greaterThanOrEqualTo($now)) {
            return;
        }

        static::withoutTimestamps(function () use ($column, $now) {
            $this->forceFill([$column => $now])->save();
        });
    }

    protected function columnForUser(User $user): ?string
    {
        if ($user->isDeveloper() && $user->id === $this->developer_id) {
            return 'developer_last_read_at';
        }

        $this->loadMissing('ticket');

        if ($user->isClient() && $this->ticket && $this->ticket->client_id === $user->id) {
            return 'client_last_read_at';
        }

        return null;
    }
}
