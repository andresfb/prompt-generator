<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AiUsedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $message,
        public readonly string $client,
    ) {}

    public function via(string $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(string $notifiable): array
    {
        return [
            'client' => $this->client,
            'message' => $this->message,
        ];
    }

    public function toArray(string $notifiable): array
    {
        return [
            'client' => $this->client,
            'message' => $this->message,
        ];
    }
}
