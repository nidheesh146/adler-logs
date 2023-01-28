@extends('layouts.default')
@section('content')
    <div class="az-content az-content-dashboard">
        <br>
        <div class="container" data-select2-id="9">
            <div class="az-content-body" data-select2-id="8">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/receipt-report') }}" style="color: #97a3b9;"> Receipt Report</a></span>
                    <span style="color: #596881;">@if(request()->get('order_type')=='wo') Service Inspection & Receipt Report(MRR) @else Material Inspection & Receipt Report(MRR) @endif</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">{{--@if(!empty($edit)) Edit @else Add @endif--}} @if(request()->get('order_type')=='wo') Service @else Material @endif Inspection & Receipt Report Info @if(!empty($edit)) ({{$edit['mrr']->mrr_number}}) @endif

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
                        @if(request()->get('order_type')=='wo') Service @else Material @endif Receipt :
                        </label>
                        <div class="form-devider"></div>
                    </div>
                </div>
                <form method="POST" id="commentForm" autocomplete="off" >
                    <div class="row">
                        {{ csrf_field() }}

                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" data-select2-id="7">
                            <label> @if(request()->get('order_type')=='wo') WOA @else MAC @endif number *<span class="spinner-border spinner-button spinner-border-sm"
                                    style="display:none;" role="status" aria-hidden="true"></span></label>
                            @if(request()->get('order_type')=='wo')
                                <select class="form-control woa_number" name="mac_number" @if(!empty($edit['mrr'])) disabled @endif>
                                    @if(!empty($edit['mrr']))
                                        <option value="{{$edit['mrr']->mac_id}}" selected>{{$edit['mrr']->mac_number}}</option>
                                    @endif            
                                </select>
                                @if(!empty($edit['mrr']))
                                    <input type="hidden" name="mac_number" value="{{$edit['mrr']->mac_id}}">
                                @endif
                            @else
                                <select class="form-control mac_number" name="mac_number" @if(!empty($edit['mrr'])) disabled @endif>
                                    @if(!empty($edit['mrr']))
                                        <option value="{{$edit['mrr']->mac_id}}" selected>{{$edit['mrr']->mac_number}}</option>
                                    @endif            
                                </select>
                                @if(!empty($edit['mrr']))
                                    <input type="hidden" name="mac_number" value="{{$edit['mrr']->mac_id}}">
                                @endif
                            @endif
                        </div>
                        <input type="hidden" value="{{request()->get('order_type')}}" id="order_type"  name="order_type">
                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <label>@if(request()->get('order_type')=='wo') SRR @else MRR @endif date *</label>
                            <input type="text" class="form-control datepicker" value="{{ (!empty($edit['mrr'])) ? date('d-m-Y',strtotime($edit['mrr']->mrr_date)) : date("d-m-Y")}}" name="mrr_date" placeholder="MRR date">
                        </div>

                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <label>Created by: *</label>
                            <select class="form-control user_list" name="created_by">
                            @foreach ($data['users'] as $user)
                                <option value="{{$user->user_id}}"   @if(!empty($edit['mrr']) && $edit['mrr']->created_by == $user->user_id) selected  @endif   >{{$user->f_name}} {{$user->l_name}}</option>
                            @endforeach                                          
                            </select>
                        </div>


                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                    class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    @if(!empty($edit['mrr'])) Update @else Submit @endif
                            </button>
                        </div>
                    </div>
                </form>
                <div class="data-bindings">
                </div>
                @if(!empty($edit)) 
                 <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                        @if(str_starts_with($edit['mrr']['mrr_number'] , 'MRR') )
                            Material Inspection & Receipt Report ({{$edit['mrr']['mrr_number']}})
                        @else
                            Service Inspection & Receipt Report ({{$edit['mrr']['mrr_number']}})
                        @endif
                        </label>
                        <div class="form-devider"></div>
                    </div>
                </div>
                <table class="table table-bordered mg-b-0">
                    <thead>
                        
                    </thead>
                    <tbody>
                        <tr>
                            <th>@if(str_starts_with($edit['mrr']['mrr_number'] , 'MRR') ) MRR
                                @else SRR @endif Date</th>
                            <td>{{date('d-m-Y', strtotime($edit['mrr']['mrr_date']))}}</td>
                            
                        </tr>
                        <tr>
                            <th>Created By & Created Date</th>
                            <td>{{$edit['mrr']['f_name']}} {{$edit['mrr']['l_name']}}, {{date('d-m-Y', strtotime($edit['mrr']['created_at']))}}</td>
                        <tr>
                        <tr>
                            <th>Supplier ID</th>
                            <td>{{$edit['mrr']['vendor_id']}}</td>
                        </tr>
                        <tr>
                            <th>Supplier Name</th>
                            <td>{{$edit['mrr']['vendor_name']}}</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                        @if(str_starts_with($edit['mrr']['mrr_number'] , 'MRR') )
                            MRR Items
                        @else
                            SRR Items
                        @endif
                        </label>
                        <div class="form-devider"></div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0" id="example1">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Type</th>
                                <th>Accepted Qty</th>
                                <th>EXpiry Date</th>
                            </tr>
                        </thead>
                        <tbody >
                            @foreach($edit['items'] as $item)
                            <tr>
                                <th>{{$item['item_code']}}</th>
                                <th>{{$item['type_name']}}</th>
                                <th>@if($item['accepted_quantity']!=NULL) {{$item['accepted_quantity']}} {{$item['unit_name']}} @endif</th>
                                <th>{{date('d-m-Y', strtotime($item['expiry_date']))}}</th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        
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
                    mac_number: {
                        required: true,
                    },
                    mrr_date:{
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


    $('.mac_number').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true,
          ajax: {
          url: "{{ url('inventory/find-mac-for-mrr') }}",
          processResults: function (data) {

            return { results: data };

          }
        }
      }).on('change', function (e) {
        $('.spinner-button').show();

        let res = $(this).select2('data')[0];
        if(res){
          $.get("{{ url('inventory/find-mac-info') }}?id="+res.id,function(data){
            $('.data-bindings').html(data);
            $('.spinner-button').hide();
          });
        }else{
          $('.data-bindings').html('');
          $('.spinner-button').hide();
        }
      });

      $('.woa_number').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true,
          ajax: {
          url: "{{ url('inventory/find-woa-for-mrr') }}",
          processResults: function (data) {

            return { results: data };

          }
        }
      }).on('change', function (e) {
        $('.spinner-button').show();

        let res = $(this).select2('data')[0];
        if(res){
          $.get("{{ url('inventory/find-woa-info') }}?id="+res.id,function(data){
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
