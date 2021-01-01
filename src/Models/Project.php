<?php
namespace Tanak\TaskManager\Models;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'tm_project';

    protected $fillable = [
        'name',
        'description'
    ];


    public function user()
    {
        return $this->belongsTo(Administrator::class,'user_id');
    }

    public function process()
    {
        return $this->hasMany(Process::class,'tm_project_id');
    }

    public function tasks()
    {
        return $this->hasMany(Tasks::class,'tm_project_id');
    }

    protected static function newFactory()
    {
        return \Database\Factories\ProjectFactory::new();
    }
}
