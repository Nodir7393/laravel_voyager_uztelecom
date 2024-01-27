<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends ALL
{
    use HasFactory;
    protected $guarded = [];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
    public function delete()
    {
        $this->deleted_by = auth()->id();
        return parent::delete();
    }
}
