<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class AiUsageSummaryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly array $summary,
        private readonly array $messages,
        private readonly int $count,
    ) {}

    public function via(string $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(string $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("AI Usage Summary — $this->count notifications")
            ->greeting('AI Usage Summary')
            ->line("You have $this->count unread AI usage notifications:")
            ->line('**Models - Tokens used:**')
            ->lines($this->summary)
            ->line('**Messages:**')
            ->lines($this->messages)
            ->salutation('— Prompt Generator');
    }

    public function toArray(string $notifiable): array
    {
        return [
            'summary' => $this->summary,
            'messages' => $this->messages,
            'count' => $this->count,
        ];
    }
}
