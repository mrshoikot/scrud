<?php

namespace Mrshoikot\Scrud\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mrshoikot\Scrud\ScrudServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

    }

    protected function getPackageProviders($app)
    {
        return [
            ScrudServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_scrud_table.php.stub';
        $migration->up();
        */
    }
}
