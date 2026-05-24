<?php

namespace AbdelwahabT\ModulesExt\Console\Commands;

use AbdelwahabT\ModulesExt\Console\Commands\Generators\BaseModuleGeneratorCommand;
use Illuminate\Support\Facades\File;

class MakeModuleMigrationCommand extends BaseModuleGeneratorCommand
{
    protected $signature = 'make:module:migration {name : Migration class name} {--module= : Target module name}';
    protected $description = 'Create a new migration file inside a module.';

    public function handle(): int
    {
        $name = $this->argument('name');
        $module = $this->option('module');
        if (!$module) {
            $this->error('The --module option is required.');
            return self::FAILURE;
        }
        $this->ensureModuleExists($module);
        $modulePath = $this->resolveModulePath($module);
        $timestamp = date('Y_m_d_His');
        $fileName = "{$timestamp}_{$name}.php";
        $relativePath = 'Database/Migrations/' . $fileName;
        $fullPath = $modulePath . '/' . $relativePath;
        $namespace = $this->resolveNamespace($module) . '\\Database\\Migrations';
        $stub = "<?php\n\nnamespace {$namespace};\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nreturn new class extends Migration {\n    public function up(): void {\n        Schema::create('{$name}', function (Blueprint $table) {\n            $table->id();\n            $table->timestamps();\n        });\n    }\n\n    public function down(): void {\n        Schema::dropIfExists('{$name}');\n    }\n};\n";
        File::ensureDirectoryExists(dirname($fullPath));
        File::put($fullPath, $stub);
        $this->info("Migration {$fileName} created in module {$module}.");
        return self::SUCCESS;
    }
}
