<?php
namespace Tanak\TaskManager\Models;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actions extends Model
{
    use HasFactory;

    protected $table = 'tm_actions';

    protected $fillable = [
        'comment',
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(Administrator::class,'user_id');
    }

    public function assinger()
    {
        return $this->belongsTo(Administrator::class,'assigner_id');
    }

    public function task()
    {
        return $this->belongsTo(Tasks::class, 'tm_task_id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'tm_process_id');
    }

    protected static function newFactory()
    {
        return \Database\Factories\ActionsFactory::new();
    }
}
