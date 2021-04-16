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
     * Upload path map
     *
     * @var array|string[]
     */
    protected $map = [
        'app.css' => 'css/app.css',
        'manifest.js' => 'js/manifest.js',
        'vendor.js' => 'js/vendor.js',
        'app.js' => 'js/app.js',
        'logo.png' => 'images/logo.png',
        'title.png' => 'images/title.png',
        'vendor.js.LICENSE.txt' => 'LICENSE/vendor.js.LICENSE.txt',
        'app.js.LICENSE.txt' => 'LICENSE/app.js.LICENSE.txt'
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $disk = Storage::disk('oss.cdn');

        foreach ($this->map as $item => $path) {
            $itemPath = public_path('resources/' . $item);
            $itemContent = file_get_contents($itemPath);

            $disk->put('gdcn/assets/' . $path, $itemContent);
        }

        return 0;
    }
}
