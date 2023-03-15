<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MINController extends Controller
{
    public function MINAdd()
    {
       return view('pages/FGS/MIN/MIN-add');
       
    }
    public function MINitemlist()
    {
        return view('pages/FGS/MIN/MIN-item-list');
    }
    public function MINitemAdd()
    {
        return view('pages/FGS/MIN/MIN-item-add');
    }
}
