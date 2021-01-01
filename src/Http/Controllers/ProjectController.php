<?php

namespace Tanak\TaskManager\Http\Controllers;

use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Tanak\TaskManager\Models\Project;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Str;
use Encore\Admin\Widgets\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Tanak\TaskManager\Models\Actions;
use Tanak\TaskManager\Models\Process;
use Tanak\TaskManager\Models\Tasks;

class ProjectController extends AdminController
{
    protected $title ='Project';

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('Start delete project',[$id]);
        
        collect(explode(',', $id))->filter()->each(function ($id) {
            $Project = Project::find($id);
            if(!$Project) {
                Log::info('Not found project',[$id]);
                return false;
            }
            //Delete actions
            $tasks = Tasks::where('tm_project_id', $id);
            
            $actions = Actions::whereIn('tm_task_id', $tasks->pluck('id')->toArray());
            $actions->delete();

            //Delete Tasks
            $tasks = Tasks::where('tm_project_id', $id);
            $tasks->delete();

            //Delete process
            $process = Process::where('tm_project_id', $id);
            $process->delete();
        });

        return $this->form()->destroy($id);
    }
    public function detail()
    {
        return abort(404);
    }

    protected function grid()
    {
        $grid = new Grid(new Project());
        $grid->column('id', __('ID'));
        $grid->column('name', __('Name'))->expand(function ($model) {
            $t = Tasks::with('process','user','assinger')
                ->where('tm_project_id', $model->id)
                ->limit(10)
                ->orderBy('updated_at','desc')->get();

            $tasks = $t->map(function ($task) use ($model) {
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
                    'name' => '<a href="'.route('task-manager.tasks.show',['project'=>$model->id,'task' => $task->id]).'">'.$task->name.'</a>',
                    'content' => Str::limit($task->content,20),
                    'status' => $status,
                    'assignee'=>$assignee,
                    'created_by'=>'<img src="'.$task->user->avatar.'" class="user-avatar-border" alt="User Image"><span>'.$task->user->name.'</span>',
                    'updated_at' => $task->updated_at
                ]);
            });
            return new Table(['ID', __('Name'), __('Content'), __('Status'),__('Assignee'),__('Created by'),__('Updated at'),''], $tasks->toArray());
        });
        $grid->column('description', __('Desciption'))->display(function($value){
            return Str::limit($value,20);
        });
        $grid->column('created_at', __('Created at'))->display(function($value){
            return Carbon::parse($value)->format('Y-m-d H:m:s');
        });
        $grid->column('updated_at', __('Updated at'))->display(function($value){
            return Carbon::parse($value)->format('Y-m-d H:m:s');
        });

        $grid->column('taks')->display(function() {
            return '<a class="btn btn-sm btn-primary" href="'.route('task-manager.tasks.index',['project'=>$this->id]).'">'.__('Open').'</a>';
        });

        $grid->actions(function ($actions) {
            $actions->disableView();
            // $actions->disableEdit();
            // $actions->disableDelete();
        });

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new Project());
        $form->text('name', __('Name'))->rules('required');
        $form->textarea('description', __('Desciption'));

        $form->hasMany('process', function (Form\NestedForm $form) {
            $form->text('name', __('Name'))->rules('required');
            $form->color('color',__('Color'))->default('#ed8077');
            $form->hidden('sort_by')->default(0);
        });

        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        return $form;
    }

}
