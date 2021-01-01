<?php

namespace Tanak\TaskManager;

use Encore\Admin\Admin;
use Illuminate\Support\ServiceProvider;

class TaskManagerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfig();
    }


    /**
     * {@inheritdoc}
     */
    public function boot(TaskManager $extension)
    {
        Admin::js('vendor/tanak/task-manager/task-manager.js');
        Admin::css('vendor/tanak/task-manager/task-manager.css');
        if (! TaskManager::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'task-manager');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/tanak/task-manager')],
                'task-manager'
            );
        }

        $this->app->booted(function () {
            TaskManager::routes(__DIR__.'/../routes/web.php');
        });

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'task-manager');
        //translation
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang')
        ]);

        $this->publishMigrations();
        $this->publishSeeders();


    }

    private function publishMigrations()
    {
        $path = __DIR__ . '/../database/migrations/';
        $this->publishes([$path => database_path('migrations')], 'migrations');
    }

    private function publishSeeders()
    {
        $path = __DIR__ . '/../database/seeders/';
        $this->publishes([$path => database_path('seeders')], 'seeders');
    }

    private function mergeConfig()
    {
        $path = $this->getConfigPath();
        $this->mergeConfigFrom($path, 'task-manager');
    }

    private function getConfigPath()
    {
        return __DIR__ . '/../config/task-manager.php';
    }

}
