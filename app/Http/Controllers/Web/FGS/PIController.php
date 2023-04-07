<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\FGS\fgs_pi;
use App\Models\FGS\fgs_grs;
use App\Models\FGS\fgs_grs_item_rel;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_pi_item;
use App\Models\FGS\fgs_pi_item_rel;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\product;
class PIController extends Controller
{
    public function __construct()
    {
        $this->fgs_pi = new fgs_pi;
        $this->fgs_grs = new fgs_grs;
        $this->fgs_grs_item_rel = new fgs_grs_item_rel;
        $this->fgs_grs_item = new fgs_grs_item;
        $this->fgs_pi_item = new fgs_pi_item;
        $this->fgs_pi_item_rel = new fgs_pi_item_rel;
        $this->fgs_maa_stock_management = new fgs_maa_stock_management;
        $this->product = new product;
    }
    public function PIList(Request $request)
    {
        $condition =[];
        if($request->pi_number)
        {
            $condition[] = ['fgs_pi.pi_number','like', '%' . $request->pi_number . '%'];
        }
        if($request->customer)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_pi.pi_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_pi.pi_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $pi = fgs_pi::select('fgs_pi.*','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.billing_address','customer_supplier.contact_person','customer_supplier.contact_number')
                ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                ->where($condition)
                ->distinct('fgs_pi.id')
                ->paginate(15);
        return view('pages/FGS/PI/PI-list', compact('pi'));
    }
    public function PIAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['customer'] = ['required'];
            $validation['pi_date'] = ['required'];
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
                $data['pi_number'] = "PI-".$this->year_combo_num_gen(DB::table('fgs_pi')->where('fgs_pi.pi_number', 'LIKE', 'PI-'.$years_combo.'%')->count()); 
                $data['pi_date'] = date('Y-m-d',strtotime($request->pi_date));
                $data['customer_id'] =$request->customer;
                $data['created_by'] = config('user')['user_id'];
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $pi_master=$this->fgs_pi->insert_data($data);
                foreach($request->grs_id as $grs_id)
                {
                    $grs = fgs_grs_item_rel::where('master','=',$grs_id)->get();
                    foreach($grs as $grs_data)
                    {
                        $item['grs_id'] = $grs_data['master'];
                        $item['grs_item_id'] = $grs_data['item'];
                        $item['created_at'] =  date('Y-m-d H:i:s');
                        $pi_item=$this->fgs_pi_item->insert_data($item);
                        if($pi_item && $pi_master ){
                            DB::table('fgs_pi_item_rel')->insert(['master'=>$pi_master,'item'=>$pi_item]);
                        }
                        $grs_item = fgs_grs_item::where('id','=',$grs_data['item'])->first();
                        $stock['product_id'] =$grs_item['product_id'];
                        $stock['pi_item_id'] =$pi_item;
                        $stock['batchcard_id'] =$grs_item['batchcard_id'];
                        $stock['quantity'] =$grs_item['batch_quantity'];
                        $stock['created_at'] =  date('Y-m-d H:i:s');
                        $maa_stock=$this->fgs_maa_stock_management->insert_data($stock);

                    }
                }
                if($pi_master)
                {
                    $request->session()->flash('success', "You have successfully added a PI !");
                    return redirect('fgs/PI-list');
                }
                else
                {
                    $request->session()->flash('error', "PI insertion is failed. Try again... !");
                    return redirect('fgs/PI-add');
                }

            }
            else
            {
                return redirect('fgs/PI-add')->withErrors($validator)->withInput();
            }
        }
        else
        {
            return view('pages/FGS/PI/PI-add');
        }
    }

    public function PIitemlist($pi_id)
    {
        $pi_item = fgs_pi_item_rel::select('fgs_grs.grs_number','product_product.sku_code','product_product.hsn_code','product_product.discription',
        'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code')
                        ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                        ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                        ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                        ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                        ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                        ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->where('fgs_pi_item_rel.master','=', $pi_id)
                        ->where('fgs_grs.status','=',1)
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->paginate(15);
        return view('pages/FGS/PI/PI-item-list',compact('pi_item'));
    }

    public function fetchGRS(Request $request)
    {
        $grs_masters =$this->fgs_grs->get_all_grs_for_pi(['customer_supplier.id'=>$request->customer_id]);
        if(count($grs_masters)>0)
        {
            $data = ' <table class="table table-bordered mg-b-0" id="example1">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width:120px;">GRS Number</th>
                                <th>OEF Number</th>
                                <th>Product category</th>
                                <th>STOCK LOCATION1(DECREASE)</th>
                                <th>STOCK LOCATION2(INCREASE)</th>
                                <th>GRS Date</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">';
            foreach($grs_masters as $grs)
            {
                $data.= '<tr>
                        <td><input type="checkbox" name="grs_id[]" value='.$grs->id.' ></td>
                        <td>'.$grs->grs_number.'</td>
                        <td>'.$grs->oef_number.'</td>
                        <td>'.$grs->category_name.'</td>
                        <td>'.$grs->location_name1.'</td>
                        <td>'.$grs->location_name2.'</td>
                        <td>'.date('d-m-Y', strtotime($grs->grs_date)).'</td>
                </tr>';
            }
            $data.= ' </tbody>
            </table>';
        return $data;
        }
        else 
        return 0;
    }
}
