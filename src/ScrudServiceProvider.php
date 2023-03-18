<?php

namespace Mrshoikot\Scrud;

use Mrshoikot\Scrud\Commands\ScrudCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ScrudServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('scrud')
            ->hasCommand(ScrudCommand::class);
    }
}
