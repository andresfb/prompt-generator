<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\MarkPromptUsedAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MarkPromptUsedController extends Controller
{
    public function __invoke(Request $request, MarkPromptUsedAction $action): JsonResponse
    {
        $values = $request->validate([
            'hash' => 'required|string',
        ]);

        $action->handle($values['hash']);

        return response()->json([
            'data' => [
                'message' => 'success',
            ],
        ]);
    }
}
