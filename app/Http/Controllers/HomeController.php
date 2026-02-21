<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\RandomPromptAction;
use Illuminate\View\View;

final class HomeController extends Controller
{
    public function __invoke(RandomPromptAction $active): View
    {
        $prompt = $active->handle();

        return view(
            'home',
            ['text' => $prompt->toHtml()],
        );
    }
}
