<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\MarkPromptUsedJob;
use Illuminate\Http\Request;

final class MarkPromptUsedController extends Controller
{
    public function __invoke(Request $request)
    {
        $values = $request->validate([
            'hash' => 'required|string',
        ]);

        MarkPromptUsedJob::dispatch($values['hash']);

        return response()->noContent();
    }
}
