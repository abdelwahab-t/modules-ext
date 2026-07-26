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

        if (!$this->createModule($name = ucfirst($this->argument('name')))){
            return self::FAILURE;
        }

        $this->createApp()
            ->createMigrations()
            ->createLanguages()
            ->createConfigurations($name)
            ->createRoutes($name)
            ->createViews($name)
            ->createMainController($name)
            ->createMainModel($name)
            ->createMainRequest($name)
            ->createMainResource($name)
            ->createMainProvider($name)
            ->createMainMiddleware($name)
            ->composer->dumpAutoloads();

        $this->info("Module [$name] created successfully at modules/$name");
        return self::SUCCESS;

    }

    private function createModule(string $name): bool
    {

        $this->modulePath = $this->application->basePath("app/Modules/$name");
        if (File::exists($this->modulePath)) {
            $this->error("Module [$name] already exists!");
            return false;
        }

        return true;

    }

    private function createApp(): self
    {
        File::makeDirectory($this->modulePath . '/App/Models', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Providers', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Http/Controllers', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Http/Requests', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Http/Resources', 0755, true);
        File::makeDirectory($this->modulePath . '/App/Http/Middleware', 0755, true);
        
        return $this;
    }

    private function createRoutes(string $name): self
    {

        if ($this->option('no-routes')) {
            return $this;
        }

        File::makeDirectory($this->modulePath . '/routes', 0755, true);

        if (!$this->option('no-web')) {
            File::put(
                $this->modulePath . '/routes/web.php',
                "<?php\n\nuse Illuminate\Support\Facades\Route;\n\nRoute::get('/" . strtolower($name) . "', function () {\n    return view('" . strtolower($name) . "::index');\n});\n"
            );
        }

        if ($this->option('api')) {
            File::put(
                $this->modulePath . '/routes/api.php',
                "<?php\n\nuse Illuminate\Support\Facades\Route;\n\nRoute::get('/" . strtolower($name) . "', function () {\n    return response()->json(['message' => '$name API endpoint working']);\n});\n"
            );
        }

        return $this;

    }

    private function createMigrations(): self
    {
        File::makeDirectory($this->modulePath . '/database/migrations', 0755, true);
        File::makeDirectory($this->modulePath . '/database/seeders', 0755, true);
        File::makeDirectory($this->modulePath . '/database/factories', 0755, true);

        return $this;
    }

    private function createViews(string $name): self
    {
        File::makeDirectory($this->modulePath . '/views', 0755, true);
        File::put($this->modulePath . '/views/index.blade.php', "<h1>$name Module Loaded!</h1>");

        return $this;
    }

    private function createLanguages(): self
    {
        foreach (self::LANGUAGES as $lang) {
            File::makeDirectory($this->modulePath . '/lang/' . $lang, 0755, true);
        }
        return $this;
    }

    private function createConfigurations(string $name): self
    {
        File::put(
            $this->modulePath . '/config.php',
            "<?php\n\nreturn [\n    // $name module config\n];\n"
        );
        return $this;
    }

    private function createMainController(string $name): self
    {
        $className = $name . 'Controller';
        File::put(
            $this->modulePath . "/App/Http/Controllers/$className.php",
            "<?php\n\nnamespace App\\Modules\\$name\\App\\Http\\Controllers;\n\nuse Illuminate\\Routing\\Controller;\n\nuse Illuminate\Http\JsonResponse;\n\nclass $className extends Controller\n{\n    public function __invoke(): JsonResponse\n    {\n        return response()->json(['message' => '$name controller works']);\n    }\n}\n"
        );
        return $this;
    }

    private function createMainRequest(string $name): self
    {
        $className = $name . 'Request';
        File::put(
            $this->modulePath . "/App/Http/Requests/$className.php",
            "<?php\n\nnamespace App\\Modules\\$name\\App\\Http\\Requests;\n\nuse Illuminate\\Foundation\\Http\\FormRequest;\n\nclass $className extends FormRequest\n{\n    public function authorize(): bool\n    {\n        return true;\n    }\n\n    public function rules(): array\n    {\n        return [];\n    }\n}\n"
        );
        return $this;
    }

    private function createMainResource(string $name): self
    {
        $className = $name . 'Resource';
        File::put(
            $this->modulePath . "/App/Http/Resources/$className.php",
            "<?php\n\nnamespace App\\Modules\\$name\\App\\Http\\Resources;\n\nuse Illuminate\Http\Resources\Json\JsonResource;\n\nclass $className extends JsonResource\n{\n    public function toArray(" . '$request' . ")\n    {\n       parent::toArray(" . '$request' . ");\n    }\n}\n"
        );
        return $this;
    }

    private function createMainModel(string $name): self
    {
        File::put(
            $this->modulePath . "/App/Models/$name.php",
            "<?php\n\nnamespace App\\Modules\\$name\\App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass $name extends Model\n{\n    protected ". '$guarded' ." = [];\n}\n"
        );
        return $this;
    }

    private function createMainProvider(string $name): self
    {
        $className = $name . 'ServiceProvider';
        File::put(
            $this->modulePath . "/App/Providers/$className.php",
            "<?php\n\nnamespace App\\Modules\\$name\\App\\Providers;\n\nuse Illuminate\\Support\\ServiceProvider;\n\nclass $className extends ServiceProvider\n{\n    public function register(): void\n    {\n        // Register bindings or module-specific services here\n    }\n\n    public function boot(): void\n    {\n        // Load routes, views, migrations, translations, etc.\n    }\n}\n"
        );
        return $this;
    }

    private function createMainMiddleware(string $name): self
    {
        $className = $name . 'Middleware';
        File::put(
            $this->modulePath . "/App/Http/Middleware/$className.php",
            "<?php\n\nnamespace App\\Modules\\$name\\App\\Http\\Middleware;\n\nuse Closure;\nuse Illuminate\\Http\\Request;\n\nclass $className\n{\n    public function handle(Request " . '$request' . ", Closure " . '$next' . ")\n    {\n        // Add your middleware logic here\n        return " . '$next($request)' . ";\n    }\n}\n"
        );
        return $this;
    }

}
