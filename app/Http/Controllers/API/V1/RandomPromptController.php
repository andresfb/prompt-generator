<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\RandomPromptAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class RandomPromptController extends Controller
{
    public function __invoke(RandomPromptAction $action): JsonResponse
    {
        return response()->json($action->handle());
    }
}
