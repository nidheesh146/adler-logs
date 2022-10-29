<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use Redirect;
use Picqer;
class LabelController extends Controller
{
    public function batchcardSearch(Request $request)
    {
        $string =[];
       
        $batchcard = DB::table('batchcard_batchcard')
                        ->leftJoin('product_product','batchcard_batchcard.product_id','=', 'product_product.id')
                        ->select('batchcard_batchcard.id', 'batch_no')
                        ->where('batchcard_batchcard.batch_no','LIKE','%'.$request->q.'%')
                        //->where('product_product.is_sterile','=', 1)
                        ->get();
        //print_r($batchcard);exit;
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

    public function instrumentLabel()
    {
        $title = "Create Instrument Label";
        return view('pages/label/label',compact('title'));
    }

    public function nonSterileProductLabel()
    {
        return view('pages/label/label');
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
        $per_pack_quantity = $request->per_pack_quantity;
        $manufacture_date = $request->manufacturing_date;
        $sterilization_expiry_date = $request->sterilization_expiry_date;
        $batchcard_data = DB::table('batchcard_batchcard')
                            ->select('batchcard_batchcard.*', 'product_product.label_format_number', 'product_product.sku_code','product_product.discription as discription','product_product.sterilization_type' )
                            ->leftJoin('product_product','batchcard_batchcard.product_id','=', 'product_product.id')
                            ->where('batchcard_batchcard.id','=',$batcard_no)
                            ->first();

        return view('pages/label/patient-label-print', compact('batchcard_data','no_of_label', 'lot_no', 'manufacture_date', 'sterilization_expiry_date', 'per_pack_quantity'));
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
                            ->select('batchcard_batchcard.batch_no','batchcard_batchcard.product_id', 'product_product.sku_code','product_product.mrp', 'product_product.label_format_number', 'product_product.drug_license_number', 'batchcard_batchcard.start_date')                        
                            ->leftJoin('product_product','batchcard_batchcard.product_id','=', 'product_product.id')
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

    public function generateSterilizationProductLabel(Request $request)
    {
        $batcard_no = $request->batchcard_no;
        $no_of_label = $request->no_of_label;
        $lot_no = $request->sterilization_lot_no;
        $manufacture_date = $request->manufacturing_date;
        $sterilization_expiry_date = $request->sterilization_expiry_date;
        $batchcard_data = DB::table('batchcard_batchcard')
                            ->select('batchcard_batchcard.*', 'product_product.*')
                            ->leftJoin('product_product','batchcard_batchcard.product_id','=', 'product_product.id')
                            ->where('batchcard_batchcard.id','=',$batcard_no)
                            ->first();
        $color =[0,0,0];
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128, 1,40, $color);
        $gs1_code_barcode = $generator->getBarcode($batchcard_data->gs1_code, $generator::TYPE_CODE_128, 1,70, $color );

        return view('pages/label/sterilization-label-print', compact('batchcard_data','no_of_label', 'lot_no','sku_code_barcode','gs1_code_barcode', 'manufacture_date','sterilization_expiry_date'));
    }
    public function generateNonSterileProductLabel(Request $request) {
        $batcard_no = $request->batchcard_no;
        $no_of_label = $request->no_of_label;
        $manufacturing_date = $request->manufacturing_date;
        $per_pack_quantity = $request->per_pack_quantity;
        $batchcard_data = DB::table('batchcard_batchcard')
                            ->select('batchcard_batchcard.*', 'product_product.*')
                            ->leftJoin('product_product','batchcard_batchcard.product_id','=', 'product_product.id')
                            ->where('batchcard_batchcard.id','=',$batcard_no)
                            ->first();
        $color =[0,0,0];
        $manf_date_combo = '[11]'.date('Y-m-d',strtotime($request->manufacturing_date));
        $gs1_label_batch_combo ='[01]' .$batchcard_data->gs1_code.'[10]'.$batchcard_data->batch_no;
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128);
        $manf_date_combo_barcode = $generator->getBarcode($manf_date_combo, $generator::TYPE_CODE_128, 1,45, $color);
        $gs1_label_batch_combo_barcode = $generator->getBarcode($gs1_label_batch_combo, $generator::TYPE_CODE_128);
        return view('pages/label/non-sterilization-label-print', compact('batchcard_data','no_of_label','sku_code_barcode', 'gs1_label_batch_combo', 'gs1_label_batch_combo_barcode','manf_date_combo','manf_date_combo_barcode','manufacturing_date','per_pack_quantity' ));
    }

    public function generateInstrumentLabel(Request $request)
    {
        $batcard_no = $request->batchcard_no;
        $no_of_label = $request->no_of_label;
        $per_pack_quantity = $request->per_pack_quantity;
        $manufacturing_date = $request->manufacturing_date;
        $batchcard_data = DB::table('batchcard_batchcard')
                            ->select('batchcard_batchcard.*', 'product_product.*')
                            ->leftJoin('product_product','batchcard_batchcard.product_id','=', 'product_product.id')
                            ->where('batchcard_batchcard.id','=',$batcard_no)
                            ->first();
        $color =[0,0,0];
        $manf_date_combo = '[11]'.date('Y-m-d',strtotime($request->manufacturing_date));
        $gs1_label_batch_combo = '[01]'.$batchcard_data->gs1_code.'[10]'.$batchcard_data->batch_no;
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128);
        $manf_date_combo_barcode = $generator->getBarcode($manf_date_combo, $generator::TYPE_CODE_128, 1,45,$color);
        $gs1_label_batch_combo_barcode = $generator->getBarcode($gs1_label_batch_combo, $generator::TYPE_CODE_128);
        return view('pages/label/instrument-label-print', compact('batchcard_data','no_of_label','sku_code_barcode', 'gs1_label_batch_combo', 'gs1_label_batch_combo_barcode','manf_date_combo','manf_date_combo_barcode','manufacturing_date','per_pack_quantity' ));
    }
}
