<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use Redirect;
use Picqer;
use Validator;
use App\Models\label_print_report;
use App\Models\FGS\fgs_grs;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_pi;
use App\Models\FGS\fgs_pi_item;
use App\Models\FGS\fgs_pi_item_rel;
use App\Models\FGS\fgs_dni;
use App\Models\FGS\fgs_dni_item;
use App\Models\FGS\fgs_dni_item_rel;
use App\Models\FGS\delivery_challan_item;
use App\Models\FGS\delivery_challan;
use App\Models\PurchaseDetails\customer_supplier;
use App\Models\batchcard;
use App\Models\product;
use App\Exports\PrintingReport;
use Maatwebsite\Excel\Facades\Excel;

class SampleLabelController extends Controller
{
    public function newaneurysm()
    {
        return view('pages/samplelabel/aneurysm-label');
    }
    public function newaneurysmGenrate(Request $request)
    {
        {
            $batcard_no = $request->batchcard_no;
            $batchcard_data = DB::table('batchcard_batchcard')
                ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
                ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
                ->where('batchcard_batchcard.id', '=', $batcard_no)
                ->first();
    
            $no_of_label = $request->no_of_label;
            $lot_no = $request->sterilization_lot_no;
            $per_pack_quantity = $request->per_pack_quantity;
            $manufacture_date = $request->manufacturing_date;
            $sterilization_expiry_date = $request->sterilization_expiry_date;
    
            return view('pages/samplelabel/aneurysm-label-print', compact('batchcard_data', 'no_of_label', 'lot_no', 'manufacture_date', 'sterilization_expiry_date', 'per_pack_quantity'));
        }
}
public function NewNonsterile()
    {
        return view('pages/samplelabel/new-non-sterile');
    }
    public function NewNonsterileGenrate(Request $request)
    {
        $batcard_no = $request->batchcard_no;
    
        // Fetch the batchcard data and related product information
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->where('batchcard_batchcard.is_active', 1)
            ->first();
        
            if (!$batchcard_data) {
                $request->session()->flash('error', "This batchcard is inactive or does not exist.");
                return redirect('samplelabel/new-non-sterile');
            }
        
    
        $no_of_label = $request->no_of_label;
        $manufacturing_date = $request->manufacturing_date;
        $per_pack_quantity = $request->per_pack_quantity;
    
        $color = [0, 0, 0];
        $manf_date_combo = '[11]' . date('Y-m-d', strtotime($request->manufacturing_date));
        $label_batch_combo = '[10]' . $batchcard_data->batch_no;
    
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128);
        $manf_date_combo_barcode = $generator->getBarcode($manf_date_combo, $generator::TYPE_CODE_128, 1, 55, $color);
        $label_batch_combo_barcode = $generator->getBarcode($label_batch_combo, $generator::TYPE_CODE_128);
    
        return view('pages/samplelabel/new-non-sterile-print', compact('batchcard_data', 'no_of_label', 'sku_code_barcode', 'label_batch_combo', 'label_batch_combo_barcode', 'manf_date_combo', 'manf_date_combo_barcode', 'manufacturing_date', 'per_pack_quantity'));
    
    }
    public function Newsterile()
    {
        return view('pages/samplelabel/new-sterile-label');
    }
