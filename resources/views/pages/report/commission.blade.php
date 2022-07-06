@extends('layouts.default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
<?php

$type = [1=>'state',2=>'district',3=>'mekhala',4=>'unit',5=>'agent'];
?>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span>Commission & Sale </span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Commission & Sale ( {{request()->date ? date("M-Y",strtotime('01-'.request()->date)) : date('M-Y')}} )
                    
                    <div class="right-button">
                        <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                            <i class="fa fa-download" aria-hidden="true"></i> Download <i
                                class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                        <div class="dropdown-menu">
                        <a href="{{request()->fullUrl()}}{{ (count(request()->all('')) > 0)  ? '&' : '?' }}download=excel" class="dropdown-item">Excel</a>
                
                        </div>
                    </div>
                </h4>
                <div class="table-responsive" style="margin-bottom: 13px;">
                    <table class="table table-bordered mg-b-0">
                        <tbody>
                            <tr>
                                <form >
                                <th scope="row">
                                    <div class="row filter_search" style="margin-left: 0px;">

                                        <div class="col-sm-10 col-md-4 col-lg-4 col-xl-4 row">
    
                                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                <label for="exampleInputEmail1">Month & Year</label>
                                                <input type="text" name="date" 
                                                 class="form-control datepicker"
                                                 value="{{request()->date ? date("m-Y",strtotime('01-'.request()->date)) : date('m-Y')}}"
                                                 placeholder="Month & Year">
                                            </div><!-- form-group -->
                                


                                        </div>

                                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12"
                                                style="padding: 0 0 0px 6px;">
                                                <label style="    width: 100%;">&nbsp;</label>
                                                <button type="submit" class="badge badge-pill badge-primary"
                                                    style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>

                                                  @if(request()->date)
                                                    <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                    style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                  @endif
        
                                            </div>
                                        </div>
                                                        
                                    </div>
                                </th>
                                </form>
                        </tbody>
                    </table>
                </div>
            @include('includes.order-nav')
                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0">
                        <thead>
                            <tr>
                                <th>Organization</th>
                                <th>Commission</th>
                                <th>Sale</th>
                                <th>Total</th>
                                
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $datas)
                                <tr>
                                <th>{{ucfirst($type[$key])}}</th>
                                <th>{{ sprintf("%.3f",$datas['Commission'])}}</th>
                                <th>{{ sprintf("%.3f",$datas['Sale']) }}</th>
                                <th>{{ sprintf("%.3f",$datas['Total'])}}</th>
                                <th> <a href="{{url('commission-view-org/'.$Controller->hashEncode($key))}}?date={{request()->date ? date("m-Y",strtotime('01-'.request()->date)) : date('m-Y')}}" class="badge badge-pill badge-primary"><i class="fas fa-eye"></i> View</a></th>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">

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
                    <script>
                      $('[data-toggle="tooltip"]').tooltip();
                    </script>

@stop
