@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="" style="color: #596881;">PURCHASE DETAILS</a></span>
                    <span><a href="">Lot Number Allocation</a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                Lot Number Allocation </h4>

                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <form method="POST" id="commentForm" novalidate="novalidate">
                            {{ csrf_field() }}
                            
							<div class="form-devider"></div>
                            <div class="row">
                            @if(Session::get('error'))
                            <div class="alert alert-danger "  role="alert" style="width: 100%;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                {{Session::get('error')}}
                            </div>
                            @endif
                            @if (Session::get('success'))
                            <div class="alert alert-success " style="width: 100%;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                            </div>
                            @endif 
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label for="exampleInputEmail1">Lot Number *</label>
                                    <input type="text" class="form-control lot-number" name="lot_number" id="lot_number" placeholder="Lot Number">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Document No *</label>
                                    <input type="text"  class="form-control document-no" value="" name="document_no" id="document-no" placeholder="Document No">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Rev No *</label>
                                    <input type="text" class="form-control" id="rev_no" name="rev_no"
                                        placeholder="Rev No">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Rev Date *</label>
                                    <input type="date"  value="" class="form-control" name="rev_date" id="rev_date" placeholder="Rev Date">
                                </div><!-- form-group -->

								<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Item Description *</label>
                                    <textarea value="" class="form-control" name="item_description"
                                        placeholder="Item Description"></textarea>
                                </div>

								<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Material Code *</label>
                                    <input type="text"  value="" class="form-control" name="material_code" id="material_code" placeholder="Material Code">
                                </div>

								<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Material Description *</label>
                                    <textarea value="" class="form-control" name="material_description"
                                        placeholder="Material Description"></textarea>
                                </div>

								<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Invoice No *</label>
                                    <input type="text"  value="" class="form-control" name="invoice_no" id="invoice_no" placeholder="Invoice No">
                                </div>

								<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Invoice Date *</label>
                                    <input type="date"  value="" class="form-control" name="invoice_date" id="invoice_date" placeholder="Invoice Date">
                                </div>

								<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Invoice Qty *</label>
                                    <input type="text"  value="" class="form-control" name="invoice_qty" id="invoice_qty" placeholder="Invoice Qty">
                                </div>

								<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Qty Received *</label>
                                    <input type="text"  value="" class="form-control" name="qty_received" id="qty_received" placeholder="Qty Received">
                                </div>

								<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Qty accepted *</label>
                                    <input type="text"  value="" class="form-control" name="qty_accepted" id="qty_accepted" placeholder="Qty Aceepted">
                                </div>

								<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Qty rejected *</label>
                                    <input type="text"  value="" class="form-control" name="qty_rejected" id="qty_rejected" placeholder="Qty Rejected">
                                </div>

								<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Unit *</label>
                                    <input type="text"  value="" class="form-control" name="unit" id="unit" placeholder="Unit">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>PO Number *</label>
                                    <input type="text"  value="" class="form-control" name="po_number" id="po_number" placeholder="PO number">
                                    <!-- <select class="form-control po_number" name="po_number" placeholder="PO Number">
                                        
                                    </select> -->
                                </div>
								<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Supplier *</label>
                                    <input type="text"  value="" class="form-control" name="supplier" id="supplier" placeholder="Supplier">
                                </div>


                                <!-- form-group -->
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Vehicle Number*</label>
                                    <input type="text" value="" class="form-control" name="vehicle_no" id="vehicle_no" placeholder="Vehicle Number">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Transporter Name *</label>
                                    <input type="text" value="" class="form-control" name="transporter_name" id="transporter_name" placeholder="Transporter Name">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> MRR Number*</label>
                                    <input type="text"  class="form-control" value="" id="mrr_no" name="mrr_no" placeholder="MRR Number">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> MRR Date*</label>
                                    <input type="date"  class="form-control" value="" id="mrr_date" name="mrr_date" placeholder="MRR Date">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Test Report No *</label>
                                    <input type="text" class="form-control" value="" name="test_report_no" placeholder="Test Report Date">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Test Report Date *</label>
                                    <input type="date" class="form-control"value="" id="test_report_date" name="test_report_date" placeholder="Test Report Date">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                            class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                        Save
                                    </button>
                                </div>
                            </div>
                            <div class="form-devider"></div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- az-content-body -->
    </div>
    <script src="<?= url('') ?>/js/azia.js"></script>
                <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
                <script src="<?= url('') ?>/js/jquery.validate.js"></script>
                <script src="<?= url('') ?>/js/additional-methods.js"></script>
                <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
                <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
                <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
                <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
                <script>
                  $(function(){

                    $("#commentForm").validate({
                            rules: {
                                lot_number: {
                                    required: true,
                                },
                                 document_no: {
                                     required: true,
                                },
                                rev_no: {
                                   required: true,
                                },
                                rev_date: {
                                     required: true,
                                },
                                Supplier: {
                                    required: true,
                                },
                                item_description: {
                                     required: true,
                                 },
                                material_description: {
                                    required: true,
                                },
                                material_code: {
                                     required: true,
                                },
                                invoice_no: {
                                     required: true,
                                 },
                                 invoice_date: {
                                    required: true,
                                },
                                invoice_qty: {
                                     required: true,
                                     number: true
                                },
                                qty_accepted: {
                                     required: true,
                                     number: true
                                 },
                                qty_rejected: {
                                    required: true,
                                    number: true
                                },
                                qty_received: {
                                    required: true,
                                    number: true
                                },
                                unit: {
                                    required: true,
                                },
                                po_number: {
                                    required: true,
                                },
                                supplier: {
                                    required: true,
                                },
                                vehicle_no: {
                                    required: true,
                                },
                                transporter_name: {
                                    required: true,
                                },
                                mrr_no: {
                                    required: true,
                                },
                                mrr_date: {
                                    required: true,
                                },
                                test_report_no: {
                                    required: true,
                                },
                                test_report_date: {
                                    required: true,
                                },
                                test_report_date: {
                                    required: true,
                                },
                                

                            },
                            submitHandler: function(form) {
                                  form.submit();
                            }
                        });

                            $('.Supplier').select2({
                                placeholder: 'Choose one',
                                searchInputPlaceholder: 'Search',
                                minimumInputLength: 3,
                                allowClear: true,
                                ajax: {
                                url: "{{ url('inventory/suppliersearch') }}",
                                processResults: function (data) {
                                  return {results: data
                                  };
                                }
                              }
                            });

                            
                           
                  });

                </script>


@stop
