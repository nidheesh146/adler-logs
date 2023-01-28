<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Validator;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->User = new User;
    }

    public function profile()
    {
        $users = DB::table('user')
                ->where('user_id', '=', session('user.id'))
                ->get();

       return view('pages/employee/profile',['datas'=>$users]);
    }
    public function updateProfile(Request $req)
    {   
        $req->validate([
            'profile_img' => 'required',
            'employee_id' => 'required',
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => 'required',
            'designation' => 'required',
            'address' => 'required',
            'email' => 'required'
            ]);



        $imageName=$req->image;
        if($req->hasFile('pro_pic')){
            $image=$req->pro_pic;
             $imageName=time().'.'.$image->getClientOriginalExtension();
             $req->pro_pic->move('profile_img',$imageName);
        }

        $users = DB::table('user')
                     ->where('user_id', '=', session('user.id'))
                    //  ->update(['employee_id' => $req->employee_id , 'f_name' => $req->f_name , 'l_name' => $req->l_name , 'phone' => $req->phone , 
                    //  'designation' => $req->designation , 'address' => $req->address , 'email' => $req->email]);
                     
                    ->update(['profile_img'=>$imageName,
                            'employee_id'=> $req->employee_id ,
                            'f_name'=> $req->f_name ,
                            'l_name'=> $req->l_name ,
                            'phone'=> $req->phone ,
                            'designation'=> $req->designation ,
                            'address'=> $req->address ,
                            'email'=> $req->email 

                    
                        // $users->image=$imageName;
                        

                    ]);



  if($req->password!="")
  {
    $req->validate([
        'password' => ['required', 'string','min:8'],
        'c_password' => ['required', 'string','min:8']
   ]);
                     $users = DB::table('user')
                     ->where('user_id', '=', session('user.id'))
                     ->update(['password' =>$this->encrypt($req->password)]);
  }

  
        return redirect('profile')->with('message','Successfully updated!');
    }


//     public function changePassword()
//    {
//    return view('change-password');
//    }

//     public function updatePassword(Request $req)
//     {
//         $req->validate([
//              'password' => ['required', 'string','min:8'],
//              'c_password' => ['required', 'string','min:8']
//         ]);
        
//         User::whereId(auth()->user()->id)->update([
//             'password' => Hash::make($req->c_password)
//         ]);

//         return back()->with("status", "Password changed successfully!");

//     }
}