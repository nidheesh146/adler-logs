<?php

namespace App\Http\Controllers\Web\fgs;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FGS\fgs_oef;
use App\Models\FGS\fgs_oef_item;
use App\Models\FGS\fgs_oef_item_rel;
use App\Models\FGS\transaction_type;
use App\Models\FGS\order_fulfil;
use App\Models\inventory_gst;
use App\Models\product;
class OEFController extends Controller
{
    public function __construct()
    {
        $this->fgs_oef = new fgs_oef;
        $this->fgs_oef_item = new fgs_oef_item;
        $this->fgs_oef_item_rel = new fgs_oef_item_rel;
        $this->transaction_type = new transaction_type;
        $this->order_fulfil = new order_fulfil;
        $this->inventory_gst = new inventory_gst;
        $this->product = new product;
    }
    public function OEFList(Request $request)
    {
        $condition =[];
        if($request->oef_number)
        {
            $condition[] = ['fgs_oef.oef_number','like', '%' . $request->oef_number . '%'];
        }
        if($request->order_number)
        {
            $condition[] = ['fgs_oef.order_number','like', '%' . $request->order_number . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_oef.oef_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_oef.oef_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $oef = fgs_oef::select('fgs_oef.*','order_fulfil.order_fulfil_type','transaction_type.transaction_name','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.contact_person','customer_supplier.contact_number')
                        ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                        ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                        ->where($condition)
                        ->distinct('fgs_oef.id')
                        ->paginate(15);
        return view('pages/FGS/OEF/OEF-list', compact('oef'));
    }
    public function OEFAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['customer'] = ['required'];
            $validation['order_number'] = ['required'];
            $validation['order_date'] = ['required','date'];
            $validation['oef_date'] = ['required','date'];
            $validation['due_date'] = ['required','date'];
            $validation['order_fulfil'] = ['required'];
            //$validation['transaction_type'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $data['oef_number'] = "OEF-".$this->po_num_gen(DB::table('fgs_oef')->where('fgs_oef.oef_number', 'LIKE', 'OEF')->count(),1); 
                $data['customer_id'] = $request->customer;
                $data['oef_date'] = date('Y-m-d', strtotime($request->oef_date));
                $data['order_number'] = $request->order_number;
                $data['order_date'] = date('Y-m-d', strtotime($request->order_date));
                $data['due_date'] = date('Y-m-d', strtotime($request->due_date));
                $data['order_fulfil'] = $request->order_fulfil;
                $data['transaction_type'] = $request->transaction_type;
                $data['created_by']= config('user')['user_id'];
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $add = $this->fgs_oef->insert_data($data);
                if($add)
                {
                    $request->session()->flash('success', "You have successfully added a OEF !");
                    return redirect('fgs/OEF/item-list/'.$add);
                }
                else
                {
                    $request->session()->flash('error', "OEF insertion is failed. Try again... !");
                    return redirect('fgs/OEF-add');
                }

            }
            else
            {
                return redirect('fgs/OEF-add')->withErrors($validator)->withInput();
            }
        }
        else
        {        
            $transaction_type = transaction_type::get();
            $order_fulfil = order_fulfil::get();
            return view('pages/FGS/OEF/OEF-add', compact('transaction_type','order_fulfil'));
        }
       
    }
    public function OEFproductsearch(Request $request)
    {
        if(!$request->q){
            return response()->json(['message'=>'Product is not valid'], 500); 
        }
        $data =  $this->product->get_product_info_for_oef(strtoupper($request->q));
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'Product is not exist'], 500); 
        }
    }

    public function OEFitemlist(Request $request, $oef_id)
    {
        $condition = ['fgs_oef_item_rel.master' =>$request->oef_id];
        if($request->product)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->product . '%'];
        }
        $items = $this->fgs_oef_item->getItems($condition);
        return view('pages/FGS/OEF/OEF-item-list', compact('oef_id','items'));
    }

    public function OEFitemAdd(Request $request, $oef_id)
    {
        if($request->isMethod('post'))
        {
            $validation['moreItems.*.product'] = ['required'];
            $validation['moreItems.*.quantity'] = ['required'];
            $validation['moreItems.*.discount'] = ['required'];
            $validation['moreItems.*.rate'] = ['required'];
            //$validation['moreItems.*.expiry_date'] = ['required','date'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $mrn_info = fgs_oef::find($request->oef_id);
                foreach ($request->moreItems as $key => $value) 
                {
                    $data = [
                        "product_id" => $value['product'],
                        "quantity" => $value['quantity'],
                        "rate"=>$value['rate'],
                        "gst" => 8,
                        "discount"=>$value['discount'],
                        "created_at" => date('Y-m-d H:i:s')
                    ];
                    $oef_data =[
                        'remarks' => $request->remarks
                    ];
                    
                    $this->fgs_oef_item->insert_data($data,$request->oef_id);
                    $this->fgs_oef->update_data(['id'=>$request->oef_id],$oef_data);
                }
                $request->session()->flash('success',"You have successfully added a OEF item !");
                return redirect('fgs/OEF/item-list/'.$request->oef_id);
            } 
            else
            {
                return redirect('fgs/OEF/add-item/'.$request->oef_id)->withErrors($validator)->withInput();
            }
        }
        else{
            $data['gst'] = $this->inventory_gst->get_gst();
            return view('pages/FGS/OEF/OEF-item-add',compact('data'));
        }
    }
}
