<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Exception;
use Illuminate\Http\Request;

class TypeOfPurchase extends Controller
{
    public function edit($id)
    {
       $purchase = Purchase::find($id);
       return empty($purchase) ? throw new Exception("Undefined data with id $id") : view('vendor.voyager.type-of-purchase.edit-add',compact('purchase'));
    }
    public function update(Request $request)
    {
        $purchase = $request->purchase_id == null ? new Purchase() : Purchase::find($request->purchase_id);
        $purchase
            ->setTranslation('name', 'en', "{$request->nameEn}")
            ->setTranslation('name', 'uz', "{$request->nameUz}")
            ->setTranslation('name', 'ru', "{$request->nameRu}")
            ->save();
        return redirect('admin/type-of-purchase');
    }
}
