<?php

namespace Mrshoikot\Scrud\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Mrshoikot\Scrud\ScrudServiceProvider;
use Orchestra\Testbench\TestCase;

class ScrudCommandTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        // Name of the model to be generated
        $this->model = 'ScrudTestModel';

        // Create Requests directory if it doesn't exist (necessary for testbench)
        if (! File::isDirectory(app_path('Http/Requests'))) {
            File::makeDirectory(app_path('Http/Requests'));
        }

        // Delete existing files for this model
        File::delete(app_path('Models/'.$this->model.'.php'));
        File::delete(app_path('Http/Controllers/'.$this->model.'Controller.php'));
        File::delete(app_path('Http/Requests/'.$this->model.'Request.php'));

        // Find all existing migrations for the model and delete them
        $migrations = File::glob(
            database_path('migrations/*create_'.Str::plural(Str::snake($this->model)).'_table.php')
        );

        foreach ($migrations as $migration) {
            File::delete($migration);
        }

        // Reset database migrations
        Artisan::call('migrate:reset');

        // Make the call to the command
        Artisan::call('scrud', ['model' => $this->model]);
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
     * Test if the controller is generated correctly
     */
    public function testGenerateController()
    {
        $expectedDestination = app_path('Http/Controllers/'.$this->model.'Controller.php');
        app_path('Http/Controllers/'.$this->model.'Controller.php');
        $this->assertFileExists($expectedDestination);
        $this->assertStringContainsString($this->model, file_get_contents($expectedDestination));
        $this->assertStringContainsString(Str::camel(Str::plural($this->model)), file_get_contents($expectedDestination));
        $this->assertStringContainsString(Str::ucfirst(Str::snake($this->model)), file_get_contents($expectedDestination));
    }

    /**
     * Test if the request is generated correctly
     *
     * @return void
     */
    public function testGenerateRequest()
    {
        $expectedDestination = app_path('Http/Requests/'.$this->model.'Request.php');
        $this->assertFileExists($expectedDestination);
        $this->assertStringContainsString($this->model, file_get_contents($expectedDestination));
    }

    /**
     * Test if the model is generated correctly
     *
     * @return void
     */
    public function testGenerateModel()
    {
        $expectedDestination = app_path('Models/'.$this->model.'.php');
        $this->assertFileExists($expectedDestination);
        $this->assertStringContainsString($this->model, file_get_contents($expectedDestination));
    }

    /**
     * Test if the migration is generated correctly
     *
     * @return void
     */
    public function testGenerateMigration()
    {
        $tableName = Str::plural(Str::snake($this->model));
        $migration_file = File::glob(
            database_path(
                'migrations/*create_'.$tableName.'_table.php'
            )
        );

        if ($migration_file) {
            $this->assertFileExists($migration_file[0]);
            $this->assertStringContainsString($tableName, file_get_contents($migration_file[0]));
        } else {
            $this->fail();
        }
    }

    /**
     * Test if table is generated
     * 
     * @return void
     */
    public function testGenerateTable()
    {
        $tableName = Str::plural(Str::snake($this->model));
        $this->assertTrue(\Schema::hasTable($tableName));
    }


    /**
     * Test if view files are generated correctly
     * 
     * @return void
     */
    public function testGenerateViewFiles()
    {
        $viewFiles = [
            'index.blade.php',
            'create.blade.php',
            'edit.blade.php',
            'show.blade.php',
            'form.blade.php',
            'layout.blade.php'
        ];

        foreach ($viewFiles as $viewFile) {
            $expectedDestination = resource_path('views/'.Str::snake(Str::plural($this->model)).'/'.$viewFile);
            $this->assertFileExists($expectedDestination);
        }
    }
}
