<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Libraries\MediaNamesLibrary;
use App\Models\ImageBasedPrompt;
use App\Repositories\Import\Interfaces\ImportServiceInterface;
use App\Traits\Screenable;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;

final class ImageBasedPromptsImportService implements ImportServiceInterface
{
    use Screenable;

    private const S3_LOCAL_DISK = 's3-local';

    private const S3_DISK = 's3';

    /**
     * @throws Exception
     */
    public function import(): void
    {
        $this->info('Reading files from S3');

        $banded = Config::array('constants.banded_words');
        $this->listS3FilesLazy()
            ->each(function (string $file) use ($banded) {
                $this->character('-');
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $hash = md5($fileName);
                $content = str($fileName)
                    ->replace($banded, '', false)
                    ->replace('  ', ' ')
                    ->trim()
                    ->ltrim('-')
                    ->trim()
                    ->toString();

                if (ImageBasedPrompt::where('hash', $hash)->exists()) {
                    $this->character('x');

                    return;
                }

                $record = ImageBasedPrompt::create([
                    'hash' => $hash,
                    'content' => $content,
                ]);

                $record->addMediaFromDisk($file, self::S3_LOCAL_DISK)
                    ->preservingOriginal()
                    ->toMediaCollection(MediaNamesLibrary::image(), self::S3_DISK);

                $this->character('|');
            });

        $this->line();
    }

    public function getName(): string
    {
        return 'Import Image Based Prompts';
    }

    private function listS3FilesLazy(): LazyCollection
    {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $s3 = Storage::disk(self::S3_LOCAL_DISK)->getClient();
        $bucket = Config::string(sprintf(
            'filesystems.disks.%s.bucket',
            self::S3_LOCAL_DISK
        ));

        return LazyCollection::make(static function () use ($s3, $bucket) {
            $path = Config::string('constants.source_image_based_path');
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
}
