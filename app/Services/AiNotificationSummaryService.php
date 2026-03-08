<?php

declare(strict_types=1);

namespace App\Services;

use App\Libraries\PushoverLibrary;
use App\Models\User;
use App\Notifications\AiUsageSummaryNotification;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Config;

final class AiNotificationSummaryService
{
    public function execute(): void
    {
        $user = User::query()
            ->where('email', Config::string('constants.admin_email'))
            ->firstOrFail();

        $notifications = $user->unreadNotifications()
            ->oldest()
            ->get();

        if ($notifications->isEmpty()) {
            throw new NotFoundException('No pending Notifications');
        }

        $summary = [];
        $messages = $notifications->map(function (DatabaseNotification $notification) use (&$summary): string {
            if (isset($notification->data['ai-client'])) {
                if (! array_key_exists($notification->data['ai-client'], $summary)) {
                    $summary[$notification->data['ai-client']] = 0;
                }

                $summary[$notification->data['ai-client']] += (int) $notification->data['tokens'];
            }

            return sprintf(
                '- %s | Generated on: %s',
                $notification->data['message'],
                $notification->created_at->format('m/d h:i A')
            );
        })->toArray();

        $services = [];
        foreach ($summary as $service => $tokens) {
            $services[] = sprintf('%s: %s', $service, $tokens);
        }

        $notification = new AiUsageSummaryNotification(
            $services,
            $messages,
            $notifications->count(),
        );

        $notification->onQueue('notifications');

        $user->notify($notification);
        $notifications->markAsRead();

        $message = str('<strong>AI Summary</strong>')
            ->newLine()
            ->append(implode('<br>', $services))
            ->toString();

        PushoverLibrary::notify($message);
    }
}
