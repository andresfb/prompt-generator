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
        string $service,
        string $client,
        string $model,
        string $title,
    ): void {

        $totalTokens = $response->usage->completionTokens + $response->usage->promptTokens;
        $cost = sprintf('Total tokens %d', $totalTokens);
        $serviceClass = str($service)->classBasename()->toString();

        $message = sprintf(
            '%s used %s with model %s to generate %s. %s',
            $serviceClass,
            $client,
            $model,
            $title,
            $cost,
        );

        User::notification($message, $service);
        PushoverLibrary::notify($message);
    }
}
