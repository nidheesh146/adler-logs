<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MRNController extends Controller
{
    public function MRNAdd()
    {
       return view('pages/FGS/MRN/MRN-add');
       
    }
    public function MRNitemlist()
    {
        return view('pages/FGS/MRN/MRN-item-list');
    }
    public function MRNitemAdd()
    {
        return view('pages/FGS/MRN/MRN-item-add');
    }
}
