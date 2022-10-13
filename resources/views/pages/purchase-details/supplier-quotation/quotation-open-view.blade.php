@extends('layouts.open-default')
@section('content')
<style>
tbody th{
    background: #9999993b;
}

</style>

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">

            {{-- <div class="az-content-breadcrumb">
                <span><a href="http://localhost/adler/public/inventory/supplier-quotation">SUPPLIER QUOTATION </a></span>
                 <span> <a href="http://localhost/adler/public/inventory/view-supplier-quotation-items/39/51">Supplier Quotation Items</a></span>
                  <span><a> Edit Supplier Quotation Item</a></span>
            </div> --}}

            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                <!-- Add  Supplier quotation item</h4> -->
                Supplier Quotation  -  {{$data['inv_supplier']['vendor_id']}} , {{$data['inv_supplier']['vendor_name']}}
            </h4>
            <!-- <div class="az-dashboard-nav">
                <nav class="nav">
                    <a class="nav-link" href="http://localhost/adler/public/inventory/edit-purchase-reqisition?pr_id=">Supplier Quotation</a>
                    <a class="nav-link  active" >Edit Supplier Quotation item </a>
                    <a class="nav-link  " href=""> </a>
                </nav>

            </div> -->

            <div class="row">

                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">


                

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-home"></i>  Quotation details </label>
                                <div class="form-devider"></div>
                            </div>
                        </div>
                            <table class="table table-bordered mg-b-0">
                                <thead>
                                    <th scope="row">RQ NO:</th>
                                    <th scope="row">Date</th>
                                    <th scope="row">Delivery Schedule</th>
                                </thead>
                                <tbody>
                                  <tr>
                                   <td>{{$data['inv_purchase_req_quotation']['rq_no']}}</td>
                                    <td>{{date('d-m-Y',strtotime($data['inv_purchase_req_quotation']['date']))}}</td>
                                    <td>{{date('d-m-Y',strtotime($data['inv_purchase_req_quotation']['delivery_schedule']))}}</td>
                                  </tr>
                                </tbody>
                              </table><br>




                              <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                        <i class="fas fa-list"></i> Item Details  : </label>
                                    <div class="form-devider"></div>
                                </div>
                            </div>

                
                         
                                <table class="table table-bordered mg-b-0">
                    
                            <tbody class="hr_change">
                                @foreach($data['inv_purchase_req_quotation_item_supp_rel'] as $key => $item)
                                <tr>
                                  
                                    <td colspan="4">

                                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                            {{++$key}})  {{$item['item_code']}} </label>
                                    </td>
                      
                                  </tr>
                                
                                    <tr>
                                        <th scope="row" >Item Code</th>
                                        <td scope="row">{{$item['item_code']}}</td>
                                        <th scope="row">Unit</th>
                                        <td scope="row">{{$item['unit_name']}}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row" >HSN Code</th>
                                        <td scope="row">{{$item['hsn_code']}}</td>
                                        <th scope="row" >Discription</th>
                                        <td scope="row">{{$item['discription']}}</td>
                                    </tr>
                               
                                      @endforeach
                         
                                    </tbody>
                                  </table><br> 
        



















                        
                   
                </div>
            </div>
        </div>
    </div>
    <!-- az-content-body -->
</div>

@stop