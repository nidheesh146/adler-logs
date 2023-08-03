<?php

namespace App\Http\Controllers\web\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Validator;
use DB;

class ConfigController extends Controller
{
    public function get_configpage($id)
    {
        $data=DB::table('config_setting')
        ->where('id',$id)
        ->first();
        return view('pages.settings.modulesettings',compact('data')); 
    }
  
public function add_configsetting(Request $request)
{
// DB::table('config_setting')
// ->insert([
//     'name'=>$request->setting_name,
//     'value'=>$request->setting_value,
//     're_date'=>$request->rev_date,
//     'rev_number	'=>$request->rev_no,
//     'qtn_head1'=>$request->qt_head1,
//     'qtn_email_id1'=>$request->qt_mail1,
//     'qtn_head2'=>$request->qt_head2,
//     'qtn_email_id2'=>$request->qt_mail2,
//     'qtn_head3'=>$request->qt_head3,
//     'qtn_email_id3'=>$request->qt_mail3,
//     'purchase_head1'=>$request->pur_head1,
//     'pur_email_id1'=>$request->pur_mail1,
//     'purchase_head2'=>$request->pur_head2,
//     'pur_email_id2'=>$request->pur_mail2,
//     'purchase_head3'=>$request->pur_head3,
//     'pur_email_id3'=>$request->pur_mail3,
//     'fgs_head1'=>$request->fgs_head1,
//     'fgs_email_id1'=>$request->fgs_mail1,
//     'fgs_head2'=>$request->fgs_head2,
//     'fgs_email_id2'=>$request->fgs_mail2,
//     'fgs_head3'=>$request->fgs_head3,
//     'fgs_email_id3'=>$request->fgs_mail3,

// ]);

DB::table('config_setting')
->where('id',$request->config_id)
->update([
    'name'=>$request->setting_name,
    'value'=>$request->setting_value,
    're_date'=>$request->rev_date,
    'rev_number'=>$request->rev_no,
    'qtn_head1'=>$request->qt_head1,
    'qtn_email_id1'=>$request->qt_mail1,
    'qtn_head2'=>$request->qt_head2,
    'qtn_email_id2'=>$request->qt_mail2,
    'qtn_head3'=>$request->qt_head3,
    'qtn_email_id3'=>$request->qt_mail3,
    'purchase_head1'=>$request->pur_head1,
    'pur_email_id1'=>$request->pur_mail1,
    'purchase_head2'=>$request->pur_head2,
    'pur_email_id2'=>$request->pur_mail2,
    'purchase_head3'=>$request->pur_head3,
    'pur_email_id3'=>$request->pur_mail3,
    'fgs_head1'=>$request->fgs_head1,
    'fgs_email_id1'=>$request->fgs_mail1,
    'fgs_head2'=>$request->fgs_head2,
    'fgs_email_id2'=>$request->fgs_mail2,
    'fgs_head3'=>$request->fgs_head3,
    'fgs_email_id3'=>$request->fgs_mail3,

]);
$data=DB::table('config_setting')
->where('id',$request->config_id)
->first();
$request->session()->flash('success', "You have successfully added  !");
return view('pages.settings.modulesettings',compact('data')); 
}
public function get_config_list(Request $request)
{
    $condition = [];
        if($request)
        {
            if ($request->sip_number) {
                $condition[] = ['config_setting.name','like', '%' . $request->sip_number . '%'];
            }
            // if ($request->lot_number) {
            //     $condition[] = ['inv_lot_allocation.lot_number','like', '%' . $request->lot_number . '%'];
            // }
            // if ($request->item_code) {
            //     $condition[] = ['inventory_rawmaterial.item_code','like', '%' . $request->item_code . '%'];
            // }
            // if($request->supplier)
            // {
            //     $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            // }
           
        }
    $data=DB::table('config_setting')->where('status',1)->where($condition)->paginate(20);
    return view('pages.settings.modulesettinglist',compact('data')); 
}
}

    

