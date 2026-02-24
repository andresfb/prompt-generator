<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\RandomPromptAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RandomPromptRequest;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use Illuminate\Http\JsonResponse;

final class RandomPromptController extends Controller
{
    public function __invoke(RandomPromptRequest $request, RandomPromptAction $action): JsonResponse
    {
        $values = $request->validated();
        $prompt = $action->handle();

        if (blank($values['format'])) {
            return $this->returnJson($prompt);
        }

        $format = mb_strtolower($values['format']);
        if ($format === 'json') {
            return $this->returnJson($prompt);
        }

        if ($format === 'md' || $format === 'markdown') {
            return response()->json([
                'data' => [
                    'format' => $format,
                    'hash' => $prompt->hash(),
                    'prompt' => $prompt->toMarkdown(),
                ]
            ]);
        }

        return response()->json([
            'data' => [
                'format' => $format,
                'hash' => $prompt->hash(),
                'prompt' => $prompt->toHtml(),
            ]
        ]);
    }

    private function returnJson(PromptItemInterface $prompt): JsonResponse
    {
        return response()->json([
            'data' => [
                'format' => 'json',
                'hash' => $prompt->hash(),
                'prompt' => $prompt->toHtml(),
            ]
        ]);
    }
}
