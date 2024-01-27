<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test($id){

        $user = Auth::user();
        switch ($user->id) {
            case 2: {                
        Application::where('id', $id)->update(['status' => "В исплонении"]);
            } break;
            case 9: {               
        Application::where('id', $id)->update(['status' => "Исполнено"]);
            } break;

        }

    return redirect()->route('voyager.applications.index');

    }

}
