<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\RandomPromptAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RandomPromptRequest;
use Illuminate\Http\JsonResponse;

final class RandomPromptController extends Controller
{
    public function __invoke(RandomPromptRequest $request, RandomPromptAction $action): JsonResponse
    {
        $values = $request->validated();
        if (blank($values['format'])) {
            return response()->json($action->handle());
        }

        $format = mb_strtolower($values['format']);
        $prompt = $action->handle();
        if ($format === 'md' || $format === 'markdown') {
            return response()->json([
                'data' => [
                    'format' => $format,
                    'prompt' => $prompt->toMarkdown(),
                ]
            ]);
        }

        return response()->json([
           'data' => [
               'format' => $format,
               'prompt' => $prompt->toHtml(),
           ]
        ]);
    }
}
