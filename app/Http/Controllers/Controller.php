<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Hashids\Hashids;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
      
    }
    function encrypt($string){
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
        $secret_iv = '5fgf5HJ5g27'; // user define secret key
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        return   $output = base64_encode($output);
  
    }
    function decrypt($encryption){
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
        $secret_iv = '5fgf5HJ5g27'; // user define secret key
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo      
        $output = openssl_decrypt(base64_decode($encryption), $encrypt_method, $key, 0, $iv);
        return $output;
    }
    function hashEncode($string){
              $hashids = new Hashids('', 0, 'abcdefghijklmnopqrstuvwxyz');
     return   $hashids->encodeHex($string);
    }
    function hashDecode($string){
              $hashids = new Hashids('', 0, 'abcdefghijklmnopqrstuvwxyz');
      return  $numbers = $hashids->decodeHex($string); 
    }
    function num_gen($Number){
        $Number = (($Number + 1) / 9999);
        list($whole, $decimal) = explode('.', $Number);
        if(!$decimal){
            if(date('m')==01 || date('m')==02 || date('m')=='03')
            return date('y',strtotime("-1 year")).date('m')."9999";
            else
            return date('y').date('m')."9999";

        }
        if(date('m')==01 || date('m')==02 || date('m')==03)
        return date('y',strtotime("-1 year")).date('m').substr($decimal, 0, 4);
        else
        return date('y').date('m').substr($decimal, 0, 4);
    }

    function po_num_gen($Number,$type)
    {
        $Number = (($Number + 1) / 999);
        list($whole, $decimal) = explode('.', $Number);
        if(!$decimal){
            if($type==1)
            {
                if(date('m')==01 || date('m')==02 || date('m')==03)
                return date('y', strtotime('-1 year')).date('y').'-'."999";
                else
                return date('y').date('y', strtotime('+1 year')).'-'."999";
            }
            else
            {
                if(date('m')==01 || date('m')==02 || date('m')==03)
                return date('y', strtotime('-1 year')).date('y').'ID-'."999";
                else
                return date('y').date('y', strtotime('+1 year')).'ID-'."999";
            }

        }
        if($type==1)
        {
            if(date('m')==01 || date('m')==02 || date('m')==03)
            return date('y', strtotime('-1 year')).date('y').'-'.substr($decimal, 0, 3);
            else
            return date('y').date('y', strtotime('+1 year')).'-'.substr($decimal, 0, 3);
        }
        else
        {
            if(date('m')==01 || date('m')==02 || date('m')==03)
            return  date('y', strtotime('-1 year')).date('y').'ID-'.substr($decimal, 0, 3);
            else
            return date('y').date('y', strtotime('+1 year')).'ID-'.substr($decimal, 0, 3);
        }
    }

    function wo_num_gen($Number){
        $Number = (($Number + 1) / 999);
        list($whole, $decimal) = explode('.', $Number);
        if(!$decimal){
            if(date('m')==01 || date('m')==02 || date('m')==03)
            return date('y', strtotime('-1 year')).date('y').'-'."999";
            else
            return date('y').date('y', strtotime('+1 year')).'-'."999";
        }
        if(date('m')==01 || date('m')==02 || date('m')==03)
        return date('y', strtotime('-1 year')).date('y').'-'.substr($decimal, 0, 3);
        else
        return date('y').date('y', strtotime('+1 year')).'-'.substr($decimal, 0, 3);
    }

    function lot_num_gen($Number){
        $Number = (($Number + 1) / 999);
        list($whole, $decimal) = explode('.', $Number);
        if(!$decimal){
            // if(date('m')==01 || date('m')==02 || date('m')==03)
            // return "999".date('m').date('y', strtotime('-1 year'));
            // else
            return "999".date('m').date('y');
        }
        // if(date('m')==01 || date('m')==02 || date('m')==03)
        // return substr($decimal, 0, 3).date('m').date('y', strtotime('-1 year'));
        // else
        return substr($decimal, 0, 3).date('m').date('y');
    }

    function ResponseApi($data,$code,$message = null){
        $status = false;
        switch ($code) {
           case 200:
                $message = $message ? $message : "success";
                $status = true;
              break;
           case 400:
                $message = $message ? $message : "Bad Request";
           break;
           case 401:
                $message = $message ? $message : "Unauthorized";
           break;
           case 403:
                $message = $message ? $message : "Forbidden";
           break;
           case 204:
            $message = $message ? $message : "Data not found";
            break;
          }
          
          $response =  [
            "success" => $status ,
            'timestamp'=> time(),
            "code" => $code,
            "message"=> $message,
            "data"=>  $data
           ];
      
          return response($response,$code);
    }
}
