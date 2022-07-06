<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function __construct()
    {

    }
    public function login(Request $request)
    {
        $error = "";
        
        if (session('token')) {
            return redirect("dashboard");
        }

        try {
            $response = Http::post(config('app.ApiURL') . '/user/login/', [
                'username' => 'aswin',
                'password' => 'Fedora@2021',
            ]);
            if ($response->status() == 200) {
                if (!empty($response->json()['success'])) {
                    session(['token' => $response->json()['token']]);
                    return redirect("dashboard");

                } else {
                    $error =  $response->json()['message'];
                }
            } else {
                $error =  " Networking Error: Server is not responding. Please contact System Administrator for assistance.";
            }
        } catch (\Exception$e) {
            $error =  " Networking Error: Server is not responding. Please contact System Administrator for assistance.";

        }

    }
}
