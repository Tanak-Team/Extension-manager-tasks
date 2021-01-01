<?php

use Illuminate\Support\Facades\Route;
use Tanak\TaskManager\Http\Controllers\ActionsController;
use Tanak\TaskManager\Http\Controllers\ProcessController;
use Tanak\TaskManager\Http\Controllers\ProjectController;
use Tanak\TaskManager\Http\Controllers\TaskManagerController;
use Tanak\TaskManager\Http\Controllers\TasksController;
use Tanak\TaskManager\Models\Tasks;

Route::name('task-manager.')->prefix('task-manager')->group(function(){
    Route::get('/', TaskManagerController::class.'@index');

    Route::resource('project', ProjectController::class);
    Route::resource('project/{project}/tasks', TasksController::class);

    Route::post('actions', ActionsController::class)->name('actions');
});


