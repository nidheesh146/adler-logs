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
        $Request['param'] = (!empty($Request['param']) ? $Request['param'] : []);

       try {
            if($Request['Method'] == 'GET'){
                $response = Http::withHeaders([
                    'Authorization' => 'Token ' . session('user')['token'],
                ])->get($Request['URL'],$Request['param']);

            }

            if($Request['Method'] == 'POST'){
                $response = Http::withHeaders([
                    'Authorization' => 'Token ' . session('user')['token'],
                    'Content-Type' => 'application/json'
                ])->withBody(
                    $Request['param'], 'application/json'
                )->post($Request['URL']);
            }

          //  print_r($response->json());die;
            if ($response->status() == 200) {
                if ($response->json()['status'] == 'success') {
                    $Res['response'] = $response->json();
                } else {
                    $Res['error'] = (!empty($response->json()['message'])) ? $response->json()['message'] : $response->json()['reason'];
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
