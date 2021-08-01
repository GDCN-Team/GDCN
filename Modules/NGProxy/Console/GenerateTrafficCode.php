<?php

namespace Modules\NGProxy\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class GenerateTrafficCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ngproxy:generate-traffic-code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成激活码';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['count', InputArgument::REQUIRED, '生成数量'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [

        ];
    }
}
