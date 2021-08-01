<?php

namespace Modules\NGProxy\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Modules\NGProxy\Entities\TrafficCode;

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
     * @return void
     */
    public function handle()
    {
        $count = $this->ask('count');
        $traffic_count = $this->ask('traffic_count');

        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $code = new TrafficCode();
            $code->active_code = Str::random(64);
            $code->traffic_count = $traffic_count;
            $code->save();

            $codes[] = $code;
        }

        $result = null;
        foreach ($codes as $code) {
            $result .= $code->active_code . PHP_EOL;
        }

        $this->info($result);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [

        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [

        ];
    }
}
