<?php

declare(strict_types=1);

namespace App\Traits;

use App\Libraries\PushoverLibrary;
use App\Models\User;
use Prism\Prism\Text\Response;

trait AiNotifiable
{
    public function notify(
        Response $response,
        string $origin,
        string $caller,
        string $client,
        string $model,
    ): void {

        $totalTokens = $response->usage->completionTokens + $response->usage->promptTokens;
        $cost = sprintf('Total tokens %d', $totalTokens);

        $message = sprintf(
            '%s used %s, to generated %s. %s',
            str($caller)
                ->replace('_', ' ')
                ->lower()
                ->title()
                ->trim()
                ->value(),
            $model,
            $origin,
            $cost,
        );

        User::notification($message, $client);
        PushoverLibrary::notify($message);
    }
}
