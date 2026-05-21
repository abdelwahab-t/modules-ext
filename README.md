# Modules Ext

**Modules Ext** is a modern, lightweight, and extensible Laravel package designed to help you structure your application into independent modules. Built with strict typing and SOLID principles in mind, it provides an easy way to auto-load your module's service providers, routes, views, translations, migrations, and seeders out-of-the-box.

## 🚀 Features

- **Decoupled Architecture:** Each module is self-contained with its own providers, routes, and resources.
- **Auto-Discovery:** Automatically finds and registers Service Providers from your modules.
- **SOLID Design:** Built with the Open-Closed Principle (OCP). You can easily extend the package by adding custom module loaders.
- **Modern PHP:** Utilizes PHP 8.2 features like `readonly` properties, constructor property promotion, and strict typing.

## 📦 Installation

Require the package via Composer:

```bash
composer require abdelwahab-t/modules-ext
```

The package will automatically register its main service provider (`ModulesExtServiceProvider`).



## 🏗️ Creating a Module

To create a new module, you can use the included Artisan command. This command scaffolds the entire module directory structure for you:

```bash
php artisan make:module Blog
```

### Command Options:
- `--api`: Generates an `api.php` route file alongside the standard web routes.
- `--no-web`: Skips generating the `web.php` route file.
- `--no-routes`: Skips creating the `routes` directory entirely.

**Example:** Generating a module intended strictly for API use:
```bash
php artisan make:module ApiModule --api --no-web
```

When you run the command, the package will automatically generate a standard, domain-driven directory structure inside your `modules/` folder:

```text
modules/
└── Blog/
    ├── App/
    │   ├── Controllers/
    │   ├── Models/
    │   ├── Services/
    │   └── ...
    ├── Providers/
    ├── database/
    │   ├── migrations/
    │   └── seeders/
    ├── lang/
    ├── routes/
    │   ├── api.php
    │   └── web.php
    └── views/
```

Once the folder structure is in place, the package's boot manager and pipeline will automatically:
1. **Providers:** Auto-discover and register any `*ServiceProvider.php` files inside the module's `Providers` directory.
2. **Routes:** Load any routes defined in `routes/web.php` and `routes/api.php`.
3. **Views:** Register the `views` directory under the module's namespace (matching the module folder name).
4. **Translations:** Register translations from the `lang` directory.
5. **Migrations:** Load database migrations.
6. **Seeders:** Register seeders so they can be called using standard Laravel database seeding.



## 📄 License

This package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
