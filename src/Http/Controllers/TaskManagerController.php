<?php

namespace Tanak\TaskManager\Http\Controllers;

use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use Illuminate\Routing\Controller;
use Tanak\TaskManager\Models\Process;
use Tanak\TaskManager\Models\Project;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\DB;
use Tanak\TaskManager\Models\Actions;
use Tanak\TaskManager\Models\Tasks;
use Illuminate\Support\Str;
use Encore\Admin\Widgets\Table;

class TaskManagerController extends AdminController
{
    public function index(Content $content)
    {
        // Admin::css();
        return $content
            ->title(trans('task-manager::task-manager.title'))
            ->description('Description')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append(view('task-manager::row'));
                });
            })
            ->row(function (Row $row) {
                $projects = Project::with('process','tasks','tasks.actionLast')->get();
                foreach($projects as $project) {
                    $row->column(12, function (Column $column) use ($project) {
                        $column->append(self::report($project));
                    });
                    // break;
                }
            });
    }

    public static function report($project)
    {
        $tasks = Tasks::with('process','user','assinger')
                ->where('tm_project_id', $project->id)
                ->limit(6)
                ->orderBy('updated_at','desc')->get();
        $tasks = $tasks->map(function ($task) use ($project) {
            if(!$task) return;
            $Process = $task->process;
            $status = '';
            if($Process) {
                $status = '<span class="label" style="background-color:'.$Process->color.'">'.$Process->name.'</span>';
            }
            $assignee = '';
            $assignee = '<img src="'.$task->assinger->avatar.'" class="user-avatar-border" alt="User Image"><span>'.$task->assinger->name.'</span>';
            return collect([
                'id' => $task->id,
                'name' => '<a href="'.route('task-manager.tasks.show',['project'=>$project->id,'task' => $task->id]).'">'.Str::limit($task->name,10).'</a>',
                'content' => Str::limit($task->content,10),
                'status' => $status,
                'assignee'=>$assignee,
                'created_by'=>'<img src="'.$task->user->avatar.'" class="user-avatar-border" alt="User Image"><span>'.$task->user->name.'</span>'
            ]);
        });
        $table = new Table(['ID', __('Name'), __('Content'), __('Status'),__('Assignee'),__('Created by'),''], $tasks->toArray());
        return view('task-manager::index',compact('project','table'));
    }
}
