<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Class UploadOssCommand
 * @package App\Console\Commands
 */
class UploadOssCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload assets to oss';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->uploadDir(public_path('css'));
        $this->uploadDir(public_path('js'));
        return 0;
    }

    /**
     * @param string $dir
     * @return bool
     */
    public function uploadDir(string $dir): bool
    {
        $oss = Storage::disk('oss');
        if (!is_dir($dir)) {
            return false;
        }

        $handle = opendir($dir);
        while (($file = readdir($handle)) !== false) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            $file = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_file($file)) {
                $filePathParts = explode(DIRECTORY_SEPARATOR, $file);
                $path = "static/gdcn/{$filePathParts[count($filePathParts) - 2]}/{$filePathParts[count($filePathParts) - 1]}";
                $this->info("$file To $path");
                $this->info('');
                $oss->put($path, file_get_contents($file));
            } elseif (is_dir($file)) {
                $this->uploadDir($file);
            }
        }

        return true;
    }
}
