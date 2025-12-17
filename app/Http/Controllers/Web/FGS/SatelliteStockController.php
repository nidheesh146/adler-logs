<?php

namespace App\Http\Controllers\Web\FGS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
use App\Models\FGS\product_stock_location;

class SatelliteStockController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
    }
    public function locationList(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $validator = Validator::make($request->all(), [
                        'location_name' => ['required', 'min:1', 'max:20'],
                        // 'zone' => ['required'],
                        // 'description' => ['required', 'min:1', 'max:115'],
                    ]);
            if (!$validator->errors()->all()) 
            {
                $datas['location_name'] = $request->location_name;
                // $datas['zone'] = $request->zone;
                // $datas['description'] = $request->description;
                $datas['created_by'] = config('user')['user_id'];
                $datas['is_satellite_location'] = 1;
                if (!$request->location_id) 
                {
                    
                    $this->product_stock_location->insert_location($datas);
                    $request->session()->flash('success', 'Satellite Stock Location has been successfully inserted');
                    return redirect("fgs/satellite-stock/location");
                }
                else
                {
                    //echo $request->location_id;exit;
                    $this->product_stock_location->update_location($datas, ['id'=>$request->location_id]);
                    $request->session()->flash('success', 'Satellite stock Location  has been successfully updated');
                    return redirect("fgs/satellite-stock/location");
                }
            }
            else
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        else
        {
            $data['location'] = product_stock_location::where('status',1)->where('is_satellite_location',1)->paginate(10);
            if ($request->location_id) {
                $edit = product_stock_location::find($request->location_id);
                return view('pages/FGS/Satellite-stock/location',compact('data','edit'));
            }
            else
            return view('pages/FGS/Satellite-stock/location',compact('data'));
        }
    }
    public function deleteLocation(Request $request)
    {
        $data['status'] = 0;
        $delete = $this->product_stock_location->update_location($data, ['id'=>$request->location_id]);
        $request->session()->flash('success', 'Satellite stock Location has been successfully deleted');
        return redirect("fgs/satellite-stock/location");
    }
    public function stockList(Request $request)
    {
        $location = product_stock_location::where('status',1)->get();
        return view('pages/FGS/Satellite-stock/stock',compact('location'));
    }
}
