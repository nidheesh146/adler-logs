<?php

namespace App\Http\Controllers\web\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
class ModuleController extends Controller
{
    public function __construct()
    {
        $this->Permission = new Permission;
    }
    public function moduleList()
    {
        $modules = $this->Permission->get_modules();
        return view('pages\employee\module-list');
    }
    public function moduleAdd()
    {
        return view('pages\employee\module-add');
    }
}
