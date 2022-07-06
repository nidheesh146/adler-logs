@extends('layouts.default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
<?php

$types = [1=>'state',2=>'district',3=>'mekhala',4=>'unit',5=>'agent'];
?>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                @if(config('organization')['type'] == 1)
                <span><a href="{{url('commission-view')}}"> Commission & Sale </a></span>
                @endif
                <span><a href="{{url('commission-view-org/'.$Controller->hashEncode($type))}}?date={{request()->get('date') ? date("m-Y",strtotime('01-'.request()->get('date'))) : date("m-Y")}}"> {{ucfirst($types[$type])}}</a> </span>
                <span> {{$organization->org_number}} - {{$organization->org_name}} </span>
            </div>
                <h4 class="az-content-title" style="font-size: 20px;"> Commission & Sale - {{$organization->org_number}} - {{$organization->org_name}}  ( {{date('M Y')}} )
                    <div class="right-button">
                        <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                            <i class="fa fa-download" aria-hidden="true"></i> Download <i
                                class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                        <div class="dropdown-menu">
                           <a href="{{request()->fullUrl()}}{{ (count(request()->all('')) > 0)  ? '&' : '?' }}download=excel" class="dropdown-item">Excel</a>
                        </div>
                    </div>
                </h4>

            @include('includes.order-nav')


                <div class="row row-sm mg-b-20 mg-lg-b-0">
                  
                    <div class="table-responsive" style="margin-bottom: 13px;">
                        <table class="table table-bordered mg-b-0">
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <form>
                                        <div class="row filter_search" style="margin-left: 0px;">

                                            <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
                                            
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label for="">Subscription ID</label>
                                                    <input type="text" name="subscription" value="{{request()->get('subscription')}}" class="form-control" placeholder="Subscription ID">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label for="">Subscriber ID</label>
                                                    <input type="text"  name="subscriber"  value="{{request()->get('subscriber')}}" class="form-control" placeholder="Subscriber ID">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label for="">Created Org</label>
                                                    <input type="text" name="org" value="{{request()->get('org')}}" class="form-control" placeholder="Created Org">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label for="exampleInputEmail1">Month & Year</label>
                                                    <input type="text" name="date" 
                                                     class="form-control datepicker"
                                                     value="{{request()->date ? request()->date : date('m-Y')}}"
                                                     placeholder="Month & Year">
                                                </div><!-- form-group -->
                                        


                                            </div>

                                            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12"
                                                    style="padding: 0 0 0px 6px;">
                                                    <label style="    width: 100%;">&nbsp;</label>
                                                    <button type="submit" class="badge badge-pill badge-primary"
                                                        style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
    
                                                        @if(count(request()->all('')) > 1)
                                                        <a href="{{url()->current();}}?date={{request()->date}}" class="badge badge-pill badge-warning"
                                                        style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                      @endif
                                                </div>
                                            </div>
                                                                 
                                        </div>
                                        </form>
                                    </th>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="table-responsive">

                    <table class="table table-bordered mg-b-0">
                        
                        <thead>
                            <tr>
                                <th>Subscription ID</th>
                                <th>subscriber ID</th>
                                <th>Created Org</th>
                                <th style="text-align:right;">Sale</th>
                                <th style="text-align:right;">Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['sale_commission'] as $sale_commission)
                                <tr>
                                    <th>{{$sale_commission->subscription_id}}</th>
                                    <th>{{$sale_commission->subscriber_id}}</th>
                                <th>{{$sale_commission->org_number}}</th>
                                <th style="text-align:right;">{{$sale_commission->sales_hide ?  sprintf("%.3f",$sale_commission->sales) : '0.000' }}</th>
                                <td style="text-align:right;">{{ sprintf("%.3f",$sale_commission->commission)}}</td>
                                </tr>
                                @endforeach
             




                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $data['sale_commission']->links() }}
                        </div>
                </div>
            </div>



        </div><!-- az-content-body -->
    </div>
    </div><!-- az-content -->

        <script src="<?= url('') ?>/js/azia.js"></script>
        <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
        <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
        <script>
            $(function () {
              'use strict'
                var date = new Date();
                date.setDate(date.getDate());
                $(".datepicker").datepicker({
                format: " mm-yyyy",
                viewMode: "months",
                minViewMode: "months",
               // startDate: date,
               autoclose:true
                });

                $('.datepicker').mask('99-9999');
            });
          </script>

@stop
