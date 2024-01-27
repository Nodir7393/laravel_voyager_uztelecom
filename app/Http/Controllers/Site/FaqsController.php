<?php

namespace App\Http\Controllers\Site;

use App\Models\Faq;

class FaqsController extends Controller
{
    public function index(){
        $faqs = Faq::all();
        return view('site.faqs.index', compact('faqs'));

    }
    public function show(Faq $faq){
        return view('site.faqs.show', compact('faq'));
    }
}
