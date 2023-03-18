<?php

namespace Mrshoikot\Scrud\Commands;

use Illuminate\Console\Command;

class ScrudCommand extends Command
{
    public $signature = 'scrud';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
