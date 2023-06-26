<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


use Validator;

use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->User = new User;
    }
    public function login(Request $request)
    {
        if (session('user.id')) {
            return redirect("dashboard");
        }
        if ($request->isMethod('post')) {
        $validator = Validator::make($request->all(), [
            'username' => ['required'],
            'password' => ['required'],
        ]);
        if (!$validator->errors()->all()) {
            $data = [
                'username' => $request->username,
                'password' => $this->encrypt($request->password),
            ];
            $user_data = $this->User->login($data);
            print_r($user_data);
            if (!empty($user_data->user_id)) {
                if ($user_data->status == 1) {
                    session(['user.id' => $user_data->user_id]);
                    return redirect("dashboard");
                }else{
                    $validator->errors()->add('Action', 'Your account has been deactivated!');
                }
            } else {
                $validator->errors()->add('auth', 'username or password is wrong!');
                return redirect('/')->withErrors($validator)->withInput();
            }
          }
        }
        return view('layouts/login');
    }
    public function logout()
    {
        session()->flush();
        return redirect("");
    }
    
}


