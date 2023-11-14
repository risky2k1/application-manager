# Application Manager Package

## Installation

### Install the package using Composer:

```bash
composer require risky2k1/application-manager
```

### Publishing Assets
You can publish the package's assets, such as migrations and views, config using the following Artisan commands:

```bash
php artisan vendor:publish --provider="Risky2k1\ApplicationManager\ApplicationManagerServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="Risky2k1\ApplicationManager\ApplicationManagerServiceProvider" --tag="views"
php artisan vendor:publish --provider="Risky2k1\ApplicationManager\ApplicationManagerServiceProvider" --tag="config"
```
### Run migration:

```bash
php artisan migrate
```

### Cache Routes:
```bash
php artisan route:cache
```

### Add to side bar:

```bash
<div class="menu-item">
   <!--begin:Menu link-->
      <a class="menu-link {{request()->routeIs('applications.*') ? 'bg-success':''}}" href="{{route('applications.index',['type'=>config('application-manager.application.default')])}}">
          <span class="menu-icon"><i class="fa-solid fa-file"></i></span>
          <span class="menu-title">Đơn từ</span>
      </a>
   <!--end:Menu link-->
</div>
```
![image](https://github.com/risky2k1/application-manager/assets/97021417/1bc1a8cf-d6ac-4ab9-8967-f44390303a8d)




