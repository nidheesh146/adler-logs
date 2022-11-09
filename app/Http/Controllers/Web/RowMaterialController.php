<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\PurchaseDetails\inv_supplier_itemrate;
use DB;
use Validator;
class RowMaterialController extends Controller
{
    public function __construct()
    {
        $this->User = new User;
        $this->inventory_rawmaterial = new inventory_rawmaterial;
        $this->inv_supplier_itemrate = new inv_supplier_itemrate;
        
    }
    public function materialList(Request $request)
    {
        $condition = [];
        if($request->item_code){
            $condition[]=['inventory_rawmaterial.item_code','LIKE','%'.$request->item_code.'%'];
        }
        if($request->type1){
            $condition[]=['inv_item_type.type_name','LIKE','%'.$request->type1.'%'];
        }
        if($request->type2){
            $condition[]=['inv_item_type_2.type_name','LIKE','%'.$request->type2.'%'];
        }
        if($request->origin){
            $condition[]=['inventory_rawmaterial.item_origin','LIKE','%'.$request->origin.'%'];
        }
        $data['materials']=$this->inventory_rawmaterial->getData($condition);
        return view('pages/row-material/material-list',compact('data'));
    }

    public function materialAdd(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $validation['item_code'] = ['required'];
            $validation['item_type1'] = ['required'];
            $validation['item_type2'] = ['required'];
            $validation['item_description'] = ['required'];
            $validation['issue_unit'] = ['required'];
            $validation['receipt_unit'] = ['required'];
            $validation['stock_keeping_unit'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) 
            {
                $data['item_code']= $request->item_code;
                $data['item_name']= $request->item_name;
                $data['item_short_name']= $request->item_short_name;
                $data['item_type_id']= $request->item_type1;
                $data['item_type_id_2']= $request->item_type2;
                $data['issue_unit_id']= $request->issue_unit;
                $data['receipt_unit_id']= $request->receipt_unit;
                $data['stock_keeping_unit_id']= $request->stock_keeping_unit;
                $data['stock_type']= $request->stock_type;
                $data['conv_fact_in_ru']= $request->conv_fact_ru;
                $data['conv_fact_in_iu']= $request->conv_fact_iu;
                $data['reorder_level']= $request->reorder_level;
                $data['min_stock']= $request->min_stock_qty;
                $data['max_stock']= $request->max_stock_qty;
                $data['over_stock']= $request->over_stock_level;
                $data['discription']= $request->item_description;
                $data['short_description']= $request->item_short_description;
                $data['item_origin']= $request->item_origin;
                $data['brand_id']= $request->brand;
                $data['company_purchase_rate']= $request->adler_purchase_rate;
                $data['purchase_rate']= $request->purchase_rate;
                $data['sales_rate']= $request->sales_rate;
                $data['ad_sp1']= $request->adsp1;
                $data['ad_sp2']= $request->adsp2;
                $data['path']= $request->path;
                $data['hierarchy_path']= $request->hierarchy_path;
                $data['unit_weight_kgs']= $request->unit_weight;
                $data['revision_record']= $request->revision_record;
                $data['expiry_control']= $request->expiry_control_required;
                $data['method_of_expiry']= $request->method_expiry_control;
                //$data['item_code']= $request->modification_record;
                $data['checker_id']= $request->checker;
                $data['approver_id']= $request->approved;
                $data['is_active']= 1;
                $data['created'] = date('Y-m-d H:i:s');
                $data['updated'] = date('Y-m-d H:i:s');
                $success = $this->inventory_rawmaterial->insertdata($data);
                if($success)
                {
                $request->session()->flash('success', "You have successfully added a row material !");
                return redirect("row-material/list");
                }
                else
                {
                    $request->session()->flash('error', "You have failed to add a row material !");
                    return redirect("row-material/add");
                }
            }
            if ($validator->errors()->all()) {
                return redirect("row-material/add")->withErrors($validator)->withInput();

            }
           
        }   
        $data['type1'] = DB::table('inv_item_type')->select('id','type_name')->where('is_active','=',1)->get();
        $data['type2'] = DB::table('inv_item_type_2')->select('id','type_name')->where('is_active','=',1)->get();
        $data['units'] = DB::table('inventory_rawmaterial_issue_unit')->select('id','unit_name')->where('is_active','=',1)->get();
        $data['users'] = $this->User->get_all_users([['user.status','=',1]]);
        return view('pages/row-material/material-add',compact('data'));
    }

    public function materialDelete(Request $request)
    {
        if($request->id)
        {
            $this->inventory_rawmaterial->updatedata(['id'=>$request->id],['is_active'=>0]);
            $request->session()->flash('success',  "You have successfully deleted a row material !");
        }
       return redirect('row-material/list');
    }

    public function materialEdit(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $validation['item_code'] = ['required'];
            $validation['item_type1'] = ['required'];
            $validation['item_type2'] = ['required'];
            $validation['item_description'] = ['required'];
            $validation['issue_unit'] = ['required'];
            $validation['receipt_unit'] = ['required'];
            $validation['stock_keeping_unit'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) 
            {
                $data['item_code']= $request->item_code;
                $data['item_name']= $request->item_name;
                $data['item_short_name']= $request->item_short_name;
                $data['item_type_id']= $request->item_type1;
                $data['item_type_id_2']= $request->item_type2;
                $data['issue_unit_id']= $request->issue_unit;
                $data['receipt_unit_id']= $request->receipt_unit;
                $data['stock_keeping_unit_id']= $request->stock_keeping_unit;
                $data['stock_type']= $request->stock_type;
                $data['conv_fact_in_ru']= $request->conv_fact_ru;
                $data['conv_fact_in_iu']= $request->conv_fact_iu;
                $data['reorder_level']= $request->reorder_level;
                $data['min_stock']= $request->min_stock_qty;
                $data['max_stock']= $request->max_stock_qty;
                $data['over_stock']= $request->over_stock_level;
                $data['discription']= $request->item_description;
                $data['short_description']= $request->item_short_description;
                $data['item_origin']= $request->item_origin;
                $data['brand_id']= $request->brand;
                $data['company_purchase_rate']= $request->adler_purchase_rate;
                $data['purchase_rate']= $request->purchase_rate;
                $data['sales_rate']= $request->sales_rate;
                $data['ad_sp1']= $request->adsp1;
                $data['ad_sp2']= $request->adsp2;
                $data['path']= $request->path;
                $data['hierarchy_path']= $request->hierarchy_path;
                $data['unit_weight_kgs']= $request->unit_weight;
                $data['revision_record']= $request->revision_record;
                $data['expiry_control']= $request->expiry_control_required;
                $data['method_of_expiry']= $request->method_expiry_control;
                //$data['item_code']= $request->modification_record;
                $data['checker_id']= $request->checker;
                $data['approver_id']= $request->approved;
                $data['is_active']= 1;
                $data['updated'] = date('Y-m-d H:i:s');
                $success = $this->inventory_rawmaterial->updatedata(['id'=>$request->id],$data);
                if($success)
                {
                    $request->session()->flash('success', "You have successfully updated a row material !");
                    return redirect("row-material/list");
                }
                else
                {
                    $request->session()->flash('error', "You have failed to update a row material !");
                    return redirect("row-material/edit/".$request->id);
                }
            }
            if ($validator->errors()->all()) {
                return redirect("row-material/edit/".$request->id)->withErrors($validator)->withInput();

            }
        }
            $edit = $this->inventory_rawmaterial->get_single_data(['id'=>$request->id]);
            $data['type1'] = DB::table('inv_item_type')->select('id','type_name')->where('is_active','=',1)->get();
            $data['type2'] = DB::table('inv_item_type_2')->select('id','type_name')->where('is_active','=',1)->get();
            $data['units'] = DB::table('inventory_rawmaterial_issue_unit')->select('id','unit_name')->where('is_active','=',1)->get();
            $data['users'] = $this->User->get_all_users([['user.status','=',1]]);
            return view('pages/row-material/material-add',compact('data','edit'));
    }

    public function fixedRateList()
    {
        $data['items'] = inv_supplier_itemrate::select('inv_supplier_itemrate.*','inventory_rawmaterial.item_code','inv_supplier.vendor_name','inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst')
                                        ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_supplier_itemrate.item_id')
                                        ->leftJoin('inv_supplier','inv_supplier.id','=','inv_supplier_itemrate.supplier_id')
                                        ->leftJoin('inventory_gst','inventory_gst.id','=','inv_supplier_itemrate.gst')
                                        ->orderBy('inv_supplier_itemrate.id','DESC')
                                        ->paginate(15);
        return view('pages/row-material/fixed-rate-list',compact('data'));
    }
        
       
}
