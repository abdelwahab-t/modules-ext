# Modules Ext

**Modules Ext** is a modern, lightweight, and extensible Laravel package designed to help you structure your application into independent modules. Built with strict typing and SOLID principles in mind, it provides an easy way to auto-load your module's routes, views, translations, migrations, and seeders out-of-the-box.

## 🚀 Features

- **Decoupled Architecture:** Each module is self-contained.
- **SOLID Design:** Built with the Open-Closed Principle (OCP). You can easily extend the package by adding custom loaders.
- **Modern PHP:** Utilizes PHP 8.2 features like `readonly` properties, constructor property promotion, and strict typing.
- **Zero Configuration Needed:** Works right out of the box with sensible defaults.

## 📦 Installation

Require the package via Composer:

```bash
composer require abdelwahab-t/modules-ext
```

The package will automatically register its service provider (`ModulesExtServiceProvider`).

## 🛠️ Configuration

By default, the package looks for a `modules` directory in your application root. 

If you want to customize the module paths or register your own custom module loaders, you can publish the configuration file (if applicable) or define them in your Laravel config under `modules-ext`:

```php
// config/modules-ext.php

return [
    'loaders' => [
        \AbdelwahabT\ModulesExt\Loaders\ViewsLoader::class,
        \AbdelwahabT\ModulesExt\Loaders\TranslationsLoader::class,
        \AbdelwahabT\ModulesExt\Loaders\RoutesLoader::class,
        \AbdelwahabT\ModulesExt\Loaders\MigrationsLoader::class,
        \AbdelwahabT\ModulesExt\Loaders\SeedersLoader::class,
        // Add your custom loader here!
    ],
];
```

## 🏗️ Creating a Module

To create a new module, simply create a directory inside your `modules/` folder. The package expects standard Laravel directory structures inside each module:

```
app/
bootstrap/
config/
modules/
└── User/
    ├── database/
    │   ├── migrations/
    │   └── seeders/
    ├── lang/
    ├── routes/
    │   ├── api.php
    │   └── web.php
    └── views/
```

Once the folder structure is in place, the package's Pipeline will automatically:
- Load any routes defined in `routes/web.php` and `routes/api.php`.
- Register the `views` directory under the module's namespace.
- Register translations from the `lang` directory.
- Load database migrations.
- Make seeders available for execution.

## 🧩 Creating Custom Loaders

You can easily extend the package's capabilities by creating your own loader. 

1. Create a class that implements `\AbdelwahabT\ModulesExt\Contracts\ModuleLoaderInterface`.
2. Add your loader class to the `loaders` array in your `modules-ext` configuration file.

```php
<?php

namespace App\Loaders;

use AbdelwahabT\ModulesExt\Contracts\ModuleLoaderInterface;
use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Dto\ModuleDetailsDto;

class CustomConfigLoader implements ModuleLoaderInterface
{
    public function load(ModuleDetailsDto $moduleDetailsDto, ModuleRegistrarInterface $provider): void
    {
        // Your custom logic to load configs, commands, etc.
    }
}
```

## 📄 License

This package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
