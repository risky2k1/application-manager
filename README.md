# application-manager

install:
composer require risky2k1/application-manager

publish:
php artisan vendor:publish --provider="Risky2k1\ApplicationManager\ApplicationManagerServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="Risky2k1\ApplicationManager\ApplicationManagerServiceProvider" --tag="views"
