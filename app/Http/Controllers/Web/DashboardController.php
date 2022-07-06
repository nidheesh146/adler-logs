<?php

namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;


use Illuminate\Http\Request;
use PDF;

use App\Models\label_name;

class DashboardController extends Controller
{


    public function __construct()
    {
        set_time_limit(300);
        $this->label_name = new label_name;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id=null)
    {
        config(['app.title' => 'Dashboard - KSSP']);
        // $response = Http::get('/user/login/');
        // print_r($response->json());




        // $response->json() : array|mixed;
        // $response->object() : object;
        // $response->collect($key = null) : Illuminate\Support\Collection;
        // $response->status() : int;
        // $response->ok() : bool;
        // $response->successful() : bool;
        // $response->redirect(): bool;
        // $response->failed() : bool;
        // $response->serverError() : bool;
        // $response->clientError() : bool;
        // $response->header($header) : string;
        // $response->headers() : array;


       //return view('pages/dashboard',compact('id','data'));
    }



    public function login(Request $request,$id=null)
    {
        config(['app.title' => 'Dashboard - KSSP']);
        $response = Http::get('/user/login/');
        print_r($response->json());




        // $response->json() : array|mixed;
        // $response->object() : object;
        // $response->collect($key = null) : Illuminate\Support\Collection;
        // $response->status() : int;
        // $response->ok() : bool;
        // $response->successful() : bool;
        // $response->redirect(): bool;
        // $response->failed() : bool;
        // $response->serverError() : bool;
        // $response->clientError() : bool;
        // $response->header($header) : string;
        // $response->headers() : array;


       //return view('pages/dashboard',compact('id','data'));
    }








    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
