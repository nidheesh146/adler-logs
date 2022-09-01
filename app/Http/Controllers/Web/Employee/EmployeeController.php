<?php

namespace App\Http\Controllers\web\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Validator;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->User = new User;
        $this->Department = new Department;
    }
    public function employeeList() {
        $data['users'] = $this->User->all_users($condition=null);
        return view('pages\employee\employee-list',compact('data'));
    }

    public function employeeAdd(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $validation['f_name'] = ['required'];
            $validation['l_name'] = ['required'];
            $validation['employee_code'] = ['required'];
            $validation['department'] = ['required'];
            $validation['designation'] = ['required'];
            $validation['date_of_hire'] = ['required'];
            $validation['email'] = ['required','email','unique:user'];
            $validation['phone'] = ['required'];
            $validation['address'] = ['required'];
            $validation['username'] = ['required'];
            $validation['password'] = ['required'];
            $validation['confirm_password'] = ['required','same:password'];
            $validator = Validator::make($request->all(), $validation);
    
            if(!$validator->errors()->all()){
                $data = [
                        "f_name" => $request->f_name,
                        "l_name"  => $request->l_name,
                        "employee_id"=> $request->employee_code,
                        "department"=> $request->department,
                        "designation"=> $request->designation,
                        "date_of_hire"=> date('Y-m-d',strtotime($request->date_of_hire)),
                        "email"=> $request->email,
                        "phone"  => $request->phone ,
                        "address"=>  $request->address,
                        "username"=> $request->username,
                        "password" => $this->encrypt($request->password), 
                ];
    
                    $this->User->insert_data($data);
                    $request->session()->flash('success',"You have successfully added a employee !");
                    return redirect('employee/list');
    
                }
                if($validator->errors()->all()){
                        return redirect("employee/add")->withErrors($validator)->withInput();
                }
        }
        $department = $this->Department->get_dept($condition=null);
        return view('pages\employee\employee-add',compact('department'));
    }
    public function employeeEdit(Request $request, $id)
    {
        if ($request->isMethod('post')) 
        {
            $validation['f_name'] = ['required'];
            $validation['l_name'] = ['required'];
            $validation['employee_code'] = ['required'];
            $validation['department'] = ['required'];
            $validation['designation'] = ['required'];
            $validation['date_of_hire'] = ['required'];
            $validation['email'] = ['required','email'];
            $validation['phone'] = ['required'];
            $validation['address'] = ['required'];
            $validation['username'] = ['required'];
            $validation['password'] = ['required'];
            $validation['confirm_password'] = ['required','same:password'];
            $validator = Validator::make($request->all(), $validation);
    
            if(!$validator->errors()->all()){
                $data = [
                        "f_name" => $request->f_name,
                        "l_name"  => $request->l_name,
                        "employee_id"=> $request->employee_code,
                        "department"=> $request->department,
                        "designation"=> $request->designation,
                        "date_of_hire"=> date('Y-m-d',strtotime($request->date_of_hire)),
                        "email"=> $request->email,
                        "phone"  => $request->phone ,
                        "address"=>  $request->address,
                        "username"=> $request->username,
                        "password" => $this->encrypt($request->password), 
                ];
    
                    $this->User->update_data(['user_id'=>$id],$data);
                    $request->session()->flash('success',"You have successfully edited  employee data  !");
                    return redirect('employee/list');
    
                }
                if($validator->errors()->all()){
                        return redirect("employee/edit/".$request->user_id)->withErrors($validator)->withInput();
                }
        }
        $user = $this->User->get_user(['user_id'=>$id]);
        //print_r($user);exit;
        $department = $this->Department->get_dept($condition=null);
        return view('pages\employee\employee-add',compact('department','user'));
    }

    public function employeeDelete(Request $request, $id)
    {
        if($id){
            $this->User->update_data(['user_id'=>$id],['status'=>0]);
            $request->session()->flash('success',  "You have successfully deleted Employee !");
        }
        return redirect('employee/list');
    }
}
