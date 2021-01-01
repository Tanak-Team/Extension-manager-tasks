<?php
namespace Tanak\TaskManager\Models;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Process extends Model
{
    use HasFactory;

    protected $table = 'tm_process';

    protected $fillable = [
        'name',
        'color',
        'sort_by'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class,'tm_project_id');
    }

    public function tasks()
    {
        return $this->hasMany(Tasks::class,'tm_process_id');
    }

    protected static function newFactory()
    {
        return \Database\Factories\ProcessFactory::new();
    }
}
