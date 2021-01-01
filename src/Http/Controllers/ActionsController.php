<?php

namespace Tanak\TaskManager\Http\Controllers;

use Encore\Admin\Facades\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Illuminate\Routing\Controller;
use Tanak\TaskManager\Models\Actions;
use Tanak\TaskManager\Models\Tasks;
use Illuminate\Support\Facades\DB;


class ActionsController extends Controller
{
    public function __invoke(Request $request)
    {

        DB::beginTransaction();
        try{
            $validated = $request->validate([
                'assigner_id' => 'required',
                'tm_process_id' => 'required',
                'comment' => 'required',
            ]);
            $project_id =  $request->project_id;
            $task_id =  $request->task_id;
            $action = new Actions();
            $action->comment = $request->comment;
            $action->tm_task_id = $task_id;
            $action->tm_process_id = $request->tm_process_id;
            $action->assigner_id = $request->assigner_id;
            $action->user_id = Admin::user()->id;
            $action->save();

            $Tasks = Tasks::find($task_id);
            $Tasks->tm_process_id = $request->tm_process_id;
            $Tasks->assigner_id = $request->assigner_id;
            $Tasks->save();
            DB::commit();
        } catch(Exception $e){
            DB::rollBack();
        }
        
        return redirect()->route('task-manager.tasks.show',['project'=>$project_id,'task' => $task_id]);
    }
}