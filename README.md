# Application Manager Package

## Installation

Install the package using Composer:

```bash
composer require risky2k1/application-manager
```

Publishing Assets
You can publish the package's assets, such as migrations and views, config using the following Artisan commands:

```bash
php artisan vendor:publish --provider="Risky2k1\ApplicationManager\ApplicationManagerServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="Risky2k1\ApplicationManager\ApplicationManagerServiceProvider" --tag="views"
php artisan vendor:publish --provider="Risky2k1\ApplicationManager\ApplicationManagerServiceProvider" --tag="config"
```
Run migration:

```bash
php artisan migrate
```
