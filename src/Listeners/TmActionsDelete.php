<?php

namespace Tanak\TaskManager\Listeners;

use Tanak\TaskManager\Events\TmTaskDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Tanak\TaskManager\Models\Actions;

class TmActionsDelete
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  TmTaskDeleted  $event
     * @return void
     */
    public function handle(TmTaskDeleted $event)
    {
        $task = $event->task;
        Actions::where('tm_task_id', $task->id)->delete();
        Log::info('Delete all the actions with task', [$task->id]);
    }
}
