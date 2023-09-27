<?php

namespace Savannabits\Saas\Commands;

use Illuminate\Console\Command;

class VanadiCommand extends Command
{
    public $signature = 'vanadiphp';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
