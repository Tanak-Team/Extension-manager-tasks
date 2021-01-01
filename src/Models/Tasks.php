<?php
namespace Tanak\TaskManager\Models;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $table = 'tm_tasks';

    protected $fillable = [
        'name',
        'content',
        'sort_by'
    ];

    public function user()
    {
        return $this->belongsTo(Administrator::class,'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'tm_project_id');
    }

    public function actions()
    {
        return $this->hasMany(Actions::class,'tm_task_id');
    }

    public function actionLast()
    {
        return $this->hasOne(Actions::class, 'tm_task_id')->latest();
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'tm_process_id');
    }

    public function assinger()
    {
        return $this->belongsTo(Administrator::class,'assigner_id');
    }
    
    protected static function newFactory()
    {
        return \Database\Factories\TasksFactory::new();
    }
}
