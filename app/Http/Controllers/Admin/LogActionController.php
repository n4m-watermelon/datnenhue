<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Activity;
class LogActionController extends Controller
{
    public function getIndex()
    {
        dd(Activity::get());
    }
}
