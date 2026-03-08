<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\MarkPromptUsedAction;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class DownloadPrompterController extends Controller
{
    public function __invoke(Request $request, MarkPromptUsedAction $action): StreamedResponse
    {
        $values = $request->validate([
            'hash' => 'required|string',
        ]);

        $item = Cache::get($values['hash']);

        abort_unless($item instanceof PromptItemInterface, 404);

        $action->handle($values['hash']);

        $markdown = $item->toMarkdown();
        $filename = sprintf(
            '%s-%s.md',
            str(class_basename($item))->kebab()->toString(),
            now()->format('Y-m-d-His')
        );

        return response()->streamDownload(function () use ($markdown): void {
            echo $markdown;
        }, $filename, [
            'Content-Type' => 'text/markdown',
        ]);
    }
}
