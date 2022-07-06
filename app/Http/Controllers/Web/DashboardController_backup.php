<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use PDF;

use App\Models\label_name;

class DashboardController extends Controller
{


    public function __construct()
    {
        set_time_limit(300);
        $this->label_name = new label_name;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id=null)
    {
        config(['app.title' => 'Dashboard - KSSP']);
        if ($request->isMethod('post')) {

            $sudrdata['ref'] = $request->ref;
            $sudrdata['lot'] = $request->lot;
            $sudrdata['sterile_type'] = $request->sterile_type;
            $sudrdata['sterile'] = $request->sterile;
            $sudrdata['created_date'] = date('Y-m-d',strtotime($request->c_date));
            $sudrdata['expiry_date'] = date('Y-m-d',strtotime($request->expire_date));

            $sudrdata['qty'] = $request->qty;
            $sudrdata['middle_desc_1'] = $request->middle_disc_1;
            $sudrdata['middle_desc_2'] = $request->middle_disc_2;
            $sudrdata['position'] = $request->position;

            if($request->tool){
                $imageName = time().'.'.$request->tool->extension();  
                $request->tool->move(public_path('img/profile'), $imageName);
                $sudrdata['tool'] = $imageName;
            }
            if($request->barcode1){
                $imageName = time().'.'.$request->barcode1->extension();  
                $request->barcode1->move(public_path('img/profile'), $imageName);
                $sudrdata['barcode1'] = $imageName;
            }
            if($request->barcode2){
                $imageName = time().'.'.$request->barcode2->extension();  
                $request->barcode2->move(public_path('img/profile'), $imageName);
                $sudrdata['barcode2'] = $imageName;
            }
            $sudrdata['dist_hum'] = $request->dist_hum;
            $sudrdata['mfg'] = $request->mfg;
      
            if($id){
                $request->session()->flash('success', 'Label Updated Successfully');
                $this->label_name->update_label_name(['id'=>$id],$sudrdata);
            }else{
                $sudrdata['created_at']  = date('Y-m-d');
                $request->session()->flash('success', 'Label added Successfully');
                $this->label_name->insert($sudrdata);
            }
            return redirect('label/list');
        }

        $data= [];
        if($id){
        $data =  $this->label_name->get_single_data(['id'=>$id]);
        }


        return view('pages/dashboard',compact('id','data'));
    }

    function list(Request $request){
      $data['label_name'] = $this->label_name->get_label_name([]);
      return view('pages/label-list' , compact('data'));

    }

    function download(Request $request,$id){

        $pdf = PDF::loadView('pages/report/print-order-pdf')->setPaper('a4', '');
        $filename = date('Y-M').'-page.pdf';
        // return $pdf->download($filename);
        return $pdf->stream($filename, array("Attachment" => false));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
