<?php

namespace Mrshoikot\Scrud\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Artisan;
use Mrshoikot\Scrud\ScrudServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ScrudCommandTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = 'ScrudTestModel';

        if (!File::isDirectory(app_path("Http/Requests"))) {
            File::makeDirectory(app_path("Http/Requests"));
        }

        File::delete(app_path("Models/".$this->model.".php"));
        File::delete(app_path("Http/Controllers/".$this->model."Controller.php"));
        File::delete(app_path("Http/Requests/".$this->model."Request.php"));

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

}