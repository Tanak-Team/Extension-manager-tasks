<?php
namespace Tanak\TaskManager\Models;

use Encore\Admin\Auth\Database\Administrator as DatabaseAdministrator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Administrator extends DatabaseAdministrator
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\AdministratorFactory::new();
    }
}
