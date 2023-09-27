<?php

namespace Savannabits\Saas\Commands;

use Illuminate\Console\Command;

class SaasCommand extends Command
{
    public $signature = 'saas';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
