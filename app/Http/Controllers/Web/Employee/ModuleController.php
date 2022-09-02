<?php

namespace App\Http\Controllers\web\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function moduleList()
    {
        return view('pages\employee\module-list');
    }
    public function moduleAdd()
    {

    }
}
