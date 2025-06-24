<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;

class MakeContract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:contract {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new contract with its interfaces and services';

    public function handle()
    {
        $moduleName = $this->argument('module');
        $name = $this->argument('name');

        if (!Module::has($moduleName)) {
            $this->error("Module {$moduleName} does not exist!");
            return;
        }

        $this->createContractFiles($moduleName, $name);
        $this->info("Contract {$name} created successfully.");
    }

    protected function createContractFiles($module, $name)
    {
        $modulePath = Module::getModulePath($module);
        $paths = [
            "{$modulePath}app/Contracts/{$name}ControllerInterface.php" => $this->getControllerInterfaceStub($name, $module),
            "{$modulePath}app/Http/Controllers/Web/Web{$name}Controller.php" => $this->getControllerStub($name, $module, 'Web'),
            "{$modulePath}app/Http/Controllers/API/API{$name}Controller.php" => $this->getControllerStub($name, $module, 'API'),
            "{$modulePath}app/Services/{$name}Service.php" => $this->getServiceStub($name, $module),
            "{$modulePath}app/Interfaces/{$name}RepositoryInterface.php" => $this->getRepositoryInterfaceStub($name, $module),
            "{$modulePath}app/Repositories/{$name}Repository.php" => $this->getRepositoryStub($name, $module),
        ];

        foreach ($paths as $path => $stub) {
            $directory = dirname($path);
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            if (!File::exists($path)) {
                File::put($path, $stub);
                $this->info("Created: {$path}");
            } else {
                $this->error("File {$path} already exists!");
            }
        }
    }

    protected function getControllerInterfaceStub($name, $module)
    {
        return <<<PHP
<?php

namespace Modules\\{$module}\\Contracts;

interface {$name}ControllerInterface
{
    // Add your methods here
}
PHP;
    }

    protected function getControllerStub($name, $module, $type)
    {
        return <<<PHP
<?php

namespace Modules\\{$module}\\Http\\Controllers\\{$type};

use Modules\\{$module}\\Contracts\\{$name}ControllerInterface;
use Modules\\{$module}\\Services\\{$name}Service;
use Modules\\{$module}\\Http\\Controllers\\{$module}Controller;

class {$type}{$name}Controller extends {$module}Controller implements {$name}ControllerInterface
{

    public function __construct(private {$name}Service \${$name}Service)
    {
        
    }

    // Add your methods here
}
PHP;
    }

    protected function getServiceStub($name, $module)
    {
        return <<<PHP
<?php

namespace Modules\\{$module}\\Services;

use Modules\\{$module}\\Interfaces\\{$name}RepositoryInterface;

class {$name}Service
{

    public function __construct(private {$name}RepositoryInterface \${$name}Repository)
    {

    }

    // Add your methods here
}
PHP;
    }

    protected function getRepositoryInterfaceStub($name, $module)
    {
        return <<<PHP
<?php

namespace Modules\\{$module}\\Interfaces;

interface {$name}RepositoryInterface
{
    // Add your methods here
}
PHP;
    }

    protected function getRepositoryStub($name, $module)
    {
        return <<<PHP
<?php

namespace Modules\\{$module}\\Repositories;

class {$name}Repository implements \\Modules\\{$module}\\Interfaces\\{$name}RepositoryInterface
{
    // Add your methods here
}
PHP;
    }
}
