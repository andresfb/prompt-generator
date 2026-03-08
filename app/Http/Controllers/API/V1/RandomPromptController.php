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
        $prompter = mb_strtolower($request->validated('ptr') ?? '');
        $format = mb_strtolower($request->validated('format') ?? 'json');

        $prompt = $action->handle(
            prompterKey: $prompter,
            forMcp: $format === 'mcp'
        );

        $content = match ($format) {
            'mcp' => $prompt->toMcp(),
            'md', 'markdown' => $prompt->toMarkdown(),
            'html'=> $prompt->toHtml(),
            default => $prompt->toJson(),
        };

        return response()->json([
            'data' => [
                'format' => $format,
                'hash' => $prompt->hash(),
                'prompt' => $content,
            ],
        ]);
    }
}
