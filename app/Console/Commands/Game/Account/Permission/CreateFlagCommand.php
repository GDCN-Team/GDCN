<?php

namespace App\Console\Commands\Game\Account\Permission;

use App\Models\Game\Account\Permission\Flag;
use Illuminate\Console\Command;

class CreateFlagCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:account.permission.flag.create {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a flag';

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
     * @return int
     */
    public function handle(): int
    {
        $this->info(
            Flag::create([
                'name' => $this->argument('name')
            ])
        );

        return 0;
    }
}
