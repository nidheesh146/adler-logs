<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\product;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\production_stock_management;
use App\Models\FGS\fgs_qurantine_stock_management;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_product_category_new;
use App\Models\FGS\fgs_mtq;
use App\Models\FGS\fgs_mtq_item;
use App\Models\FGS\fgs_mtq_item_rel;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FGSmtqtransactionExport;

class MTQController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_category = new fgs_product_category;
        $this->product = new product;
        $this->fgs_mtq = new fgs_mtq;
        $this->fgs_mtq_item = new fgs_mtq_item;
        $this->fgs_mtq_item_rel = new fgs_mtq_item_rel;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->fgs_qurantine_stock_management = new fgs_qurantine_stock_management;
    }
    public function MTQlist(Request $request)
    {
        $condition = [];
        $condition =[];
        if($request->mtq_no)
        {
            $condition[] = ['fgs_mtq.mtq_number','like', '%' . $request->mtq_no . '%'];
        }
        if($request->ref_number)
        {
            $condition[] = ['fgs_mtq.ref_number','like', '%' . $request->ref_number . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_mtq.mtq_date', '>=', date('Y-m-d', strtotime( $request->from))];
        }
        if ($request->to) {
            $condition[] = ['fgs_mtq.mtq_date', '<=', date('Y-m-d', strtotime( $request->to))];
        
        }
      //dd($request->all());

        // if($request->from)
        // {
        //     $condition[] = ['fgs_mtq.mtq_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        //     $condition[] = ['fgs_mtq.mtq_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        // }
        if ($request->mtq_month) {
            $startOfMonth = date('Y-m-01', strtotime($request->mtq_month));
            $endOfMonth = date('Y-m-t', strtotime($request->mtq_month));
            
            $condition[] = ['fgs_mtq.mtq_date', '>=', $startOfMonth];
            $condition[] = ['fgs_mtq.mtq_date', '<=', $endOfMonth];
        }
        
        $mtq = $this->fgs_mtq->get_all_mtq($condition);
        return view('pages/FGS/MTQ/MTQ-list',compact('mtq'));
       
    }
    public function MTQAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['ref_no'] = ['required'];
            $validation['ref_date'] = ['required','date'];
            $validation['mtq_date'] = ['required','date'];
            $validation['product_category'] = ['required'];
            $validation['stock_location1'] = ['required'];
            $validation['stock_location2'] = ['required'];
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
                $data['mtq_number'] = "MTQ-".$this->year_combo_num_gen(DB::table('fgs_mtq')->where('fgs_mtq.mtq_number', 'LIKE', 'MTQ-'.$years_combo.'%')->count()); 
                $data['mtq_date'] = date('Y-m-d', strtotime($request->mtq_date));
                $data['ref_number'] = $request->ref_no;
                $data['ref_date'] = date('Y-m-d', strtotime($request->ref_date));
                $data['product_category_id'] = $request->product_category;
                $data['new_product_category'] = $request->new_product_category;
                $data['stock_location_id1'] = $request->stock_location1;
                $data['stock_location_id2'] = $request->stock_location2;
                $data['created_by']= config('user')['user_id'];
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $data['remarks'] =$request->remarks;

                $add = $this->fgs_mtq->insert_data($data);
                if($add)
                {
                    $request->session()->flash('success', "You have successfully added a MTQ !");
                    return redirect('fgs/MTQ/item-list/'.$add);
                }
                else
                {
                    $request->session()->flash('error', "MTQ insertion is failed. Try again... !");
                    return redirect('fgs/MTQ-add');
                }

            }
            else
            {
                return redirect('fgs/MTQ-add')->withErrors($validator)->withInput();
            }
        }
        else
        {
            $locations = product_stock_location::get();
            $category = fgs_product_category::get();
            $product_category = fgs_product_category_new::get();
            return view('pages/FGS/MTQ/MTQ-add',compact('locations','category','product_category'));
        }
       
    }
    public function MTQitemlist(Request $request)
    {
        $mtq_id = $request->mtq_id;
        $mtq_info = fgs_mtq::find($request->mtq_id);
        $mtq_number = $mtq_info['mtq_number'];
        $condition = ['fgs_mtq_item_rel.master' =>$request->mtq_id];
        if($request->product)
        {
            $condition[] = ['fgs_item_master.sku_code','like', '%' . $request->product . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_mtq_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_mtq_item.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $items = $this->fgs_mtq_item->getMTQItems($condition);
        return view('pages/FGS/MTQ/MTQ-item-list',compact('mtq_id','items','mtq_number'));
    }
    public function MTQitemAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['moreItems.*.product'] = ['required'];
            $validation['moreItems.*.batch_no'] = ['required'];
            $validation['moreItems.*.qty'] = ['required'];
         $validation['moreItems.*.manufacturing_date'] = ['required', 'date'];
            //$validation['moreItems.*.expiry_date'] = ['required','date'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {
                $mtq_info = fgs_mtq::find($request->mtq_id);
                // print_r($mtq_info);exit;
                foreach ($request->moreItems as $key => $value) {
                    // dd($value['manufacturing_date']);
                    if ($value['expiry_date'] != 'N.A')
                        $expiry_date = date('Y-m-d', strtotime($value['expiry_date']));
                    else
                        $expiry_date = '';
                    $data = [
                        "product_id" => $value['product'],
                        "batchcard_id" => $value['batch_no'],
                        "quantity" => $value['qty'],
                        "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        "expiry_date" => $expiry_date,
                        "created_at" => date('Y-m-d H:i:s'),
                        // 'remarks' =>$value['remarks'] 
                    ];
                    // $mtq_data = [
                        
                    // ];
                    $stock = [
                        "product_id" => $value['product'],
                        "batchcard_id" => $value['batch_no'],
                        "quantity" => $value['qty'],
                        //"stock_location_id"=>$mrn_info['stock_location'],
                        "quantity" => $value['qty'],
                        "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        "expiry_date" => $expiry_date,
                    ];
                    $this->fgs_mtq_item->insert_data($data, $request->mtq_id);
                    // $this->fgs_mtq->update_data(['id' => $request->mtq_id], $mtq_data);
                    $qurantine_stock = fgs_qurantine_stock_management::where('product_id', '=', $value['product'])->where('batchcard_id', '=', $value['batch_no'])->first();
                    if ($qurantine_stock) {
                        $qurantine_stock_update = $qurantine_stock['quantity'] + $value['qty'];
                        $this->fgs_qurantine_stock_management->update_data(['id' => $qurantine_stock['id']], ['quantity' => $qurantine_stock_update]);
                    } else {
                        $this->fgs_qurantine_stock_management->insert_data($stock);
                    }


                    $production_stock = fgs_product_stock_management::where('product_id', '=', $value['product'])
                        ->where('batchcard_id', '=', $value['batch_no'])
                        ->where('fgs_product_stock_management.stock_location_id', '=', $mtq_info['stock_location_id1'])
                        ->first();
                    $update_stock = $production_stock['quantity'] - $value['qty'];
                    $production_stock = $this->fgs_product_stock_management->update_data(['id' => $production_stock['id']], ['quantity' => $update_stock]);
                }
                $request->session()->flash('success', "You have successfully added a MTQ item !");
                return redirect('fgs/MTQ/item-list/' . $request->mtq_id);
            } else {
                return redirect('fgs/MTQ/add-item/' . $request->mtq_id)->withErrors($validator)->withInput();
            }
        } else {
            $mtq_id = $request->mtq_id;
            return view('pages/FGS/MTQ/MTQ-item-add', compact('mtq_id'));
        }
    }
    public function fetchProductBatchCardsforMTQ(Request $request)
    {
       // echo $request->mtq_id;
        $fgs_mtq = fgs_mtq::find($request->mtq_id);
        $batchcards = fgs_product_stock_management::select('batchcard_batchcard.batch_no','fgs_product_stock_management.quantity','batchcard_batchcard.id as batch_id',
        'fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date')
                                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id')
                                        ->where('fgs_product_stock_management.product_id','=',$request->product_id)
                                        ->where('fgs_product_stock_management.stock_location_id','=',$fgs_mtq['stock_location_id1'])
                                        ->where('fgs_product_stock_management.quantity','!=',0)
                                        ->get();
        return $batchcards;
    }

    public function MTQpdf($mtq_id)
    { 
        $data['mtq'] = $this->fgs_mtq->get_single_mtq(['fgs_mtq.id' => $mtq_id]);
        $data['items'] = $this->fgs_mtq_item->get_items(['fgs_mtq_item_rel.master' => $mtq_id]);
        $pdf = PDF::loadView('pages.FGS.MTQ.pdf-view', $data);
        // $pdf->set_paper('A4', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true]);       

        $file_name = "MTQ" . $data['mtq']['firm_name'] . "_" . $data['mtq']['mtq_date'];
        return $pdf->stream($file_name . '.pdf');
    }
    public function mtq_transaction(Request $request)
    {
        $condition = [];
        if ($request->mtq_no) {
            $condition[] = ['fgs_mtq.mtq_number', 'like', '%' . $request->mtq_no . '%'];
        }

        if ($request->item_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->item_code . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_mtq_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        }
        $items = fgs_mtq_item::select(
            'fgs_mtq.*',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'fgs_item_master.hsn_code',
            'fgs_mtq.mtq_number',
            'fgs_mtq.mtq_date',
            'fgs_mtq.created_at as mtq_wef',
            'fgs_mtq_item.id as mtq_item_id',
            'fgs_mtq_item.quantity'
        )
            ->leftJoin('fgs_mtq_item_rel', 'fgs_mtq_item_rel.item', '=', 'fgs_mtq_item.id')
            ->leftJoin('fgs_mtq', 'fgs_mtq.id', '=', 'fgs_mtq_item_rel.master')
            ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mtq_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mtq_item.batchcard_id')
            //->where('fgs_mtq_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            //->where('fgs_mtq_item.status',1)
            //->distinct('fgs_mtq_item.id')
            ->orderBy('fgs_mtq_item.id', 'desc')
            ->paginate(15);

        return view('pages/fgs/MTQ/MTQ-transaction-list', compact('items'));
    }
    public function mtq_transaction_export(Request $request)
    {
        $condition = [];
        if ($request->mtq_no) {
            $condition[] = ['fgs_mtq.mtq_number', 'like', '%' . $request->mtq_no . '%'];
        }

        if ($request->item_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->item_code . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_mtq_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        }
        $items = fgs_mtq_item::select(
            'fgs_mtq.*',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'fgs_item_master.hsn_code',
            'fgs_mtq.mtq_number',
            'fgs_mtq.mtq_date',
            'fgs_mtq.created_at as mtq_wef',
            'fgs_mtq_item.id as mtq_item_id',
            'fgs_mtq_item.quantity',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name'

        )
            ->leftJoin('fgs_mtq_item_rel', 'fgs_mtq_item_rel.item', '=', 'fgs_mtq_item.id')
            ->leftJoin('fgs_mtq', 'fgs_mtq.id', '=', 'fgs_mtq_item_rel.master')
            ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mtq_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mtq_item.batchcard_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_mtq.product_category_id')
            ->leftJoin('fgs_product_category_new','fgs_product_category_new.id','fgs_mtq.new_product_category')

            //->where('fgs_mtq_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            //->where('fgs_mtq_item.status',1)
            ->distinct('fgs_mtq_item.id')
            ->orderBy('fgs_mtq_item.id', 'desc')
            ->get();
        return Excel::download(new FGSmtqtransactionExport($items), 'FGS-MTQ-transaction' . date('d-m-Y') . '.xlsx');
    }

    
}
