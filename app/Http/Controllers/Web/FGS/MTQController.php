<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MTQController extends Controller
{
    public function MTQAdd()
    {
       return view('pages/FGS/MTQ/MTQ-add');
       
    }
    public function MTQitemlist()
    {
        return view('pages/FGS/MTQ/MTQ-item-list');
    }
    public function MTQitemAdd()
    {
        return view('pages/FGS/MTQ/MTQ-item-add');
    }
}
