<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\inventory_rawmaterial;
use App\Models\indirect;
use DB;

class DepartmentController extends Controller
{
    function addDepartment(Request $req)
    {
        $add=Department::orderBy('dept_name','DESC')->paginate(5);
       return view('department',['datas'=>$add,'btnname'=>'ADD','lbl'=>'Create new','action'=>'departmentadd']);
    }
    function addDepartmentadd(Request $req)
    {
        $req->validate([
            'name'=>'required'
        ]);
        $add=new Department;
        $add->dept_name=$req->name;

        $add->save();
        return redirect('department')->with('massege',' Added successfully!');
    }
    function editDepartment($id)
    {
        $add=Department::paginate(5);
        $data=Department::select('id','dept_name')
                       ->where('id',$id)
                       ->get();


                       return view('department',['datas'=>$add,'record'=>$data,'btnname'=>'UPDATE','lbl'=>'Update','action'=>'updatedepartment/'.$id]);
    }
    function update(Request $upreq,$id)
    {
      $data=Department::find($id);
      $data->dept_name=$upreq->name;
      $data->save();
      return redirect('department')->with('massege',' Updated successfully!');

    }

    public function destroy($id)
       {


          $data=Department::find($id);
          $data->delete();
          return redirect('department');

       }
       function getItemData($id){


        $data=inventory_rawmaterial::select('inventory_rawmaterial.discription','inv_mac_item.accepted_quantity')
                                     ->join('inv_mac_item','inventory_rawmaterial.id',"=",'inv_mac_item.item_id')
                                     ->where('inventory_rawmaterial.id',$id)
                                     ->first();

        $datas=$data->discription.'~~'. $data->accepted_quantity ;



        echo $datas;
       }

       ///////////////////////////////////////////////////////////////////////////////////////////////////////
       ///////////////////////////////////////////////////////////////////////////////////////////////////////
       function indirectAdd(Request $req)
       {

           $data=new indirect;
           $data->itemcode=$req->itemCode;
           $data->quantity=$req->itemQty;
           $data->descrption=$req->item_description;
           $data->required=$req->qty_required;

           $data->save();
           return view('pages/inventory/stock/stock-indirect-list')->with('massege',' Added successfully!');
       }
       function listIndirect(Request $req)
       {
           $data=indirect::all();
           $data->sip_number = "SIP3-" .$this->po_num_gen(DB::table('inv_stock_to_production')->where('inv_stock_to_production.sip_number', 'LIKE', 'SIP3%')->count(),1);
          return view('pages/inventory/stock/stock-indirect-list',['datas'=>$data]);
       }



















}
