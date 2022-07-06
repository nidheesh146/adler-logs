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
                <span>{{ucfirst($types[$Controller->hashDecode($type)])}} </span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Commission & Sale -  {{ucfirst($types[$Controller->hashDecode($type)])}}  ( {{date("M-Y",strtotime($date.'-01'))}} )
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

                                            <div class="col-sm-10 col-md-8 col-lg-8 col-xl-8 row">
                                            
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label for="exampleInputEmail1">Organization ID</label>
                                                    <input type="text" name="org_id" value="{{request()->get('org_id')}}" class="form-control" placeholder="Organization ID">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label for="exampleInputEmail1">Organization name</label>
                                                    <input type="text" name="name" value="{{request()->get('name')}}" class="form-control" placeholder="Organization name">
                                                </div><!-- form-group -->
                                                {{-- <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label for="exampleInputEmail1">From</label>
                                                <select  class="form-control" name="from">
                                                    <option value="All">All</option>
                                                    <option value="Agent">Agent</option>
                                                    <option value="Suscriber">Suscriber</option>
                                                </select>
                                                </div> --}}
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
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
                                <th>Organization ID</th>
                                <th>Organization Name</th>
                                <th>SALE</th>
                                <th>COMMISSION</th>
                                <th></th>
                            
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <th>{{$item['org_number']}}</th>
                                    <th>{{$item['org_name']}}</th>
                                    <td>{{sprintf("%.3f",$item['sale'])}}</td>
                                    <td>{{sprintf("%.3f",$item['commission'])}}</td>
                                    <td>  <a href="{{url('commission-sale-list/'.$Controller->hashEncode($item['org_id']))}}?date={{request()->date ? date("m-Y",strtotime('01-'.request()->date)) : date('m-Y')}}" class="badge badge-pill badge-primary"><i class="fas fa-eye"></i> View</a></th>
                                </tr>
             
                                @endforeach



                        </tbody>
                    </table>
                    @if(config('organization')['type'] == 1)
                    <div class="box-footer clearfix">
                        {{ $notices->links() }}
                        </div>
                    @endif
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
