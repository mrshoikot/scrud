<?php

namespace Mrshoikot\Scrud\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ScrudCommand extends Command
{
    public $signature = 'scrud {model}';

    public $description = 'Generate a complete CRUD operation for a model';

    protected $modelName, $controllerName, $tableName, $requestName;

    public function handle(): int
    {
        // Define the names of files and elements
        $this->defineNames($this->argument('model'));
        
        if (!$this->isSafeToRun()) {
            return self::FAILURE;
        }

        $this->insertRoute();
        $this->generateController();
        $this->generateRequest();
        $this->generateModel();

        return self::SUCCESS;
    }


    /**
     * Define the names of files and elements
     * 
     * @param string $model
     * @return void
     */
    protected function defineNames(string $model): void
    {
        $this->modelName = $this->argument('model');
        $this->controllerName = $model . 'Controller';
        $this->requestName = $model . 'Request';
        $this->tableName = Str::plural(Str::snake($model));
    }


    /**
     * Check if the application is in a state where the command can be executed safely
     * 
     * @return bool
     */
    protected function isSafeToRun(): bool
    {
        $proceed = true;

        // Check if the model, controller, request and table already exist

        if (File::exists(app_path('Models/' . $this->modelName . '.php'))) {
            $this->warn("Model $this->modelName already exists");
            $proceed = false;
        }

        if (File::exists(app_path('Http/Controllers/' . $this->controllerName . '.php'))) {
            $this->warn("Controller $this->controllerName already exists");
            $proceed = false;
        }

        if (File::exists(app_path('Http/Requests/' . $this->modelName . 'Request.php'))) {
            $this->warn("Request $this->modelName" . "Request already exists");
            $proceed = false;
        }

        if (Schema::hasTable($this->tableName)) {
            $this->warn("Table $this->tableName already exists");
            $proceed = false;
        }

        if (!$proceed) {
            $this->error("For the sake of this application's integrity, I can't move forward!");
            return false;
        }

        return true;
    }


    /**
     * Insert the route for the resource in the web.php file
     * 
     * @return void
     */
    protected function insertRoute()
    {
        $path = base_path('routes/web.php');

        // Create web.php if doens't exist (primariliy for testing)
        if(!is_file($path)){
            $contents = '<?php'.PHP_EOL;
            file_put_contents($path, $contents);
        }

        $content = File::get($path);
        $content .= "\nRoute::resource('" . Str::snake(Str::plural($this->modelName))
                    . "', App\\Http\\Controllers\\" 
                    . $this->controllerName . "::class);\n";
        File::put($path, $content);
    }


    /**
     * Generate the controller
     * 
     * @return void
     */
    public function generateController()
    {
        $stub = __DIR__.'/../../app/Http/Controllers/ScrudController.php.stub';
        $destination = app_path('Http/Controllers/'. $this->controllerName .'.php');

        $this->processAndPublishStub($stub, $destination);
    }


    /**
     * Generate the request
     * 
     * @return void
     */
    public function generateRequest()
    {
        $stub = __DIR__.'/../../app/Http/Requests/ScrudRequest.php.stub';
        $destination = app_path('Http/Requests/'. $this->requestName .'.php');

        $this->processAndPublishStub($stub, $destination);
    }


    /**
     * Generate the model
     * 
     * @return void
     */
    public function generateModel()
    {
        $stub = __DIR__.'/../../app/Models/Scrud.php.stub';
        $destination = app_path('Models/'. $this->modelName .'.php');

        $this->processAndPublishStub($stub, $destination);
    }


    /**
     * Replace the placeholders in the stub with the actual values
     * 
     * @param string $source
     * @param string $destination
     * @return void
     */
    public function processAndPublishStub($source, $destination)
    {
        $contents = file_get_contents($source);
        $contents = str_replace(
            ['{{modelName}}', '{{modelNameCamel}}', '{{modelNameCapitalized}}'],
            [
                $this->modelName,
                Str::camel(Str::plural($this->modelName)),
                Str::ucfirst(Str::snake($this->modelName))
            ],
            $contents
        );

        file_put_contents($destination, $contents);
    }
}
