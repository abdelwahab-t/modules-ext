<?php

namespace AbdelwahabT\ModulesExt\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\File;

class MakeModuleCommand extends Command
{

    const LANGUAGES = ['en', 'ar'];

    protected $signature = 'make:module {name : The name of the module} {--api : Include an api.php route file} {--no-web : Exclude an web.php route file} {--no-routes : Exclude route folder}';

    protected $description = 'Create a new module inside the modules directory';

    private string $modulePath;


    public function __construct(
        private readonly Composer $composer, 
        private readonly Application $application
    )
    {
        parent::__construct();
    }


    public function handle(): int
    {

        if(!$this->createModule($name = ucfirst($this->argument('name')))){
            return self::FAILURE;
        }

        $this->createApp();
        $this->createMigrations();
        $this->createLanguages();
        $this->createConfigurations($name);
        $this->createRoutes($name);
        $this->createViews($name);

        $this->composer->dumpAutoloads();
        $this->info("✅ Module [{$name}] created successfully at modules/{$name}");
        return self::SUCCESS;

    }

    private function createModule(string $name): bool
    {

        $this->modulePath = $this->application->basePath("App/Modules/{$name}");

        if (File::exists($this->modulePath)) {
            $this->error("Module [{$name}] already exists!");
            return false;
        }

        return true;

    }

    private function createApp(): void
    {
        File::makeDirectory($this->modulePath . '/App', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Models', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Services', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Traits', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Contracts', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Enums', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Repositories', 0755, true);
        File::makeDirectory($this->modulePath . '/App/DTO', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Http', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Http/Controllers', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Http/Requests', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Http/Responses', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Http/Resources', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Http/Middleware', 0755, true);
    }

    private function createRoutes(string $name): void
    {

        if($this->option('no-routes')) {
            return;
        }

        File::makeDirectory($this->modulePath . '/routes', 0755, true);

        if(!$this->option('no-web')) {
            File::put(
                $this->modulePath . '/routes/web.php',
                "<?php\n\nuse Illuminate\Support\Facades\Route;\n\nRoute::get('/" . strtolower($name) . "', function () {\n    return view('" . strtolower($name) . "::index');\n});\n"
            );
        }

        if ($this->option('api')) {
            File::put(
                $this->modulePath . '/routes/api.php',
                "<?php\n\nuse Illuminate\Support\Facades\Route;\n\nRoute::get('/" . strtolower($name) . "', function () {\n    return response()->json(['message' => '{$name} API endpoint working']);\n});\n"
            );
        }

    }

    private function createMigrations(): void
    {
        File::makeDirectory($this->modulePath . '/database/migrations', 0755, true);
        File::makeDirectory($this->modulePath . '/database/seeders', 0755, true);
        File::makeDirectory($this->modulePath . '/database/factories', 0755, true);
    }

    private function createViews(string $name): void
    {
        File::makeDirectory($this->modulePath . '/views', 0755, true);
        File::put($this->modulePath . '/views/index.blade.php', "<h1>{$name} Module Loaded!</h1>");
    }

    private function createLanguages(): void
    {
        foreach (self::LANGUAGES as $lang) {
            File::makeDirectory($this->modulePath . '/lang/' . $lang, 0755, true);
        }
    }

    private function createConfigurations(string $name): void
    {
        File::put($this->modulePath . '/config.php', "<?php\n\nreturn [\n    // {$name} module config\n];\n");
    }

}
