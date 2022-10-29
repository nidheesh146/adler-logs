@extends('layouts.default')
@section('content')
    <div class="az-content az-content-dashboard">
        <br>
        <div class="container" data-select2-id="9">
            <div class="az-content-body" data-select2-id="8">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/supplier-invoice') }}">Material Inwards To Quarantine</a></span>
                    <span>@if(!empty($data['miq'])) Edit @else Add @endif Material Inwards To Quarantine</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">@if(!empty($data['miq'])) Edit @else Add @endif Material Inwards To Quarantine

                </h4>
                @foreach ($errors->all() as $errorr)
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{ $errorr }}
                </div>
               @endforeach 
               @if (Session::get('error'))
               <div class="alert alert-danger " style="width: 100%;">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                   <i class="icon fa fa-check"></i> {{ Session::get('error') }}
               </div>
               @endif              
               @if (Session::get('success'))
               <div class="alert alert-success " style="width: 100%;">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                   <i class="icon fa fa-check"></i> {{ Session::get('success') }}
               </div>
               @endif
                        


                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                        Material Inwards To Quarantine :
                        </label>
                        <div class="form-devider"></div>
                    </div>
                </div>
                <form method="POST" id="commentForm" autocomplete="off" >
                    <div class="row">
                        {{ csrf_field() }}

                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" data-select2-id="7">
                            <label>Invoice number *<span class="spinner-border spinner-button spinner-border-sm"
                                    style="display:none;" role="status" aria-hidden="true"></span></label>
                            @if(!empty($data['miq']))
                            <input type="hidden" name="invoice_number" value="{{$data['miq']->invoice_master_id}}">
                            @endif
                            <select class="form-control invoice_number" name="invoice_number" @if(!empty($data['miq'])) disabled @endif>
                                <!-- <option value="" ></option> -->
                                @if(!empty($data['miq']))
                                    <option value="{{$data['miq']->invoice_master_id}}" selected>{{$data['miq']->invoice_number}}</option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <label>MIQ date *</label>
                            <input type="text" class="form-control datepicker" value="{{ (!empty($data['miq'])) ? date('d-m-Y',strtotime($data['miq']->miq_date)) : date("d-m-Y")}}" name="miq_date" placeholder="MIQ date">
                        </div>

                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <label>Created by: *</label>
                            <select class="form-control user_list" name="created_by">
                            @foreach ($data['users'] as $user)
                                <option value="{{$user->user_id}}"   @if(!empty($data['miq']) && $data['miq']->created_by == $user->user_id) selected  @endif   >{{$user->f_name}} {{$user->l_name}}</option>
                            @endforeach                                                 
                            </select>
                        </div>


                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                    class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    @if(!empty($data['miq'])) Update @else Submit @endif
                            </button>
                        </div>
                    </div>
                </form>
                @if(!empty($data['miq']))
                <div class="data-bindings">
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                Supplier Invoice ({{$data['miq']->invoice_number}})
                                </label>
                            <div class="form-devider"></div>
                        </div>
                    </div>
                    <table class="table table-bordered mg-b-0">    
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>{{$data['miq']->invoice_number}}</th>
                                <th>Invoice Date</th>
                                <th>{{date('d-m-Y',strtotime($data['miq']->invoice_date))}}</th>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <th>{{$data['miq']->vendor_id}}-{{$data['miq']->vendor_name}}</th>
                                <th>Prepared By</th>
                                <th>{{$data['miq']->f_name}} {{$data['miq']->l_name}}</th>
                            </tr>
                            
                        </thead> 
                    </table>
                    <br/>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            Supplier Invoice items</label>
                            <div class="form-devider"></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mg-b-0" id="example1">
                            <thead>
                                <tr>
                                    <th>Item Code</th>
                                    <th>Item Type</th>
                                    <th>LOT No.</th>
                                    <th>Quantity</th>
                                    <th>Stk Kpng Unit</th>
                                    <th>unit Rate</th>
                                    <th>Expiry Control</th>
                                    <th>Expiry Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        <tbody >
                            @if($data['miq_items'])
                            @foreach($data['miq_items'] as $item)
                            <tr>
                                <th>{{$item['item_code']}}</th>
                                <th>{{$item['type_name']}}</th>
                                <th>{{$item['lot_number']}}</th>
                                <th>{{$item['order_qty']}}</th>
                                <th>{{$item['unit_name']}}</th>
                                <th>{{$item['rate']}}</th>
                                <th>{{$item['expiry_control']}}</th>
                                <th>{{$item['expiry_date']}}</th>
                                <th><a class="badge badge-info" style="font-size: 13px;" href="{{url('inventory/MIQ/'.$item['item_id'].'/item')}}"  class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 	</th>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
        <!-- az-content-body -->
    </div>

    <script src="<?=url('');?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>
    <script>
      $(function(){
        'use strict'

        $("#commentForm").validate({
        rules: {
            invoice_number: {
                required: true,
            },
            miq_date:{
                required: true,
            },
            created_by:{
                required: true,
            }

        },
        submitHandler: function(form) {
            $('.spinner-button').show();
            form.submit();
        }
    });


    $('.user_list').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',

      });


        $('.invoice_number').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true,
          ajax: {
          url: "{{ url('inventory/find-invoice-number') }}",
          processResults: function (data) {

            return { results: data };

          }
        }
      }).on('change', function (e) {
        $('.spinner-button').show();

        let res = $(this).select2('data')[0];
        if(res){
          $.get("{{ url('inventory/find-po-number') }}?id="+res.id,function(data){
            $('.data-bindings').html(data);
            $('.spinner-button').hide();
          });
        }else{
          $('.data-bindings').html('');
          $('.spinner-button').hide();
        }
      });
      });

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true,
    endDate: new Date()
    });
    $('.datepicker').mask('99-99-9999');
    </script>


@stop
