<?php
namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ShowApplication extends AbstractAction {
    public function getTitle(){

        return  'Просмотр на сайте';
    }
    public function getIcon(){
        return 'voyager-eye';
    }
    public function getPolicy(){
        return 'read';
    }

    public function getAttributes()
    {
        $color = $this->data->leader?"success":"danger";
        return [
            'class' => "btn active btn-sm btn-$color pull-right width-active",
        ];
    }
    public function getDefaultRoute()
    {
        return route('site.applications.show', ['application' => $this->data->id]);
    }
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'applications';
    }
    public function shouldActionDisplayOnRow($row){
        return true;
    }
}
