<?php

namespace Tanak\TaskManager\Http\Actions;

use Encore\Admin\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class Comeback extends Action
{
    protected $href ='#';

    public function setHref($val)
    {
        $this->setHref = $val;
    }

    public function getHref()
    {
        return $this->setHref;
    }

    public function html()
    {
        return '<a class="btn btn-sm btn-default import-post" href="'.$this->getHref().'"><i class="fa fa-undo"></i> <span class="hidden-xs">'.__('Back').'</span></a>';
    }

}
