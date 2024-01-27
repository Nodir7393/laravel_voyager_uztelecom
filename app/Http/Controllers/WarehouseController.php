<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Warehouse Create
     **/
    public function create(Request $request)
    {
        $data = Warehouse::where('application_id',$request->application_id)->first();
        if($data == null)
        {
            $new = new Warehouse();
            $new->branch_id = $request->branch_id;
            $new->product_id = $request->product_id;
            $new->application_id = $request->application_id;
            $new->count = $request->count;
            $new->save();
        }else{
            $data->branch_id = $request->branch_id;
            $data->product_id = $request->product_id;
            $data->application_id = $request->application_id;
            $data->count = $request->count;
            $data->save();
        }
        return redirect()->back();
    }
}
