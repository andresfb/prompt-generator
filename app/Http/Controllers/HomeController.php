<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\RandomPromptAction;
use App\Http\Requests\RandomPromptRequest;
use Illuminate\View\View;

final class HomeController extends Controller
{
    public function __invoke(RandomPromptRequest $request, RandomPromptAction $active): View
    {
        $values = $request->validated();

        $prompt = $active->handle($values['ptr'] ?? '');

        return view(
            'home',
            ['prompt' => $prompt],
        );
    }
}
