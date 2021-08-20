<?php

namespace App\Console\Commands\Game\Account\Permission;

use App\Models\Game\Account\Permission\Flag;
use App\Models\Game\Account\Permission\Group;
use Illuminate\Console\Command;

class GiveFlagToGroupCommand extends Command
{
    protected $signature = 'game:account_permission_group.give_flag {name} {flag}';

    protected $description = 'Give flag to group.';

    public function handle($name, $flag): int
    {
        $group = Group::whereName($name)
            ->firstOrFail();

        /** @var Flag $flagModel */
        $flagModel = $group->flags()
            ->create([
                'name' => $flag
            ]);

        $created = $flagModel->assigns()
            ->create([
                'group' => $group->id
            ]);

        $this->info($created);
        return 0;
    }
}
