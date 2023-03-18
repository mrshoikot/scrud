<?php

namespace Mrshoikot\Scrud;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Mrshoikot\Scrud\Commands\ScrudCommand;

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
