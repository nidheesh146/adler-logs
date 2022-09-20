@extends('layouts.default')
@section('content')
    <div class="az-content az-content-dashboard">
        <br>
        <div class="container" data-select2-id="9">
            <div class="az-content-body" data-select2-id="8">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/supplier-invoice') }}">Material Inwards To Quarantine</a></span>
                    <span>Add Material Inwards To Quarantine</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Add Material Inwards To Quarantine

                </h4>
                @foreach ($errors->all() as $errorr)
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{ $errorr }}
                </div>
               @endforeach               
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

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" data-select2-id="7">
                            <label>Invoice number *<span class="spinner-border spinner-button spinner-border-sm"
                                    style="display:none;" role="status" aria-hidden="true"></span></label>
                                <select class="form-control RQ-code" name="po_number">
                                
                                    <option value="" ></option>
                                  

                                </select>
                        </div>

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <label>MIQ number *</label>
                        <input type="text" class="form-control"  value=""  name="invoice_number" placeholder="Invoice number">
                        </div>

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <label>MIQ date *</label>
                            <input type="text" class="form-control datepicker" value="" name="date" placeholder="Invoice date">
                        </div>

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <label>Created by: *</label>
                            <select class="form-control user_list" name="create_by">
                                
                            </select>
                        </div>


                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                    class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Submit
                            </button>
                        </div>
                    </div>
                </form>
                <div class="data-bindings">
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                Supplier Invoice (1323724)
                                </label>
                            <div class="form-devider"></div>
                        </div>
                    </div>
                    <table class="table table-bordered mg-b-0">    
                        <thead>
                            <tr>
                                <th>Supplier Invoice date</th>
                                <th>created date</th>
                            </tr>
                        </thead> 
                        <tbody>
                            <tr>
                                <td>{{date('d-m-Y',strtotime('18-09-2022'))}}</td>
                                <td>{{date('d-m-Y',strtotime('19-09-2022'))}}</td>
                            </tr>
                        </tbody>
                    </table>
       
                    <table class="table table-bordered mg-b-0">     
                        <thead>
                            <tr>
                                <th>Supplier ID</th>
                                <th>Supplier Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>VIN001</td>
                            <td>AArya Vision</td>
                        </tr>
                    </tbody>
                </table><br>
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
                            <th>PO/WO No.</th>
                            <th>Item Code:</th>
                            <th>Quantity</th>
                            <th>Stk Kpng Unit</th>
                            <th>unit Rate</th>
                            <th>Expiry Control</th>
                            <th>Expiry Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody >
                    <tr>
                            <th>PO/WO No.</th>
                            <th>Item Code:</th>
                            <th>Quantity</th>
                            <th>Stk Kpng Unit</th>
                            <th>unit Rate</th>
                            <th>Expiry Control</th>
                            <th>Expiry Date</th>
                            <th><a class="badge badge-info" style="font-size: 13px;" href="{{url('inventory/MIQ/1/item')}}"  class="dropdown-item"><i class="fas fa-eye"></i> Edit</a> 	</th>
                        </tr>
                    </tbody>
                </table>
            </div>
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
            miq_number: {
                required: true,
            },
            invoice_number: {
                required: true,
            },
            miq_date:{
                required: true,
            },
            create_by:{
                required: true,
            }

        },
        submitHandler: function(form) {
           // $('.spinner-button').show();
            form.submit();
        }
    });


    $('.user_list').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',

      });


        $('.RQ-code').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 6,
          allowClear: true,
          ajax: {
          url: "{{ url('inventory/find-po-number') }}",
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
