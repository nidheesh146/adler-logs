<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use DB;
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
           // echo $e;
            $Res['error'] = " Networking Error: Server is not responding. Please contact System Administrator for assistance.";
        }

        return $Res;
    }
    function insert_user(){
    $name = ['Mangesh','Milind','Pradeep','Nayan','Sachin','Shriniwas',
    'Abhijeeth','Vinayak','Ganesh','Satyawan','Kanchan','Ravindra','Bhushan',
     'Sonali','Dinesh','Mithun'];

foreach( $name as $key => $names){

$data['email']= strtolower($names).'@adler.com';
$data['username']= strtolower($names);
$data['password']= $this->encrypt(strtolower($names).'@123');
$data['f_name']= $names;
$data['phone']= 000000000;
$data['employee_id']= 'EM000'.$key+1;
$data['designation']= 'Admin';
$data['role_permission']= 1;
$data['status']= 1;
$data['admin']= 1;
$data['department']= 1;

DB::table('user')->insert($data);
}



    }
    function insert_dept(){
        $Dept = ['RM Stores','Quality','Purchase','NPD','Administration','HR',
    'Accounts','Manufactoring','FG Stores','Maintenance','Logistics','IT'];
    foreach(  $Dept as $key =>  $Depts){

        DB::table('department')->insert(['dept_name'=>$Depts,'status'=>1]);
    }


    }

}
