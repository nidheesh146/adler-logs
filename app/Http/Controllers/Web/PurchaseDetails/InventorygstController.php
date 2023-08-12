<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class InventorygstController extends Controller
{
    public function get_data()
    {
        $gst_details = DB::table('inventory_gst')
            ->orderBy('id', 'DESC')
            ->get();
        return view('pages/inventory/gst/inventory-gst', compact('gst_details'));
    }
    public function add_gst_details(Request $request)
    {
        DB::table('inventory_gst')
            ->insert(
                [

                    'igst' => $request->igst,
                    'cgst' => $request->cgst,
                    'sgst' => $request->sgst
                ]
            );
        $gst_details = DB::table('inventory_gst')
            ->orderBy('id', 'DESC')
            ->get();
        return view('pages/inventory/gst/inventory-gst', compact('gst_details'));
    }
    // public function get_itemtype()
    // {
    //     $inv_item=DB::table('inv_item_type_2')->get();
    //     return view('pages/inventory/gst/inventory-itemtype-add',compact('inv_item'));
    // }
    public function Add_itemtype(Request $request)
    {
        // $
        // $inv_item=DB::table('inv_item_type_2')->get();
        // return view('pages/inventory/gst/inventory-itemtype-add',compact('inv_item'));
        $validator = Validator::make($request->all(), [
            'role' => ['required', 'min:1', 'max:20'],

        ]);
        if (!$validator->errors()->all()) {
            $datas['type_name'] = $request->role;

            if (!$request->id) {

                DB::table('inv_item_type_2')->insert($datas);
                $request->session()->flash('success', 'Type has been successfully inserted');
                return redirect("inventory/inventory-itemtype-add");
            }
            DB::table('inv_item_type_2')->where('id', $request->id)->update($datas);

            $request->session()->flash('success', 'Type has been successfully updated');
            return redirect("inventory/inventory-itemtype-add");
        }
        $data['type'] = DB::table('inv_item_type_2')->get();
        if ($request->id) {
            //$edit = $this->Role->get_role($request->role_id);
            $edit = DB::table('inv_item_type_2')->where('id', $request->id)->first();
            //print_r($edit);exit;
            return view('pages/inventory/gst/inventory-itemtype-add', compact('data', 'edit'));
        } else
            return view('pages/inventory/gst/inventory-itemtype-add', compact('data'));
    }

    

    //echo "yes";exit;
    //     $da['label_format_number'] = $excelsheet[30];
    //     $da['drug_license_number'] = $excelsheet[42];
    //     $da['is_sterile'] = (strtolower($excelsheet[31]) == 'yes') ? 1 : 0;
    //     $da['process_sheet_no'] = $excelsheet[16];
    //     $da['groups'] = $excelsheet[49];
    //     $da['family'] =  $excelsheet[28];
    //     $da['brand'] = $excelsheet[27];
    //     $da['is_ce_marked'] = (strtolower($excelsheet[36]) == 'yes') ? 1 : ((strtolower($excelsheet[36]) == 'no') ? 0 : NULL) ;
    //     $da['notified_body_number'] = $excelsheet[37];
    //     $da['is_ear_log_address'] = (strtolower($excelsheet[38]) == 'yes') ? 1 : ((strtolower($excelsheet[38]) == 'no') ? 0 : NULL) ;

    //     $da['is_read_instruction_logo'] = (strtolower($excelsheet[39]) == 'yes') ? 1 : ((strtolower($excelsheet[39]) == 'no') ? 0 : NULL) ;

    //     $da['is_instruction'] = (strtolower($excelsheet[39]) == 'yes') ? 1 : 0;

    //     $da['is_temperature_logo'] = (strtolower($excelsheet[40]) == 'yes') ? 1 : ((strtolower($excelsheet[40]) == 'no') ? 0 : NULL) ;

    //     $da['is_donot_reuse_logo'] = (strtolower($excelsheet[41]) == 'yes') ? 1 : ((strtolower($excelsheet[41]) == 'no') ? 0 : NULL) ;

    //     $da['is_reusable'] = (strtolower($excelsheet[41]) == 'yes') ? 1 : 0;

    //     $da['drug_license_number'] = $excelsheet[42];
    //     //$da['hsn_code'] = $excelsheet[43];
    //     // $da['product_group_id'] = ((($excelsheet[49]) == '') ? NULL : $this->identify_id($excelsheet[49],"product group"));
    //     // $da['brand_details_id'] = ((($excelsheet[27]) == '') ? NULL : $this->identify_id($excelsheet[27],"product brand"));
    //     // $da['product_family_id'] = ((($excelsheet[28]) == '') ? NULL : $this->identify_id($excelsheet[28],"product family"));
    //     if(empty($excelsheet[49]))
    //     $da['product_group_id'] = NULL;
    //     else
    //     $da['product_group_id'] = $this->identify_id($excelsheet[49],"product group");
    //     if($excelsheet[27]=='N. A.' || $excelsheet[27]=='')
    //     $da['brand_details_id'] = NULL;
    //     else
    //     $da['brand_details_id'] = $this->identify_id($excelsheet[27],"product brand");
    //     $da['snn_description']= $excelsheet[29];
    //     $da['revision_record'] = $excelsheet[47];
    //     $da['is_inactive'] = (strtolower($excelsheet[48]) == 'y') ? 1 : ((strtolower($excelsheet[48]) == 'n') ? 0 : NULL);
    //     $da['gs1_code'] = $excelsheet[50];
    //     $da['is_control_sample_applicable'] = (strtolower($excelsheet[51]) == 'yes') ? 1 : ((strtolower($excelsheet[51]) == 'no') ? 0 : NULL);
    //     $da['is_doc_applicability'] =(strtolower($excelsheet[52]) == 'yes') ? 1 : ((strtolower($excelsheet[52]) == 'no') ? 0 : NULL);
    //     $da['is_instruction_for_reuse_symbol'] = $excelsheet[53] ? $excelsheet[53]  : NULL;
    //     $da['is_donot_reuse_symbol'] = $excelsheet[54] ? $excelsheet[54] : NULL ; 
    //     $da['ce_logo'] = $excelsheet[56]  ? $excelsheet[56]  : NULL; 
    //     $da['ad_sp1'] =  $excelsheet[57];
    //     $da['ad_sp2'] = $excelsheet[58];
    //     $da['groups'] = $excelsheet[49] ;
    //     $da['family'] =  $excelsheet[28];
    //     $da['brand'] = $excelsheet[27];
    //     // if($excelsheet[28]=='N. A.' || $excelsheet[28]=='')
    //     // $da['product_family_id'] = NULL;
    //     // else
    //     // $da['product_family_id'] = $this->identify_id($excelsheet[27],"product family");
    //    // $data['product_group_id'] = ((($excelsheet[49]) == '') ? NULL : $this->identify_id($excelsheet[49],"product group")) ;
    //     $res[] = DB::table('product_product')->where('id', $product_product->id)->update($da);
    // }

}
