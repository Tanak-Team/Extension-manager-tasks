# Manager tasks extension for laravel-admin

========================

## Screenshot


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

