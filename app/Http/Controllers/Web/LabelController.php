<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use Redirect;
class LabelController extends Controller
{
    public function batchcardSearch(Request $request)
    {
        $string =[];
        $batchcard = DB::table('batchcard_batchcard')
                        ->select('id', 'batch_no')
                        ->where('batch_no','LIKE','%'.$request->q.'%')
                        ->get();
                       // echo count($batchcard);exit;
        if(count($batchcard)>0)
        {
             foreach($batchcard  as $card){
                $string[] = [
                    'id'=>$card->id,
                    'text'=>$card->batch_no
                ];
            }
            return response()->json($string, 200); 
        }
        else 
        {
            return response()->json(['message'=>'batch code is not valid'], 500);
        }
    }

    public function batchcardData($batch_no_id)
    {

        $batchcard_data = DB::table('batchcard_batchcard')
                            ->select('batchcard_batchcard.*', 'product_product.*')
                             ->leftJoin('product_product','batchcard_batchcard.product_id','=', 'product_product.id')
                            ->where('batchcard_batchcard.id','=',$batch_no_id)
                            ->first();
        return response()->json($batchcard_data, 200); 
    }

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

    public function generatePatientLabel(Request $request)
    {
        $batcard_no = $request->batchcard_no;
        $no_of_label = $request->no_of_label;
        $lot_no = $request->sterilization_lot_no;
        $batchcard_data = DB::table('batchcard_batchcard')
                            ->select('batchcard_batchcard.*', 'product_product.label_format_number')
                            ->leftJoin('product_product','batchcard_batchcard.product_id','=', 'product_product.id')
                            ->where('batchcard_batchcard.id','=',$batcard_no)
                            ->first();

        return view('pages/label/patient-label-print', compact('batchcard_data','no_of_label', 'lot_no'));
        //Redirect::away('label/print/patient-label');
    }

    public function getBatchcard($sku_code)
    {
        $batchcard_no = DB::table('product_product')
                            ->leftJoin('batchcard_batchcard','product_product.id','=', 'batchcard_batchcard.product_id')
                            ->where('product_product.sku_code' ,'=', $sku_code)
                            ->pluck('batchcard_batchcard.batch_no')
                            ->first();
        //return $batchcard_no;
        return response()->json($batchcard_no, 200);
    }

    public function generateMRPLabel(Request $request)
    {
        $batcard_no = $request->batchcard_no;
        $no_of_label = $request->no_of_label;
        $sku_code = $request->sku_code;
        $product = DB::table('batchcard_batchcard')
                            ->select('batchcard_batchcard.batch_no','batchcard_batchcard.product_id', 'product_product.sku_code','product_product.mrp', 'product_product.label_format_number', 'product_product.drug_license_number')                        ->leftJoin('product_product','batchcard_batchcard.product_id','=', 'product_product.id')
                            ->where('product_product.sku_code' ,'=', $sku_code)
                            ->where('batchcard_batchcard.batch_no','=',$batcard_no)
                            ->first();
        //print_r($batchcard_data);exit;
        if($product)
        {
            return view('pages/label/mrp-label-print', compact('product','no_of_label'));
        
        } 
        else
        {
            return Redirect::back()->with('error', 'Batch Code & Sku code not matching..');
        }
    }
    public function patient() {
        return view('pages/label/patient-label-print');
    }
}
