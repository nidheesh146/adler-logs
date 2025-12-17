<?php

namespace App\Http\Controllers\Web;

use App\Models\Quality;
use App\Models\batchcard;
use DB;
use Validator;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QualityAnalysisExport;
use App\Exports\InspectionExport;

class QualityController extends Controller
{
    protected $batchcard;

    public function __construct(BatchCard $batchcard)
    {
        $this->batchcard = $batchcard;
        $this->User = new User;
        $this->quality = new Quality;
        
        
    }
    
    public function qualitylist(Request $request)
    {
        $condition=[];
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->sku_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->sku_name) {
            $condition[] = ['product_product.sku_name', 'like', '%' . $request->sku_name . '%'];
        }
       
         $batchcards = $this->batchcard->get_all_batchcard_list_quality($condition);
        return view('pages/quality/qualitylist',compact('batchcards'));
    }

    public function qualitycheck(Request $request,$id)
    {
       // $batchcards = $this->batchcard->find($id);
       $condition = ['batchcard_batchcard.id' => $id];
        $batchcards = $this->batchcard->get_all_batchcard($condition)->first(); 
        if (!$batchcards) {
            return redirect()->back()->with('error', 'Batch card not found');
        }
        $checkStatus = Quality::where('status', '!=', 1)->get();
        $usernames = $this->User->whereIn('role_permission', [1, 14])->pluck('username')->toArray();
     
        return view('pages/quality/quality-check',compact('batchcards','checkStatus','usernames'));
    }
    public function qualityInwardForm(Request $request,$id)
    {
   
        $condition = ['batchcard_batchcard.id' => $id];
        $batchcards = $this->batchcard->get_all_batchcard($condition)->first(); 
        if (!$batchcards) {
            return redirect()->back()->with('error', 'Batch card not found');
        }
        $checkStatus = Quality::where('status', '!=', 1)->get();
        $usernames = $this->User->whereIn('role_permission', [1, 14])->pluck('username')->toArray();
     
        return view('pages/quality/quality-inward-form',compact('batchcards','checkStatus','usernames'));
    }

    public function addinward(Request $request)
{
    if ($request->isMethod('post')) {
        // Validation rules
        $validation = [
            'start_date' => 'required|date',
            'inward_doc_date' => 'required|date',
            'batch_no' => 'required',
            'sku_code' => 'required',
            'description' => 'required',
            'batchcard_inward_qty' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $materialLotNo = $request->input('material_lot_no');
        $manualLotNo = $request->input('manual_material_lot_no');
        
        if (is_array($materialLotNo) && !empty($materialLotNo)) {
            $lotNumbers = implode(',', $materialLotNo);
        } elseif (!empty($manualLotNo)) {
            $lotNumbers = $manualLotNo;
        } else {
            $lotNumbers = null;
        }
        // Find the batch using batch_no
        $batch = $this->batchcard->where('batch_no', $request->batch_no)->first();
       // $material_lot_numbers = is_array($request->material_lot_no) ? implode(',', $request->material_lot_no) : $request->material_lot_no;
        $updated = DB::table('batchcard_batchcard')
    ->where('batch_no', $request->batch_no)
    ->update([
        'inward_doc_date' => date('Y-m-d', strtotime($request->inward_doc_date)),
        'quantity' => $request->batchcard_inward_qty,
        'is_inspected' => '1',
        'multiple_batch' => $lotNumbers ,
    ]);

    if ($updated) {
        $request->session()->flash('success', "Batch record updated successfully.");
    } else {
        $request->session()->flash('error', "No rows affected. Check batch_no.");
    }

        return redirect('quality/qualitylist');
    }
}



   
    public function addquality(Request $request)
    {
       // print_r($_POST);exit;
    if ($request->isMethod('post')) {
        // Validation rules
        $validation = [
            'inward_doc_date' => 'required',
            'batch_no' => 'required',
            'sku_name' => 'required',
            'description' => 'required',
            'batchcard_inward_qty' => 'required',
            'material_lot_no' => 'required',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'inspected_qty' => 'required|numeric',
            'inspector_name' => 'required',
            'accepted_quantity' => 'required|numeric',
           // 'rejected_qty' => 'required|numeric',
            //'rework_quantity' => 'required|numeric',
           // 'accepted_quantity_with_deviation' => 'required|numeric',
            'product_group' => 'required',
            'pending_status' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation);

        if (!$validator->fails()) {
            $inspector_names = is_array($request->inspector_name) ? implode(',', $request->inspector_name) : $request->inspector_name;
            $material_lot_numbers = is_array($request->material_lot_no) ? implode(',', $request->material_lot_no) : $request->material_lot_no;
            $data = [
                'batch_creation_date' => date('Y-m-d', strtotime($request->batch_creation_date)),
                'inward_doc_date' => date('Y-m-d', strtotime($request->inward_doc_date)),
                'batch_no' => $request->batch_no,
                'sku_name' => $request->sku_name,
                'description' => $request->description,
                'batchcard_inward_qty' => $request->batchcard_inward_qty,
                'material_lot_no' =>  $material_lot_numbers,
                'start_date' => date('Y-m-d', strtotime($request->start_date)),
                'start_time' => $request->start_time,
                'end_date' => $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : null,
                'end_time' => $request->end_time,
                'inspected_qty' => $request->inspected_qty,
                'inspector_name' => $inspector_names,
                'accepted_quantity' => $request->accepted_quantity,
                'rejected_qty' => $request->rejected_qty,
                'rework_quantity' => $request->rework_quantity,
                'rework_reason' => $request->rework_reason,
                'remaining_quantity' => $request->remaining_quantity,
                'accepted_quantity_with_deviation' => $request->accepted_quantity_with_deviation,
                'product_group' => $request->product_group,
                'pending_status' => $request->pending_status,
                'remark' => $request->remark,
                'rejected_reason' => $request->rejected_reason ?: null,
                'status' => $request->pending_status == 1 ? 1 : 0,
                'reason_for_deviation' => $request->reason_for_deviation,
                'remaining_reason' => $request->remaining_reason,

            ];
            $qualitycheck = Quality::create($data);

            if ($qualitycheck) {
            if ($request->rework_quantity > 0  && $request->accepted_quantity > 0) {
               
                $newBatchData = [
                    'batch_no' => 'RW-' . $this->batchcardNumberGeneration(),
                    'description' => $request->description, 
                    'quantity' =>$request->rework_quantity,
                    'start_date' =>date('Y-m-d', strtotime($request->start_date)),
                    'inward_doc_date' =>date('Y-m-d', strtotime($request->inward_doc_date)),
                    'target_date' => $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : null,
                    'is_active' =>1, 
                    'product_id' =>$request->product_id,
                    'process_sheet_id' =>$request->process_sheet_id,
                    'created' =>date('Y-m-d H:i:s'),
                    'updated' =>date('Y-m-d H:i:s'),
                ];

                $newBatch = $this->batchcard->create($newBatchData);

                if (!$newBatch) {
                    $request->session()->flash('error', "Failed to create rework batch");
                    return redirect('quality/quality-check');
                }
            }
        }

            $request->session()->flash('success', "You have successfully completed quality check");
            return redirect('quality/inspected-quality-list');
        } else {
            return redirect("quality/quality-check")
                ->withErrors($validator)
                ->withInput();
        }
    }
}
function batchcardNumberGeneration()
{
    $i = 1993;
    $year_char = '';
   
    for ($x = 'A'; $x <= 'Z' && $x !== 'AAA'; $x++) {
        $timestamp = strtotime(date('Y'));
        $current_year = idate('Y', $timestamp);

        if (date('m') == '01' || date('m') == '02' || date('m') == '03') {
            if ($i == ((int)$current_year - 1)) {
                $year_char = $x;
                break;
            }
        } else {
            if ($i == (int)$current_year) {
                $year_char = $x;
                break;
            }
        }
        $i++;
    }

    $m = date('m');
    $mnth_char = chr(64 + (int)$m); // converts month number to A-L (1=A, 2=B,...,12=L)

    $structure = $year_char . $mnth_char;

    // Extract batch numbers that contain this structure, ignoring prefixes like 'RW-'
    $batchNumbers = DB::table('batchcard_batchcard')
        ->select('batch_no')
        ->where('batch_no', 'LIKE', '%' . $structure . '%')
        ->get()
        ->pluck('batch_no')
        ->toArray();

    $maxSerial = 0;

    foreach ($batchNumbers as $batchNo) {
        // Extract the actual structure + 4 digit number using regex
        if (preg_match('/' . $structure . '(\d{4})/', $batchNo, $matches)) {
            $serial = (int)$matches[1];
            if ($serial > $maxSerial) {
                $maxSerial = $serial;
            }
        }
    }

    $nextSerial = $maxSerial + 1;
    $serial_no = str_pad($nextSerial, 4, '0', STR_PAD_LEFT);

    $batch_no = $structure . $serial_no;

    return $batch_no;
}

public function inspectedqualitylist(Request $request)
{
    $condition = [];

    if ($request->batch_no) {
        $condition[] = ['add_quality.batch_no', 'like', '%' . $request->batch_no . '%'];
    }
    if ($request->sku_code) {
        $condition[] = ['add_quality.sku_name', 'like', '%' . $request->sku_code . '%'];
    }
    if ($request->from) {
        $condition[] = ['add_quality.start_date', '>=', date('Y-m-d', strtotime($request->from))];
    }
    if ($request->to) {
        $condition[] = ['add_quality.end_date', '<=', date('Y-m-d', strtotime($request->to))]; 
    }
    if ($request->inspector_name) {
        $condition[] = ['add_quality.inspector_name', 'like', '%' . $request->inspector_name . '%']; 
    }

    $checkedQuality =  $this->quality->get_all_quality_list($condition);

    return view('pages/quality/inspected-quality-list', compact('checkedQuality'));
}


    public function batchcardSearch(Request $request)
    {
        $string = [];

        $batchcard = DB::table('batchcard_batchcard')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->select('batchcard_batchcard.id', 'batch_no',)
            ->where('batchcard_batchcard.batch_no', 'LIKE', '%' . $request->q . '%')
            ->get();

        if (count($batchcard) > 0) {
            foreach ($batchcard  as $card) {
                $string[] = [
                    'id' => $card->batch_no,
                    'text' => $card->batch_no
                ];
            }
            return response()->json($string, 200);
        } else {
            return response()->json(['message' => 'batch code is not valid'], 500);
        }
    }

  public function qualityAnalysisReport(Request $request)
    {
        $query = Quality::query()->where('status', 1);
        if ($request->filled('batch_no')) {
            $query->where('batch_no', $request->batch_no);
        }
        
        if ($request->filled('sku_code')) {
            $query->where('sku_code', 'like', '%' . $request->sku_code . '%');
        }

        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::createFromFormat('Y-m-d', $request->from)->startOfDay();
            $to = Carbon::createFromFormat('Y-m-d', $request->to)->endOfDay();
        
            $query->where(function($q) use ($from, $to) {
                $q->whereBetween('start_date', [$from, $to])
                  ->whereBetween('end_date', [$from, $to]);
            });
        }
    
        if ($request->filled('inspector_name')) {
            $query->where('inspector_name', 'like', '%' . $request->inspector_name . '%');
        }
        $query->groupBy('batch_no');
        $datas = $query->get();
        return Excel::download(new QualityAnalysisExport($datas), 'Quality_Analysis_Report' . date('d-m-Y') . '.xlsx');
    }

    public function inspectedList(Request $request)
{
    $datas = DB::table('batchcard_batchcard as b')
        ->select('b.*', 'p1.*','inv_lot_allocation.lot_number as material_lot_no')
        ->leftJoin('product_product as p1', 'b.product_id', '=', 'p1.id') 
        ->leftJoin('product_productgroup', 'p1.product_group_id', '=', 'product_productgroup.id')
        ->leftJoin('add_quality as aq', 'aq.batch_no', '=', 'b.batch_no')
        ->leftJoin('inv_stock_to_production_item', 'b.id','=','inv_stock_to_production_item.batchcard_id')
        ->leftJoin('inv_stock_to_production_item_rel','inv_stock_to_production_item.id','=','inv_stock_to_production_item_rel.item')
        ->leftjoin('inv_stock_to_production','inv_stock_to_production_item_rel.master','=','inv_stock_to_production.id')
        ->leftJoin('inv_lot_allocation', 'inv_lot_allocation.id','=','inv_stock_to_production.lot_id')
        ->where('b.is_inspected', '1')
        ->where('b.is_active', '1')
        ->whereNull('aq.batch_no') 
        ->get();
    return Excel::download(new InspectionExport($datas), 'Inspection_pending_Report_' . date('d-m-Y') . '.xlsx');
}

  
}