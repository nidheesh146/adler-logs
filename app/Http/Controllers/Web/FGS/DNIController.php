<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\FGS\fgs_pi;
use App\Models\FGS\fgs_dni;
use App\Models\FGS\fgs_dni_item_rel;
use App\Models\FGS\fgs_dni_item;
use App\Models\FGS\fgs_pi_item;
use App\Models\FGS\fgs_pi_item_rel;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\FGS\fgs_grs_item;
use App\Models\product;
class DNIController extends Controller
{
    public function __construct()
    {
        $this->fgs_grs_item= new fgs_grs_item;
        $this->fgs_pi = new fgs_pi;
        $this->fgs_dni = new fgs_dni;
        $this->fgs_dni_item_rel = new fgs_dni_item_rel;
        $this->fgs_dni_item = new fgs_dni_item;
        $this->fgs_pi_item = new fgs_pi_item;
        $this->fgs_pi_item_rel = new fgs_pi_item_rel;
        $this->fgs_maa_stock_management = new fgs_maa_stock_management;
        $this->product = new product;
    }
    public function DNIList(Request $request)
    {
        $condition =['fgs_dni.dni_exi'=>'DNI'];
        if($request->dni_number)
        {
            $condition[] = ['fgs_dni.dni_number','like', '%' . $request->dni_number . '%'];
        }
        if($request->customer)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_dni.dni_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_dni.dni_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $dni = $this->fgs_dni->get_all_dni($condition);

        return view('pages/FGS/DNI/DNI-list', compact('dni'));
    }

    public function DNIAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['customer'] = ['required'];
            $validation['dni_date'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                if(date('m')==01 || date('m')==02 || date('m')==03)
                {
                    $years_combo = date('y', strtotime('-1 year')).date('y');
                }
                else
                {
                    $years_combo = date('y').date('y', strtotime('+1 year'));
                }
                $data['dni_number'] = "DNI-".$this->year_combo_num_gen(DB::table('fgs_dni')->where('fgs_dni.dni_number', 'LIKE', 'DNI-'.$years_combo.'%')->count()); 
                $data['dni_date'] = date('Y-m-d',strtotime($request->dni_date));
                $data['customer_id'] =$request->customer;
                $data['dni_exi']= 'DNI';
                $data['created_by'] = config('user')['user_id'];
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $dni_master=$this->fgs_dni->insert_data($data);
                foreach($request->pi_id as $pi_id)
                {
                    $pi = fgs_pi_item_rel::where('master','=',$pi_id)->get();
                    foreach($pi as $pi_data)
                    {
                        $item['pi_id'] = $pi_data['master'];
                        $item['pi_item_id'] = $pi_data['item'];
                        $item['created_at'] =  date('Y-m-d H:i:s');
                        $dni_item=$this->fgs_dni_item->insert_data($item);
                        if($dni_item && $dni_master ){
                            DB::table('fgs_dni_item_rel')->insert(['master'=>$dni_master,'item'=>$dni_item]);
                        }
                        $pi_item = fgs_pi_item::where('id','=',$pi_data['item'])->first();
                        $grs_item = fgs_grs_item::where('id','=',$pi_item['grs_item_id'])->first();
                        $maa_stock =  fgs_maa_stock_management::where('pi_item_id',$pi_item['id'])->first();
                        $stock['quantity'] =0;
                        $maa_stock_management = $this->fgs_maa_stock_management->update_data(['pi_item_id'=>$pi_item['id']],$stock);
                        //$maa_stock=$this->fgs_maa_stock_management->insert_data($stock);

                    }
                }
                if($dni_master)
                {
                    $request->session()->flash('success', "You have successfully added a DMI !");
                    return redirect('fgs/DNI-list');
                }
                else
                {
                    $request->session()->flash('error', "DMI insertion is failed. Try again... !");
                    return redirect('fgs/DNI-add');
                }
            }
        }
        else
        {
            return view('pages/FGS/DNI/DNI-add');
        }
    }


    public function fetchPI(Request $request)
    {
        $pi_masters =$this->fgs_pi->get_all_pi_for_dni(['customer_supplier.id'=>$request->customer_id]);
        if(count($pi_masters)>0)
        {
            $data = ' <table class="table table-bordered mg-b-0" id="example1">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width:120px;">PI Number</th>
                                <th>GRS Numbers</th>
                                <th>PI Date</th>
                                <th>Customer</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">';
            foreach($pi_masters as $pi)
            {
                $grs_numbers = $this->get_grs_numbers($pi->id);
                $data.= '<tr>
                        <td><input type="checkbox" name="pi_id[]" value='.$pi->id.' ></td>
                        <td>'.$pi->pi_number.'</td>
                        <td>';
                foreach($grs_numbers as $grs)
                {
                    $data .= $grs->grs_number.'<br>';
                }
                $data.='</td>
                        <td>'.date('d-m-Y', strtotime($pi->pi_date)).'</td>
                        <td>'.$pi->firm_name.'</td>
                </tr>';
            }
            $data.= ' </tbody>
            </table>';
        return $data;
        }
        else 
        return 0;
    }

    public function get_grs_numbers($pi_id)
    {
        $grs_numbers = fgs_pi_item_rel::leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                                        ->leftJoin('fgs_grs','fgs_grs.id','=', 'fgs_pi_item.grs_id')
                                        ->select('fgs_grs.grs_number')
                                        ->get();
        return $grs_numbers;                                
    }

    public function DNIitemlist($dni_id)
    {
        $dni_items = fgs_dni_item_rel::select('fgs_dni_item.pi_id','fgs_pi.pi_number','fgs_pi.pi_date')
                            ->leftJoin('fgs_dni_item','fgs_dni_item.id','fgs_dni_item_rel.item')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_dni_item.pi_id')
                            ->where('master','=',$dni_id)->get();
        foreach($dni_items as $items)
        {
            $pi_item = fgs_pi_item_rel::select('fgs_grs.grs_number','product_product.sku_code','product_product.hsn_code','product_product.discription',
            'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi.pi_number')
                            ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                            ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                            ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                            ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                            ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                            ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                            ->where('fgs_pi_item_rel.master','=', $items['pi_id'])
                            ->where('fgs_grs.status','=',1)
                            ->orderBy('fgs_grs_item.id','DESC')
                            ->distinct('fgs_grs_item.id')
                            ->get();
            $items['pi_item'] = $pi_item;
        }
       // print_r(json_encode($dni_items));exit;
        return view('pages/FGS/DNI/DNI-item-list',compact('dni_items'));
    }
}
