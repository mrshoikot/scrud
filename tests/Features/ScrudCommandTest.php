<?php

namespace Mrshoikot\Scrud\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Artisan;
use Mrshoikot\Scrud\ScrudServiceProvider;

class ScrudCommandTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('make:model', ['name' => 'ExistingModel']);
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
    }


    /**
     * Test that the isSafeToRun method returns true when it is safe to run
     *
     * @return void
     */
    public function testIsSafeToRunReturnsTrueWhenSafe()
    {
        $this->assertTrue(
            Artisan::call('scrud', ['model' => 'NonExistingModel']) === 0
        );
    }

    /**
     * Test that the isSafeToRun method returns false when it is not safe to run
     *
     * @return void
     */
    public function testIsSafeToRunReturnsFalseWhenNotSafe()
    {
        $this->assertFalse(
            Artisan::call('scrud', ['model' => 'ExistingModel']) === 0
        );
    }

}