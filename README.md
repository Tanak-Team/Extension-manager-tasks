# Manager tasks extension for laravel-admin

========================

## Screenshot
![wx20180904-103609](https://repository-images.githubusercontent.com/325947973/d71e0f00-4c53-11eb-9e43-1abe7ec95485)

## Installation

```
$ composer require tanak/task-manager

$ php artisan vendor:publish --provider=Tanak\\TaskManager\\TaskManagerServiceProvider

$ php artisan migrate

$ php artisan db:seed --class=TaskManagerSeeder

$ php artisan admin:import task-manager
```

Open `http://your-host/admin/task-manager`.

License
------------
Licensed under [The MIT License (MIT)](LICENSE).

