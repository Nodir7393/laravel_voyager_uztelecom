<?php
namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class  AddSignerAction extends AbstractAction
{
    public function getTitle()
    {

        return "Show Roles";
    }
    public function getIcon()
    {
    }
    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        $color = "success";
        return [
            'class' => "btn active btn-sm btn-$color pull-right width-active",
        ];
    }
    public function getDefaultRoute()
    {
        return route('signers.add', ['id' => $this->data->id]);
    }
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'branches';
    }
    public function shouldActionDisplayOnRow($row)
    {
        return true;
    }
}
