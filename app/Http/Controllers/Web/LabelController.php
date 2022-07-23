<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LabelController extends Controller
{
   

    public function instumentLabel()
    {
        $title = "Create Instrument Label";
        return view('pages/label/label',compact('title'));
    }

    public function nonSterileProductLabel()
    {
        $title = "Create Non-Sterile Product Label";
        return view('pages/label/label', compact('title'));
    }
    public function mrplabel()
    {
        return view('pages/label/mrp-label');
    }

    public function sterilizationProductLabel()
    {
        return view('pages/label/sterilization-product-label');
    }
    public function patientLabel()
    {
        $title= "Create Patient Label ";
        return view('pages/label/sterilization-product-label', compact('title'));
    }
}
