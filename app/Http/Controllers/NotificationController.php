<?php

namespace App\Http\Controllers;

use App\Events\Notify;

class NotificationController extends Controller
{
    public static function notify()
    {
        broadcast(new Notify(['test' => 123], '1'))->toOthers();
    }
}
