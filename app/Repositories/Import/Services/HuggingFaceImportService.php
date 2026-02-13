<?php

namespace App\Repositories\Import\Services;

use App\Models\HuggingFacePrompt;
use App\Repositories\Import\Interfaces\ImportServiceInterface;
use App\Traits\Screenable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class HuggingFaceImportService implements ImportServiceInterface
{
    use Screenable;

    public function import(): void
    {
        $dataFile = storage_path('app/public/promptgendata/huggingface/prompts.txt');
        if (! file_exists($dataFile)) {
            throw new RuntimeException("{$this->getName()} data not found in $dataFile");
        }

        $data = file($dataFile);
        if (blank($data)) {
            throw new RuntimeException("No records found in file $dataFile");
        }

        DB::table('hugging_face_prompts')->truncate();

        foreach ($data as $datum) {
            HuggingFacePrompt::create([
                'text' => str($datum)
                    ->replace("\n", '')
                    ->replace("  ", ' ')
                    ->replace(" ,", ',')
                    ->replace(" .", '.')
                    ->replace(" ;", ';')
                    ->replace("`` ", '"')
                    ->replace("``", '"')
                    ->replace(" ''", '"')
                    ->replace(" ' ", "' ")
                    ->replace(" ',", "',")
                    ->replace(" '.", "'.")
                    ->replace("’", "'")
                    ->replace("‘", "'")
                    ->replace("''", '"')
                    ->replace(' " ', '" ')
                    ->replace(" )", ')')
                    ->replace("( ", '(')
                    ->replace(" ]", ']')
                    ->replace("[ ", '[')
                    ->replace(" ?", '?')
                    ->replace(" !", '!')
                    ->replace(" :", ':')
                    ->replace(" %", '%')
                    ->replace("$ ", '$')
                    ->replace(" ...", '...')
                    ->replace("do n't", "don't", false)
                    ->replace("does n't", "doesn't", false)
                    ->replace("have n't", "haven't", false)
                    ->replace("has n't", "has't", false)
                    ->replace("wo n't", "won't", false)
                    ->replace("is n't", "isn't", false)
                    ->replace(" 've ", "'ve ")
                    ->replace(" ' ve ", "'ve ")
                    ->replace(" 're ", "'re ")
                    ->replace(" ' re ", "'re")
                    ->replace(" 'll ", "'ll ")
                    ->replace(" 's ", "'s ")
                    ->replace(" ' s ", "'s ")
                    ->replace(" 'd ", "'d ")
                    ->replace(" 'm ", "'m ")
                    ->replace(" 've.", "'ve.")
                    ->replace(" ' ve.", "'ve.")
                    ->replace(" 're.", "'re.")
                    ->replace(" ' re.", "'re.")
                    ->replace(" 'll.", "'ll.")
                    ->replace(" 's.", "'s.")
                    ->replace(" ' s.", "'s.")
                    ->replace(" 'd.", "'d.")
                    ->replace(" 'm.", "'m.")
                    ->replace(" 've,", "'ve,")
                    ->replace(" ' ve,", "'ve,")
                    ->replace(" 're,", "'re,")
                    ->replace(" ' re,", "'re,")
                    ->replace(" 'll,", "'ll,")
                    ->replace(" 's,", "'s,")
                    ->replace(" ' s,", "'s,")
                    ->replace(" 'd,", "'d,")
                    ->replace(" 'm,", "'m,")
                    ->replaceEnd(" 've", "'ve")
                    ->replaceEnd(" ' ve", "'ve")
                    ->replaceEnd(" 're", "'re")
                    ->replaceEnd(" ' re ", "'re")
                    ->replaceEnd(" 'll", "'ll")
                    ->replaceEnd(" 's", "'s")
                    ->replaceEnd(" ' s", "'s")
                    ->replaceEnd(" 'd", "'d")
                    ->replaceEnd(" 'm", "'m")
                    ->replaceEnd(' "', '"')
                    ->replaceEnd(' " ', '"')
                    ->replaceEnd(" '", "'")
                    ->replaceEnd(" ' ", "'")
                    ->replaceEnd(" '.", "'.")
                    ->replaceStart('-', '')
                    ->replaceStart(':', '')
                    ->replaceStart('" ', '"')
                    ->replaceStart("' ", "'")
                    ->trim(),
            ]);

            $this->character('.');
        }

        $this->line();
    }

    public function getName(): string
    {
        return 'Import Hugging Face Prompts';
    }
}
