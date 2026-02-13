<?php

declare(strict_types=1);

namespace App\Repositories\AI\Dtos;

use App\Traits\AiNotifiable;
use Exception;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Text\Response;
use Spatie\LaravelData\Data;
use Throwable;

final class AiChatResponse extends Data
{
    use AiNotifiable;

    public function __construct(
        public string $content = '',
        public string $response = '',
    ) {}

    public static function fromResponse(
        Response $response,
        string $origin,
        string $caller,
        string $client
    ): self {
        $aiResponse = new self;
        $aiResponse->content = $response->text;

        try {
            $aiResponse->response = self::encodeResponse($response);

            $aiResponse->notify($response, $origin, $caller, $client);
        } catch (Throwable $e) {
            Log::error('Error loading data: '.$e->getMessage());
        } finally {
            return $aiResponse;
        }
    }

    /**
     * @throws Exception
     */
    private static function encodeResponse(Response $response): string
    {
        $responseData = [
            'responseMessages' => $response->messages->toArray(),
            'text' => $response->text,
            'finishReason' => $response->finishReason->name,
            'toolCalls' => $response->toolCalls,
            'toolResults' => $response->toolResults,
            'usage' => (array) $response->usage,
            'meta' => (array) $response->meta,
        ];

        return json_encode($responseData, JSON_THROW_ON_ERROR);
    }
}
