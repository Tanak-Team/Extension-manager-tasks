<?php

namespace Tanak\TaskManager;

use Encore\Admin\Extension;

class TaskManager extends Extension
{
    public $name = 'task-manager';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => 'Taskmanager',
        'path'  => 'task-manager',
        'icon'  => 'fa-thumb-tack',
    ];
}
