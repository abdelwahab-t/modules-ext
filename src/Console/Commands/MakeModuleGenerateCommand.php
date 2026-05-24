<?php

namespace AbdelwahabT\ModulesExt\Console\Commands;

use AbdelwahabT\ModulesExt\Console\Commands\Generators\BaseModuleGeneratorCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class MakeModuleGenerateCommand extends BaseModuleGeneratorCommand
{
    protected $signature = 'make:module:generate {type : The generator type (model,migration,controller,request,resource,factory,seeder,policy,test,mail,event,listener,job,broadcast)} {name : Name of the class} {--module= : The target module name}';
    protected $description = 'Generate a Laravel class scoped to a module using the --module flag.';

    public function handle(): int
    {
        $type = $this->argument('type');
        $name = $this->argument('name');
        $module = $this->option('module');

        if (!$module) {
            $this->error('The --module option is required.');
            return self::FAILURE;
        }

        $modulePath = $this->validateModule($module);

        if (!$modulePath) {
            $this->error('Module does not exist.');
            return self::FAILURE;
        }

        $namespace = $this->resolveNamespace($module);
        $typeMap = Config::get('modules-artisan.type_map');

        if (!isset($typeMap[$type])) {
            $this->error('Unsupported generator type: ' . $type);
            return self::FAILURE;
        }

        $relativePath = $typeMap[$type] . '/' . $name . $this->classSuffix($type) . '.php';
        $fullPath = $modulePath . '/' . $relativePath;
        $classNamespace = $namespace . '\\' . str_replace(['/', '\\'], '\\', $typeMap[$type]);
        $stub = $this->buildStub($type, $name, $classNamespace);
        File::ensureDirectoryExists(dirname($fullPath));
        File::put($fullPath, $stub);
        $this->info("Created $type $name in module $module.");
        return self::SUCCESS;
    }

    private function classSuffix(string $type): string
    {
        return match ($type) {
            'controller' => 'Controller',
            'request' => 'Request',
            'resource' => 'Resource',
            'policy' => 'Policy',
            'test' => 'Test',
            'mail' => 'Mail',
            'event' => 'Event',
            'listener' => 'Listener',
            'job' => 'Job',
            'broadcast' => 'Broadcast',
            default => '',
        };
    }

    private function buildStub(string $type, string $name, string $classNamespace): string
    {
        $className = $name . $this->classSuffix($type);
        $base = "<?php\n\nnamespace $classNamespace;\n\n";
        return match ($type) {
            'model' => $base . "use Illuminate\\Database\\Eloquent\\Model;\n\nclass $className extends Model\n{\n    protected " . '$guarded' . " = [];\n}\n",
            'migration' => $base . "use Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nreturn new class extends Migration {\n    public function up(): void {\n        Schema::create('$name', function (Blueprint " . '$table' . ") {\n           " . ' $table->id();\n            $table->timestamps();\n  ' . "      });\n    }\n\n    public function down(): void {\n        Schema::dropIfExists('$name');\n    }\n};\n",
            'controller' => $base . "use Illuminate\\Routing\\Controller;\n\nclass $className extends Controller\n{\n    public function __invoke()\n    {\n        return response()->json(['message' => '$name works']);\n    }\n}\n",
            'request' => $base . "use Illuminate\\Foundation\\Http\\FormRequest;\n\nclass $className extends FormRequest\n{\n    public function authorize(): bool\n    {\n        return true;\n    }\n\n    public function rules(): array\n    {\n        return [];\n    }\n}\n",
            'resource' => $base . "use Illuminate\\Http\\Resources\\JsonResource;\n\nclass $className extends JsonResource\n{\n    public function toArray(" . '$request' . ")\n    {\n        return parent::toArray(" . '$request' . ");\n    }\n}\n",
            'factory' => $base . "use Illuminate\\Database\\Eloquent\\Factories\\Factory;\n\nclass $className extends Factory\n{\n    protected \$model = \App\\Models\\$name::class;\n\n    public function definition(): array\n    {\n        return [];\n    }\n}\n",
            'seeder' => $base . "use Illuminate\\Database\\Seeder;\n\nclass $className extends Seeder\n{\n    public function run(): void\n    {\n        //\n    }\n}\n",
            'policy' => $base . "use Illuminate\\Auth\\Access\\HandlesAuthorization;\n\nclass $className\n{\n    use HandlesAuthorization;\n\n    public function view(\$user, \$model)\n    {\n        return true;\n    }\n}\n",
            'test' => $base . "use Tests\\TestCase;\n\nclass $className extends TestCase\n{\n    public function test_example(): void\n    {\n        \$this->assertTrue(true);\n    }\n}\n",
            'mail' => $base . "use Illuminate\\Mail\\Mailable;\n\nclass $className extends Mailable\n{\n    public function build(): self\n    {\n        return \$this->view('mail.$name');\n    }\n}\n",
            'event' => $base . "use Illuminate\\Foundation\\Events\\Dispatchable;\nuse Illuminate\\Broadcasting\\InteractsWithSockets;\n\nclass $className\n{\n    use Dispatchable, InteractsWithSockets;\n}\n",
            'listener' => $base . "use Illuminate\\Contracts\\Events\\Dispatcher;\n\nclass $className\n{\n    public function handle(\$event): void\n    {\n        //\n    }\n}\n",
            'job' => $base . "use Illuminate\\Bus\\Queueable;\nuse Illuminate\\Contracts\\Queue\\ShouldQueue;\nuse Illuminate\\Queueable\n\nclass $className implements ShouldQueue\n{\n    use Queueable;\n\n    public function handle(): void\n    {\n        //\n    }\n}\n",
            'broadcast' => $base . "use Illuminate\\Broadcasting\\Channel;\nuse Illuminate\\Broadcasting\\InteractsWithSockets;\nuse Illuminate\\Broadcasting\\PresenceChannel;\nuse Illuminate\\Contracts\\Broadcasting\\ShouldBroadcast;\n\nclass $className implements ShouldBroadcast\n{\n    use InteractsWithSockets;\n\n    public function broadcastOn(): array\n    {\n        return [new PresenceChannel('presence')];\n    }\n}\n",
            default => $base . "// Stub for $type not implemented yet.\n",
        };
    }
}
