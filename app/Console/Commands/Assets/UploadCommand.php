<?php

namespace App\Console\Commands\Assets;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use function public_path;

/**
 * Class UploadCommand
 * @package App\Console\Commands
 */
class UploadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload assets to oss';

    /**
     * @var string
     */
    protected string $prefix = '/static';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->uploadDir([
            public_path('css') => "$this->prefix/css",
            public_path('js') => "$this->prefix/js",
            public_path('fonts') => "$this->prefix/fonts"
        ]);

        return 0;
    }

    protected function uploadDir(array $paths)
    {
        $disk = Storage::disk('oss');
        foreach ($paths as $path => $to) {
            if (!$contents = scandir($path)) {
                $this->info("Dir $path scan Failed");
                continue;
            }

            foreach ($contents as $content) {
                if (in_array($content, ['.', '..'])) {
                    $this->info("Ignore '$content'");
                    continue;
                }

                $file = $path . "/" . $content;
                if (is_dir($file)) {
                    $this->info("'$content' is dir");
                    $this->uploadDir([
                        $file => "$to/$content"
                    ]);
                }

                if (is_file($file)) {
                    $this->info("File '$path/$content' Uploaded to '$to/$content'");
                    $disk->put("$to/$content", file_get_contents("$path/$content"));
                }
            }
        }
    }
}