public function NewsterileGenrate(Request $request)
{
    $batcard_no = $request->batchcard_no;
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->first();
        if (($batchcard_data->label_format_number != 02) && ($batchcard_data->label_format_number != 04)) {
            $request->session()->flash('error', "This is not a sterilization label batchcard.Try with sterilization label batchcard...");
            return redirect('samplelabel/new-sterile-label');
        }

        $no_of_label = $request->no_of_label;
        $lot_no = $request->sterilization_lot_no;
        $manufacture_date = $request->manufacturing_date;
        $sterilization_expiry_date = $request->sterilization_expiry_date;
        $per_pack_quantity = $request->per_pack_quantity;

        $color = [0, 0, 0];
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128, 1, 40, $color);
        // $gs1_code_barcode = $generator->getBarcode($batchcard_data->gs1_code, $generator::TYPE_CODE_128, 1,70, $color );

        return view('pages/samplelabel/new-sterile-print', compact('batchcard_data', 'no_of_label', 'lot_no', 'sku_code_barcode', 'manufacture_date', 'sterilization_expiry_date', 'per_pack_quantity'));
    }
    public function NewpatientLabel()
    {
        $title = "Create New Patient Label ";
        return view('pages/label/new-sterilization-product-label', compact('title'));
    }
    public function NewgeneratePatientLabel(Request $request)
    {
        // $is_sterile = $this->check_label_type($request->batchcard_no);
        // if($is_sterile==0)
        // {
        //     $request->session()->flash('error', "This is non-sterile product batchcard.Try with sterile product batchcard...");
        //     return redirect('label/patient-label');
        // }
        $batcard_no = $request->batchcard_no;
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->first();
        // if($batchcard_data->label_format_number!=10)
        // {
        //     $request->session()->flash('error', "This is not a patient label batchcard.Try with patient label batchcard...");
        //     return redirect('label/patient-label');
        // }

        $no_of_label = $request->no_of_label;
        $lot_no = $request->sterilization_lot_no;
        $per_pack_quantity = $request->per_pack_quantity;
        $manufacture_date = $request->manufacturing_date;
        $sterilization_expiry_date = $request->sterilization_expiry_date;
        return view('pages/samplelabel/new-patient-print', compact('batchcard_data', 'no_of_label', 'lot_no', 'manufacture_date', 'sterilization_expiry_date', 'per_pack_quantity'));
        //Redirect::away('label/print/patient-label');
    }
    public function InstrumentLabel()
    {
        $title = "Create Instrument Label";
        return view('pages/samplelabel/new-instrument', compact('title'));
    }
    public function GenerateInstrumentLabel(Request $request)
    {
        /* $is_sterile = $this->check_label_type($request->batchcard_no);
        if($is_sterile==1)
        {
            $request->session()->flash('error', "This is sterile product batchcard.Try with non-sterile product batchcard...");
            return redirect('label/instrument-label');
        }*/
        $batcard_no = $request->batchcard_no;
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->first();
        if ($batchcard_data->label_format_number != 17) {
            $request->session()->flash('error', "This is not a instrument label batchcard.Try with instrument label batchcard...");
            return redirect('samplelabel/new-instrument');
        }
        $no_of_label = $request->no_of_label;
        $per_pack_quantity = $request->per_pack_quantity;
        $manufacturing_date = $request->manufacturing_date;

        $color = [0, 0, 0];
        $manf_date_combo = '[11]' . date('Y-m-d', strtotime($request->manufacturing_date));
        // $gs1_label_batch_combo = '[01]'.$batchcard_data->gs1_code.'[10]'.$batchcard_data->batch_no;
        $label_batch_combo = '[10]' . $batchcard_data->batch_no;
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128);
        $manf_date_combo_barcode = $generator->getBarcode($manf_date_combo, $generator::TYPE_CODE_128, 1, 45, $color);
        //$gs1_label_batch_combo_barcode = $generator->getBarcode($gs1_label_batch_combo, $generator::TYPE_CODE_128);
        $label_batch_combo_barcode = $generator->getBarcode($label_batch_combo, $generator::TYPE_CODE_128);
        return view('pages/samplelabel/new-instrument-print', compact('batchcard_data', 'no_of_label', 'sku_code_barcode', 'label_batch_combo', 'label_batch_combo_barcode', 'manf_date_combo', 'manf_date_combo_barcode', 'manufacturing_date', 'per_pack_quantity'));
    }
    public function NonSterileProductLabel()
    {
        $title = "Create Instrument Label";
        return view('pages/samplelabel/flip-non-sterile');
    }
    public function GenerateNonSterile(Request $request)
    {

        // $is_sterile = $this->check_label_type($request->batchcard_no);
        // if($is_sterile==1)
        // {
        //     $request->session()->flash('error', "This is sterile product batchcard.Try with non-sterile product batchcard...");
        //     return redirect('label/non-sterile-product-label');
        // }
        $batcard_no = $request->batchcard_no;
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->first();
        if ($batchcard_data->label_format_number != 16) {
            $request->session()->flash('error', "This is not a non-sterilization label batchcard.Try with non-sterilization label batchcard...");
            return redirect('samplelabel/flip-non-sterile');
        }
        $no_of_label = $request->no_of_label;
        $manufacturing_date = $request->manufacturing_date;
        $per_pack_quantity = $request->per_pack_quantity;

        $color = [0, 0, 0];
        $manf_date_combo = '[11]' . date('Y-m-d', strtotime($request->manufacturing_date));
        // $gs1_label_batch_combo ='[01]' .$batchcard_data->gs1_code.'[10]'.$batchcard_data->batch_no;
        $label_batch_combo = '[10]' . $batchcard_data->batch_no;
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128);
        $manf_date_combo_barcode = $generator->getBarcode($manf_date_combo, $generator::TYPE_CODE_128, 1, 45, $color);
        //$gs1_label_batch_combo_barcode = $generator->getBarcode($gs1_label_batch_combo, $generator::TYPE_CODE_128);
        $label_batch_combo_barcode = $generator->getBarcode($label_batch_combo, $generator::TYPE_CODE_128);
        return view('pages/samplelabel/flip-non-sterile-print', compact('batchcard_data', 'no_of_label', 'sku_code_barcode', 'label_batch_combo', 'label_batch_combo_barcode', 'manf_date_combo', 'manf_date_combo_barcode', 'manufacturing_date', 'per_pack_quantity'));
    }

    public function Patient30Label()
    {
        $title = "Create Patient Label ";
        return view('pages/samplelabel/patient-30-label', compact('title'));
    }

    public function GeneratePatient30Label(Request $request)
    {
        // $is_sterile = $this->check_label_type($request->batchcard_no);
        // if($is_sterile==0)
        // {
        //     $request->session()->flash('error', "This is non-sterile product batchcard.Try with sterile product batchcard...");
        //     return redirect('label/patient-label');
        // }
        $batcard_no = $request->batchcard_no;
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->first();
        // if($batchcard_data->label_format_number!=10)
        // {
        //     $request->session()->flash('error', "This is not a patient label batchcard.Try with patient label batchcard...");
        //     return redirect('label/patient-label');
        // }

        $no_of_label = $request->no_of_label;
        $lot_no = $request->sterilization_lot_no;
        $per_pack_quantity = $request->per_pack_quantity;
        $manufacture_date = $request->manufacturing_date;
        $sterilization_expiry_date = $request->sterilization_expiry_date;
        return view('pages/samplelabel/patient-30-label-print', compact('batchcard_data', 'no_of_label', 'lot_no', 'manufacture_date', 'sterilization_expiry_date', 'per_pack_quantity'));
        //Redirect::away('label/print/patient-label');
    }

    public function SterilizationProductLABLE2()
{
    return view('pages/samplelabel/sterilization-label-2');
}
public function GenerateSterilizationProductLABLE2(Request $request)
{
   //dd('hi');
    // $is_sterile = $this->check_label_type($request->batchcard_no);
    // if($is_sterile==0)
    // {
    //     $request->session()->flash('error', "This is non-sterile product batchcard.Try with sterile product batchcard...");
    //     return redirect('label/sterilization-label');
    // }
    $batcard_no = $request->batchcard_no;
    $batchcard_data = DB::table('batchcard_batchcard')
        ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
        ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
        ->where('batchcard_batchcard.id', '=', $batcard_no)
        ->where('batchcard_batchcard.is_active', 1)
        ->first();

        if (!$batchcard_data) {
            $request->session()->flash('error', "This batchcard is inactive or does not exist.");
            return redirect('samplelabel/sterilization-label-2');
        }

            if ( ($batchcard_data->label_format_number != 21)) {
    // if ( ($batchcard_data->label_format_number != 21 && $batchcard_data->label_format_number != 2)) {
        $request->session()->flash('error', "This is not a sterilization label batchcard.Try with sterilization label batchcard...");
        return redirect('samplelabel/sterilization-label-2');
    }
    // Fetch MRP from price master
    $mrp = DB::table('product_price_master')
    ->where('product_id', $batchcard_data->product_id)
    ->value('mrp');

    $no_of_label = $request->no_of_label;
    $lot_no = $request->sterilization_lot_no;
    $manufacture_date = date('d-m-Y', strtotime($request->manufacturing_date));
    $sterilization_expiry_date = date('d-m-Y', strtotime($request->sterilization_expiry_date));

    $per_pack_quantity = $request->per_pack_quantity;

    $color = [0, 0, 0];
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128, 1, 40, $color);
    // $gs1_code_barcode = $generator->getBarcode($batchcard_data->gs1_code, $generator::TYPE_CODE_128, 1,70, $color );

    return view('pages/samplelabel/sterilization-label-2-print', compact('batchcard_data', 'no_of_label', 'lot_no', 'sku_code_barcode', 'manufacture_date', 'sterilization_expiry_date', 'per_pack_quantity','mrp'));
}

    public function NewNonsterilization()
    {
        return view('pages/samplelabel/new-non-sterilization-label');
    }
   
    public function NewNonsterilizationGenrate(Request $request)
    {

        // $is_sterile = $this->check_label_type($request->batchcard_no);
        // if($is_sterile==1)
        // {
        //     $request->session()->flash('error', "This is sterile product batchcard.Try with non-sterile product batchcard...");
        //     return redirect('label/non-sterile-product-label');
        // }
        $batcard_no = $request->batchcard_no;
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->where('batchcard_batchcard.is_active', 1)
            ->first();
        if (!$batchcard_data) {
            $request->session()->flash('error', "This batchcard is inactive or does not exist.");
            return redirect('samplelabel/non-sterile-product-label2');
        }
        if ($batchcard_data->label_format_number != 19) {
            $request->session()->flash('error', "This is not a non-sterilization label batchcard.Try with non-sterilization label batchcard...");
            return redirect('label/non-sterile-product-label2');
        }

        $mrp = DB::table('product_price_master')
            ->where('product_id', $batchcard_data->product_id)
            ->value('mrp');

        $no_of_label = $request->no_of_label;
        $manufacturing_date = $request->manufacturing_date;
        $per_pack_quantity = $request->per_pack_quantity;

        $color = [0, 0, 0];
        $manf_date_combo = '[11]' . date('Y-m-d', strtotime($request->manufacturing_date));
        // $gs1_label_batch_combo ='[01]' .$batchcard_data->gs1_code.'[10]'.$batchcard_data->batch_no;
        $label_batch_combo = '[10]' . $batchcard_data->batch_no;
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128);
        $manf_date_combo_barcode = $generator->getBarcode($manf_date_combo, $generator::TYPE_CODE_128, 1, 55, $color);
        //$gs1_label_batch_combo_barcode = $generator->getBarcode($gs1_label_batch_combo, $generator::TYPE_CODE_128);
        $label_batch_combo_barcode = $generator->getBarcode($label_batch_combo, $generator::TYPE_CODE_128);
        return view('pages/samplelabel/new-non-sterilization-label-print', compact('batchcard_data', 'no_of_label', 'sku_code_barcode', 'label_batch_combo', 'label_batch_combo_barcode', 'manf_date_combo', 'manf_date_combo_barcode', 'manufacturing_date', 'per_pack_quantity', 'mrp'));

    }

     // public function NewNonsterilizationGenrate(Request $request)
    // {
    //     $batcard_no = $request->batchcard_no;
    
    //     // Fetch the batchcard data and related product information
    //     $batchcard_data = DB::table('batchcard_batchcard')
    //         ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
    //         ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
    //         ->where('batchcard_batchcard.id', '=', $batcard_no)
    //         ->first();
    
    //         $mrp = DB::table('product_price_master')
    //         ->where('product_id', $batchcard_data->product_id)
    //         ->value('mrp');
    
    //     $no_of_label = $request->no_of_label;
    //     $manufacturing_date = $request->manufacturing_date;
    //     $per_pack_quantity = $request->per_pack_quantity;
    
    //     $color = [0, 0, 0];
    //     $manf_date_combo = '[11]' . date('Y-m-d', strtotime($request->manufacturing_date));
    //     $label_batch_combo = '[10]' . $batchcard_data->batch_no;
    
    //     $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    //     $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128);
    //     $manf_date_combo_barcode = $generator->getBarcode($manf_date_combo, $generator::TYPE_CODE_128, 1, 55, $color);
    //     $label_batch_combo_barcode = $generator->getBarcode($label_batch_combo, $generator::TYPE_CODE_128);
    
    //     return view('pages/samplelabel/new-non-sterilization-label-print', compact('batchcard_data', 'no_of_label', 'sku_code_barcode', 'label_batch_combo', 'label_batch_combo_barcode', 'manf_date_combo', 'manf_date_combo_barcode', 'manufacturing_date', 'per_pack_quantity', 'mrp'));
    
    // }

}