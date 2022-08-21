@extends('layouts.default')
@section('content')
    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/supplier-invoice') }}">SUPPLIER INVOICE </a></span>
                    <span> <a href="{{ url('inventory/supplier-invoice-add/' . $master) }}">EDIT SUPPLIER INVOICE</a></span>
                    <span><a> EDIT SUPPLIER INVOICE Item</a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                    <!-- Add  Supplier quotation item</h4> -->
                    Edit Supplier invoice item for ( {{ $data['item']->invoice_number }} )
                </h4>
                <!-- <div class="az-dashboard-nav">
                        <nav class="nav">
                            <a class="nav-link" href="http://localhost/adler/public/inventory/edit-purchase-reqisition?pr_id=">Supplier Quotation</a>
                            <a class="nav-link  active" >Edit Supplier Quotation item </a>
                            <a class="nav-link  " href=""> </a>
                        </nav>
      
                    </div> -->

                <div class="row">

                    <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 "
                        style="border: 0px solid rgba(28, 39, 60, 0.12);">


                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-hand-point-right"></i> Item details </label>
                                <div class="form-devider"></div>
                            </div>
                        </div>


                        <div class="row">

                            <table class="table table-bordered mg-b-0">
                                <thead>
                                    <tr>
                                        <th>PR NO</th>
                                        <th>ITEM CODE</th>
                                        <th>HSN code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $data['item']->pr_no }}</td>
                                        <td>{{ $data['item']->item_code }}</td>
                                        <td>{{ $data['item']->hsn_code }}</td>


                                    </tr>



                                </tbody>
                            </table>
                        </div>

                        <div class="row" style="margin-top: 7px;">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-hand-point-right"></i> Supplier </label>
                                <div class="form-devider"></div>
                            </div>
                        </div>
                        <div class="row">

                            <table class="table table-bordered mg-b-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $data['item']->vendor_id }}</td>
                                        <td>{{ $data['item']->vendor_name }}</td>

                                    </tr>



                                </tbody>
                            </table>
                        </div>


                        <div class="row" style="margin-top: 7px;">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-hand-point-right"></i> Final Purchase Order Details </label>
                                <div class="form-devider"></div>
                            </div>
                        </div>
                        <div class="row">

                            <table class="table table-bordered mg-b-0">
                                <thead>
                                    <tr>
                                        <th>PO NO</th>
                                        <th>PO date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <td>{{ $data['item']->po_number }}</td>
                                        <td>{{ date('d-m-Y', strtotime($data['item']->po_date)) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row" style="margin-top: 7px;">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-hand-point-right"></i> Supplier Invoice Item Details </label>
                                <div class="form-devider"></div>
                            </div>
                        </div>
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
                        <form method="POST" id="commentForm" >
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>Quantity *</label>
                                    <input type="text" name="quantity" value="{{ $data['item']->order_qty }}"
                                        class="form-control" placeholder="Quantity">

                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label> Rate *</label>
                                    <input type="text" class="form-control" value="{{ $data['item']->rate }}" name="rate"
                                        id="rate" placeholder="Rate">
                                </div>
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label> Discount (%) *</label>
                                    <input type="text" class="form-control" value="{{ $data['item']->discount }}"
                                        name="discount" id="discount" placeholder="Discount">
                                </div>

                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label> Specification *</label>
                                    <textarea class="form-control" id="Specification" name="specification"
                                        placeholder="Specification">{{ $data['item']->specification }}</textarea>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                            class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="form-devider"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
        <script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
        <script src="<?= url('') ?>/js/azia.js"></script>
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
                quantity: {
                    required: true,
                },
                rate: {
                    required: true,
                },
                discount:{
                    required: true,
                },
                specification:{
                    required: true,
                }

            },
            submitHandler: function(form) {
               // $('.spinner-button').show();
                form.submit();
            }
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
