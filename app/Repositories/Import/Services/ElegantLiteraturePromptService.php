<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Libraries\MediaNamesLibrary;
use App\Models\Prompter\ElegantLiteraturePrompt;
use App\Repositories\Import\Services\Base\BaseImporterService;
use Exception;
use Illuminate\Filesystem\AwsS3V3Adapter;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;
use RuntimeException;

final class ElegantLiteraturePromptService extends BaseImporterService
{
    private const string S3_DISK = 's3';

    private const string S3_LOCAL_DISK = 's3-local';

    public function getName(): string
    {
        return 'Elegant Literature Prompt';
    }

    /**
     * @throws Exception
     */
    protected function execute(): void
    {
        $dataFile = storage_path('app/public/promptgendata/elegant-literature-magazine/prompts.csv');
        $data = collect(
            $this->readFile($dataFile)
        );

        $this->listS3FilesLazy()
            ->each(function (string $file) use ($data) {
                $fileName = pathinfo($file, PATHINFO_BASENAME);
                $item = $data->where('image', $fileName)->first();
                if ($item === null) {
                    return;
                }

                $prompt = ElegantLiteraturePrompt::updateOrCreate([
                    'hash' => md5(sprintf('%s:%s', mb_strtolower(trim($item['title'])), mb_strtolower($item['image']))),
                ], [
                    'title' => trim($item['title']),
                    'text' => trim($item['text']),
                ]);

                $this->character('.');

                $prompt->addMediaFromDisk($file, self::S3_LOCAL_DISK)
                    ->preservingOriginal()
                    ->toMediaCollection(MediaNamesLibrary::image(), self::S3_DISK);

                $this->character('|');
            });
    }

    private function listS3FilesLazy(): LazyCollection
    {
        /** @var AwsS3V3Adapter $adapter */
        $adapter = Storage::disk(self::S3_LOCAL_DISK);
        $s3 = $adapter->getClient();
        $bucket = Config::string(sprintf(
            'filesystems.disks.%s.bucket',
            self::S3_LOCAL_DISK
        ));

        return LazyCollection::make(static function () use ($s3, $bucket) {
            $path = Config::string('constants.elegant_literature_base_path');
            $paginator = $s3->getPaginator('ListObjectsV2', [
                'Bucket' => $bucket,
                'Prefix' => $path,
            ]);

            foreach ($paginator as $result) {
                if (! isset($result['Contents'])) {
                    continue;
                }

                foreach ($result['Contents'] as $object) {
                    yield $object['Key'];
                }
            }
        });
    }

    private function readFile(string $dataFile): array
    {
        if (! is_readable($dataFile)) {
            throw new RuntimeException("File not readable: $dataFile");
        }

        $handle = fopen($dataFile, 'rb');
        if ($handle === false) {
            throw new RuntimeException("Failed to open file: $dataFile");
        }

        // Read header (field names)
        $header = fgetcsv($handle, 0, ',', '"', '\\');
        if ($header === false) {
            fclose($handle);

            return []; // empty file
        }

        // Trim header values (optional)
        $header = array_map(trim(...), $header);

        $rows = [];
        while (($data = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
            // If a row has fewer columns than the header, pad with nulls
            if (count($data) < count($header)) {
                $data = array_pad($data, count($header), null);
            }

            // If a row has extra columns, keep them with numeric keys or ignore extras:
            // $data = array_slice($data, 0, count($header));
            $rows[] = array_combine($header, $data);
        }

        fclose($handle);

        return $rows;
    }
}
