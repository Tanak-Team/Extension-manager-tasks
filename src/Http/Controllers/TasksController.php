<?php

namespace Tanak\TaskManager\Http\Controllers;

use Carbon\Carbon;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Tanak\TaskManager\Models\Project;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Row;
// use Tanak\TaskManager\Http\Show;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Tanak\TaskManager\Http\Actions\Comeback;
use Tanak\TaskManager\Models\Tasks;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Log;
use Tanak\TaskManager\Events\TmTaskDeleted;
use Tanak\TaskManager\Models\Actions;

class TasksController extends Controller
{
    protected $title ='Tasks';
    protected $description = [];

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description['index'] ?? trans('admin.list'))
            ->body($this->grid());
    }
    public function create(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description['create'] ?? trans('admin.create'))
            ->body($this->form());
    }

    public function store()
    {
        return $this->form()->store();
    }

    public function edit($project, $id, Content $content)
    {
        $content = new Content();
        return $content
            ->title($this->title())
            ->description($this->description['edit'] ?? trans('admin.edit'))
            ->body($this->form()->edit($id));
    }

    public function update($project,$id)
    {
        return $this->form()->update($id);
    }

    public function show($project, $id, Content $content)
    {
        return Admin::content(function (Content $content) use ($id, $project) {
            $content->header($this->title());
            $content->description(__('Detail'));
            $content->row(function(Row $row) use ($id, $project) {
                $Project = Project::with(['process','tasks.actions','tasks.user','user'])->findOrFail($project);
                $row->column(6, function(Column $column) use ($id, $Project) {
                    $column->row(function(Row $row) use ($id, $Project) {
                        $Tasks = Tasks::with(['project','actions','actions.user','actions.process','actions.assinger'])->findOrFail($id);
                        $row->column(12, function(Column $column) use ($Tasks, $Project) {
                            $column->append(self::actions($Tasks, $Project));
                        });
                        $row->column(12, function(Column $column) use ($id, $Project) {
                            $column->append($this->form_action($id, $Project));
                        });
                    });
                });
                $row->column(6, function(Column $column) use ($id, $Project) {
                    $column->row(function(Row $row) use ($id, $Project) {
                        $row->column(12, new Show(Tasks::with(['process','user','assinger','actions'])->findOrFail($id), function (Show $show) {
                            $show->field('name', __('Title'));
                            $show->status()->unescape()->as(function () {
                                if(!$this->process) return;
                                return '<span class="label" style="background-color:'.$this->process->color.'">'.$this->process->name.'</span>';
                            });
                            $show->assignee()->unescape()->as(function () {
                                if(!$this->assinger) return;
                                return '<img src="'.$this->assinger->avatar.'" class="user-avatar-border" alt="User Image"><span>'.$this->assinger->name.'</span>';
                            });
                            $show->field('content', __('Content'));

                        }));

                    });
                });
            });
        });
    }

    public static function actions(Tasks $tasks, Project $project){
        $actions = $tasks->actions;
        return view('task-manager::actions', compact('actions','project', 'tasks'));
    }

    public function destroy($project, $id)
    {
        Log::info('Start delete task',[$id]);
        collect(explode(',', $id))->filter()->each(function ($id) {
            $task = Tasks::find($id);
            if(!$task) {
                Log::info('Not found task',[$id]);
            }
            TmTaskDeleted::dispatch($task);
        });
        return $this->form()->destroy($id);
    }

    public function title()
    {
        $Project = Project::find(request()->project);
        $Admin = Admin::user();
        return 'Project: '.$Project->name;
    }

    protected function grid()
    {
        $Project = Project::find(request()->project);
        if(!$Project) {
            abort(404);
        }

        $grid = new Grid(new Tasks());

        $grid->model()->with(['project','assinger','process','user'])->where('tm_project_id',$Project->id)->orderBy('updated_at', 'desc');

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'))->display(function($value) use ($Project) {
            return '<a href="'.route('task-manager.tasks.show',['project'=>$Project->id,'task' => $this->id]).'">'.$value.'</a>';
        });
        $grid->column('content', __('Content'))->display(function($value){
            return Str::limit($value,20);
        });
        $grid->column('status', __('Status'))->display(function(){
            if($this->process){
                return '<span class="label" style="background-color:'.$this->process->color.'">'.$this->process->name.'</span>';
            }
        });
        $grid->column('assignee', __('Assignee'))->display(function(){
            if($this->assinger){
                return '<img src="'.$this->assinger->avatar.'" class="user-avatar-border" alt="User Image"><span>'.$this->assinger->name.'</span>';
            }
        });
        $grid->column('created_by', __('Created by'))->display(function(){
            return '<img src="'.$this->user->avatar.'" class="user-avatar-border" alt="User Image"><span>'.$this->user->name.'</span>';
        });
        $grid->column('updated_at', __('Updated at'))->display(function($value){
            return Carbon::parse($value)->format('Y-m-d H:m:s');
        });

        $grid->tools(function (Grid\Tools $tools) {
            $Comback = new Comeback();
            $Comback->setHref(route('task-manager.project.index'));
            $tools->append($Comback);
        });

        return $grid;
    }

    protected function form()
    {
        $Project = Project::find(request()->project);
        $Admin = Admin::user();
        if(!$Project) {
            abort(404);
        }

        $form = new Form(new Tasks());
        $form->text('name', __('Name'))->rules('required');
        $form->select('tm_process_id', __('Process'))->options(function() use ($Project) {
            return $Project->process()->get()->pluck('name','id');
        })->rules('required');

        $form->select('assigner_id', __('Assignee'))->options(function() {
            return Administrator::get()->pluck('name','id')->toArray();
        })->rules('required');
        $form->textarea('content', __('Content'));

        $form->tools(function (Form\Tools $tools) {

        });
        $form->submitted(function (Form $form) {
        });

        $form->saving(function () use ($form, $Project, $Admin) {
            $form->model()->tm_project_id = $Project->id;
            $form->model()->user_id = $Admin->id;
        });

        $form->saved(function (Form $form) use ($Project) {
        });

        return $form;
    }

    protected function form_action($id, Project $Project)
    {
        $form = new Form(new Actions());
        $form->setTitle(__('New comment'));
        $form->setAction(route('task-manager.actions'));

        $form->hidden('project_id')->default($Project->id);
        $form->hidden('task_id')->default($id);
        $form->textarea('comment', __('Comment'))->rules('required');

        $form->select('tm_process_id', __('Process'))->options(function() use ($Project) {
            return $Project->process()->get()->pluck('name','id');
        })->rules('required');

        $form->select('assigner_id', __('Assignee'))->options(function() {
            return Administrator::get()->pluck('name','id')->toArray();
        })->rules('required');

        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();
            $tools->disableDelete();
            $tools->disableView();
        });
        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });

        return $form;
    }
}
