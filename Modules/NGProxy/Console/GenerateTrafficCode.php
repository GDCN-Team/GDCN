<?php

namespace Modules\NGProxy\Console;

use Illuminate\Console\Command;
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
        $codes = TrafficCode::factory()
            ->count($this->ask('count'))
            ->create([
                'traffic_count' => $this->ask('traffic_count')
            ]);

        $codes = $codes->pluck('active_code');
        $codes = implode(PHP_EOL, $codes);
        $this->info($codes);
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
