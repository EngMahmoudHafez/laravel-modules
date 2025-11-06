<?php

namespace Nwidart\Modules\Commands\Make;

use Illuminate\Support\Str;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RepositoryMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';

    protected $name = 'module:make-repository';

    protected $description = 'Create a new repository class for the specified module.';

    public function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $filePath = GenerateConfigReader::read('repository')->getPath() ?? config('modules.paths.app_folder').'Repositories';

        return $path.$filePath.'/'.$this->getRepositoryName().'.php';
    }

    protected function getTemplateContents(): string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        $repositoryName = $this->getClassNameWithoutNamespace();
        $interfaceName = str_replace('Repository', 'RepositoryInterface', $repositoryName);
        
        // Try to determine if RepositoryInterface exists
        $interfaceNamespace = $this->getInterfaceNamespace($module, $interfaceName);
        $hasInterface = $this->hasRepositoryInterface($module, $interfaceName);

        $replacements = [
            'CLASS_NAMESPACE' => $this->getClassNamespace($module),
            'CLASS' => $repositoryName,
        ];

        // Add interface-related replacements if interface exists
        if ($hasInterface) {
            $replacements['REPOSITORY_INTERFACE_NAMESPACE'] = $interfaceNamespace;
            $replacements['REPOSITORY_INTERFACE'] = $interfaceName;
            $replacements['REPOSITORY_INTERFACE_USE'] = "\nuse {$interfaceNamespace};";
            $replacements['REPOSITORY_INTERFACE_IMPLEMENTS'] = " implements {$interfaceName}";
        } else {
            // If no interface, remove the implements clause from stub
            $replacements['REPOSITORY_INTERFACE_NAMESPACE'] = '';
            $replacements['REPOSITORY_INTERFACE'] = '';
            $replacements['REPOSITORY_INTERFACE_USE'] = '';
            $replacements['REPOSITORY_INTERFACE_IMPLEMENTS'] = '';
        }

        return (new Stub($this->getStubName(), $replacements))->render();
    }

    /**
     * Get interface namespace.
     */
    protected function getInterfaceNamespace($module, string $interfaceName): string
    {
        $interfacesPath = GenerateConfigReader::read('interfaces')->getPath() ?? config('modules.paths.app_folder').'Interfaces';
        $namespace = config('modules.paths.generator.interfaces.namespace', 'Interfaces');
        
        return $this->module_namespace($module->getStudlyName(), $namespace).'\\'.$interfaceName;
    }

    /**
     * Check if repository interface exists.
     */
    protected function hasRepositoryInterface($module, string $interfaceName): bool
    {
        $interfacesPath = GenerateConfigReader::read('interfaces')->getPath() ?? config('modules.paths.app_folder').'Interfaces';
        $interfacePath = $this->laravel['modules']->getModulePath($this->getModuleName()).$interfacesPath.'/'.$interfaceName.'.php';
        
        return $this->laravel['files']->exists($interfacePath);
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the repository class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['invokable', 'i', InputOption::VALUE_NONE, 'Generate an invokable action class', null],
            ['force', 'f', InputOption::VALUE_NONE, 'su.'],
        ];
    }

    protected function getRepositoryName(): array|string
    {
        return Str::studly($this->argument('name'));
    }

    private function getClassNameWithoutNamespace(): array|string
    {
        return class_basename($this->getRepositoryName());
    }

    public function getDefaultNamespace(): string
    {
        return config('modules.paths.generator.repository.namespace', 'Repositories');
    }

    protected function getStubName(): string
    {
        return $this->option('invokable') === true ? '/repository-invoke.stub' : '/repository.stub';
    }
}
