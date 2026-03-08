<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\MarkPromptUsedAction;
use Illuminate\Http\Request;

final class MarkPromptUsedController extends Controller
{
    public function __invoke(Request $request, MarkPromptUsedAction $action)
    {
        $values = $request->validate([
            'hash' => 'required|string',
        ]);

        $action->handle($values['hash']);

        return response()->noContent();
    }
}
