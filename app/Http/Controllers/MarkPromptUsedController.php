<?php

namespace App\Http\Controllers;

use App\Jobs\MarkPromptUsedJob;
use Illuminate\Http\Request;

class MarkPromptUsedController extends Controller
{
    public function __invoke(Request $request)
    {
        $values = $request->validate([
            'hash' => 'required|string',
        ]);

        MarkPromptUsedJob::dispatch($values['hash']);
    }
}
