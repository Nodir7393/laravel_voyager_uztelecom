<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Purchase extends ALL

{
    use HasFactory;
    public $translatable = ['name'];
    use HasTranslations;
    protected $table = 'type_of_purchase';
}

