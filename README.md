# Application Manager Package

## Installation

### Install the package using Composer:

```bash
composer require risky2k1/application-manager
```

### Publishing Assets
You can publish the package's migrations and config using the following Artisan commands:

```bash
php artisan vendor:publish --provider="Risky2k1\ApplicationManager\ApplicationManagerServiceProvider" --tag="migrations"
```
```bash
php artisan vendor:publish --provider="Risky2k1\ApplicationManager\ApplicationManagerServiceProvider" --tag="config"
```

(Optional) If you want to change the view and lang files:

```bash
php artisan vendor:publish --provider="Risky2k1\ApplicationManager\ApplicationManagerServiceProvider" --tag="views"
php artisan vendor:publish --provider="Risky2k1\ApplicationManager\ApplicationManagerServiceProvider" --tag="langs"
```
### Run migration:

```bash
php artisan migrate
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

## Usage
After done the installation:
--index page
![image](https://github.com/risky2k1/application-manager/assets/97021417/49a49590-9883-44d1-9eb1-96401d2b538b)
--show page
![image](https://github.com/risky2k1/application-manager/assets/97021417/d15930ed-6093-49be-9ed7-cda1ef241461)




