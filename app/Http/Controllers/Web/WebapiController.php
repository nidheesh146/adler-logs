<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class WebapiController extends Controller
{
    public function __construct()
    {

    }
    public function HttpClient($Request)
    {

        $Res['error'] = "";
        $Res['response'] = [];
       try {
            if($Request['Method'] == 'GET'){
                $response = Http::withHeaders([
                    'Authorization' => 'Token ' . session('user')['token'],
                ])->get($Request['URL']);
            }
            if ($response->status() == 200) {
                if (!empty($response->json()['success'])) {
                    $Res['response'] = $response->json();

                } else {
                    $Res['error'] = $response->json()['message'];
                }
            } else {
                $Res['error'] = " Networking Error: Server is not responding. Please contact System Administrator for assistance.";
            }
        } catch (\Exception $e) {

            echo $e;
            $Res['error'] = " Networking Error: Server is not responding. Please contact System Administrator for assistance.";
        }

        return $Res;
    }

}
