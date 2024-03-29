<?php

namespace App\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use BroadcastsEvents;

    protected $fillable = [
        'user_id',
        'message',
        'private',
    ];

    protected function casts(): array
    {
        return [
            'private' => 'boolean',
        ];
    }

    protected function time(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $this->created_at->format('g:i:sa'),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Get the channels that model events should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel|\Illuminate\Database\Eloquent\Model>
     */
    public function broadcastOn(string $event): array
    {
        if ($this->user_id) {
            return [
                new PrivateChannel("App.Models.User.{$this->user_id}"),
            ];
        }

        return [
            new Channel('public.newMessage'),
        ];
    }
}
