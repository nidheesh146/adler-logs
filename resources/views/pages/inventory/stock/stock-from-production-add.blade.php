@extends('layouts.default')
@section('content')
    <div class="az-content az-content-dashboard">
        <br>
        <div class="container" data-select2-id="9">
            <div class="az-content-body" data-select2-id="8">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/supplier-invoice') }}">Stock From Production</a></span>
                    <span>@if(!empty($edit)) Edit @else Add @endif Stock From Production Info</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">@if(!empty($edit)) Edit @else Add @endif Stock From Production Info @if(!empty($edit)) (SRP3-2223-001) @endif
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
                        Stock Issue to Production :
                        </label>
                        <div class="form-devider"></div>
                    </div>
                </div>
                <form method="POST" id="commentForm" autocomplete="off" >
                    <div class="row">
                        {{ csrf_field() }}

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" data-select2-id="7">
                            <label>MIQ number *<span class="spinner-border spinner-button spinner-border-sm"
                                    style="display:none;" role="status" aria-hidden="true"></span></label>
                           
                            <select class="form-control invoice_number" name="invoice_number" @if(!empty($data['miq'])) disabled @endif>
                                <!-- <option value="" ></option> -->
                               
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" data-select2-id="7">
                            <label>Department*<span class="spinner-border spinner-button spinner-border-sm"
                                    style="display:none;" role="status" aria-hidden="true"></span></label>
                           
                            <select class="form-control invoice_number" name="invoice_number" @if(!empty($data['miq'])) disabled @endif>
                                <!-- <option value="" ></option> -->
                               
                            </select>
                        </div>

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <label>Date *</label>
                            <input type="text" class="form-control datepicker" value="{{ (!empty($data['miq'])) ? date('d-m-Y',strtotime($data['miq']->miq_date)) : date("d-m-Y")}}" name="miq_date" placeholder="MIQ date">
                        </div>

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <label>Issued by: *</label>
                            <select class="form-control user_list" name="created_by">
                                                                         
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
                @if(!empty($edit))
                <div class="data-bindings">
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                MIQ NUMber (MIQ3-2223-001)
                                </label>
                            <div class="form-devider"></div>
                        </div>
                    </div>
                    <table class="table table-bordered mg-b-0">    
                        <thead>
                            <tr>
                                <th>MIQ No</th>
                                <th>MIQ3-2223-001</th>
                                <th>MIQ Date</th>
                                <th>10-11-2022</th>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <th>Aarya Vision</th>
                                <th>Prepared By</th>
                                <th>Nayan</th>
                            </tr>
                            
                        </thead> 
                    </table>
                    <br/>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            MIQ items</label>
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
                                    <th>Expiry Date</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        <tbody >
                            <tr>
                                    <th>Item Code</th>
                                    <th>Item Type</th>
                                    <th>LOT No.</th>
                                    <th>Quantity</th>
                                    <th>Stk Kpng Unit</th>
                                    <th>Expiry Date</th>
                                    <th>Remarks</th>
                                <th><a class="badge badge-info" style="font-size: 13px;" href="{{url('inventory/Stock/FromProduction/1/item')}}"  class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 	</th>
                            </tr>
                           
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
