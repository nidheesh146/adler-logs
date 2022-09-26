@extends('layouts.default')
@section('content')

<style>
.select2-container{
    width:100% !important;
}

</style>
    <div class="az-content az-content-dashboard">
        <br>

        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb"> 
            
                     <span><a href="">Supplier Invoice</a></span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;"> Add Lot Number Allocation 
                    <div class="right-button">
                      <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                          <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                      <div class="dropdown-menu">
                      <a href="#" class="dropdown-item">Excel</a>
        
                      </div> -->
                    {{-- <button style="float: right;font-size: 14px;" onclick="document.location.href='http://localhost/adler/public/inventory/supplier-invoice-add'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Supplier Invoice</button>  --}}
                    </div>
                </h4>
                <div class="az-dashboard-nav">
                    <nav class="nav"> </nav>	
                </div>
        
                                 
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
                
               
                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0" id="example1">
                        <thead>
                            <tr>
                            
                                <th style="width:100px;">PO Number</th>
                                <th>Invoice number:</th>
                                <th>Invoice date</th>
                                <th>Supplier</th>
                                <th>ITEM CODE:</th>
                                <th>HSN</th>
                                <th>QUANTITY</th>
                                <th>RATE</th>
                                <th>DISCOUNT</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $data['items']  as $item)
                            <tr @if($item['lot_id']) style="background:#3bb00133;" @endif>
                                <td>{{$item['po_number']}}</td>
                                <td>{{$item['invoice_number']}}</td>
                                <td>{{$item['invoice_date']}}</td>
                                <td>{{$item['vendor_id']}}-{{$item['vendor_name']}}</td>
                                <td>{{$item['item_code']}}</td>
                                <td>{{$item['hsn_code']}}</td>
                                <td>{{$item['order_qty']}}</td>
                                <td>{{$item['rate']}}</td>
                                <td>{{$item['discount']}}</td>
                                <td>
                                    @if(!$item['lot_id']) 
                                    <a class="badge badge-info lot-add" style="font-size: 13px;" href="#" data-invoiceitem="{{$item['id']}}" data-toggle="modal" data-target="#myModal"><i class="fas fa-plus"></i> LOT Number</a>                                    
                                    @else
                                    - 
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $data['items']->appends(request()->input())->links() }}
                   </div> 
                </div>
            </div>
        </div> 
        <!-- az-content-body -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" style="max-width: 97% !important;">
            
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header" style="display: block;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Add Lot Number Allocation</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                            <form method="POST" id="commentForm" novalidate="novalidate">
                                {{ csrf_field() }}
                                
                       
                                <div class="row">
                             
                                    {{-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                        <label for="exampleInputEmail1">Lot Number *</label>
                                        <input type="text" class="form-control lot-number" name="lot_number" id="lot_number" placeholder="Lot Number">
                                    </div> --}}
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Document No *</label>
                                        <input type="text"  class="form-control document-no" value="" name="document_no" id="document-no" placeholder="Document No">
                                    </div><!-- form-group -->
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Revision Number *</label>
                                        <input type="text" class="form-control" id="rev_no" name="rev_no"
                                            placeholder="Revision Number">
                                    </div><!-- form-group -->
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Revision Date *</label>
                                        <input type="date"  value="" class="form-control" name="rev_date" id="rev_date" placeholder="Rev Date">
                                    </div><!-- form-group -->
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Item Code</label>
                                        <input type="text"  value="" class="form-control" name="material_code" id="material_code" readonly placeholder="Material Code">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Item Description</label>
                                        <textarea value="" class="form-control" name="item_description" id="item_description"
                                            placeholder="Item Description" readonly></textarea>
                                    </div>
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Supplier</label>
                                        <input type="text"  value="" class="form-control" name="supplier_name" id="supplier_name" disabled placeholder="Supplier">
                                        <input type="hidden"  value="" class="form-control" name="supplier" id="supplier">
                                    </div>
    

                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Supplier Specification</label>
                                        <textarea value="" class="form-control" name="material_description"  id="material_description"
                                            placeholder="Material Description" readonly></textarea>
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Supplier Invoice Number</label>
                                        <input type="text"  value="" class="form-control" name="invoice_no" id="invoice_no" readonly placeholder="Invoice Number">
                                        <input type="hidden"  value="" class="form-control" name="invoice_id" id="invoice_id">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Supplier Invoice Date</label>
                                        <input type="date"  value="" class="form-control" name="invoice_date" id="invoice_date" readonly placeholder="Invoice Date">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Supplier Invoice Quantity</label>
                                        <input type="text"  value="" class="form-control" name="invoice_qty" id="invoice_qty" readonly placeholder="Invoice Qty">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Quantity Received *</label>
                                        <input type="text"  value="" class="form-control" name="qty_received" id="qty_received" placeholder="Quantity Received">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Quantity accepted *</label>
                                        <input type="text"  value="0" class="form-control" name="qty_accepted" id="qty_accepted" placeholder="Qty Aceepted">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Quantity rejected *</label>
                                        <input type="text"  value="0" class="form-control" name="qty_rejected" id="qty_rejected" placeholder="Qty Rejected">
                                    </div>
                                   
                                        <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3 rejobj">
                                            <label>Rejected Reason</label>
                                            <textarea value="" class="form-control" name="qty_rej_reason"  id="qty_rej_reason"
                                            placeholder="Rejected Reason" ></textarea>
                                        </div>
                                        <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3 rejobj">
                                            <label>Rejected Person</label>
                                            <select class="form-control rejected_user" name="rejected_user">
                                                @foreach ($data['users'] as $item)
                                                 <option value="{{$item['user_id']}}"
                                                 @if(!empty($data['simaster']) && $data['simaster']->created_by == $item['user_id']) selected @endif
                                                 >{{$item['employee_id']}} - {{$item['f_name']}} {{$item['l_name']}}</option>
                                                @endforeach
                                            </select>  
                                        </div>


                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Unit</label>
                                        <input type="text"  value="" class="form-control" name="unit_name" id="unit_name" readonly  placeholder="Unit">
                                        <input type="hidden"  value="" class="form-control" name="unit" id="unit">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>PO Number</label>
                                        <input type="text"  value="" class="form-control" name="po_number" disabled id="po_number" placeholder="PO number">
                                        <input type="hidden"  value="" class="form-control" name="si_id" id="si_id">
                                    </div>
                    
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label> MRR Number </label>
                                        <input type="text"  class="form-control" value="" id="mrr_no" name="mrr_no" placeholder="MRR Number">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label> MRR Date </label>
                                        <input type="date"  class="form-control" value="" id="mrr_date" name="mrr_date" placeholder="MRR Date">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Supplier Invoice rate <span id="inv_rate"></span> </label>
                                        <input type="text" readonly class="form-control" value="" name="invoice_rate" id="invoice_rate" placeholder="Invoice rate">
                                    </div>
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Currency *</label>
                                        <select class="form-control" name="currency" id="currency">
                                            @foreach ($data["currency"] as $item)
                                               <option value="{{$item->currency_id}}" @if($item->currency_code == "INR") selected  @endif >{{$item->currency_code}}</option>
                                            @endforeach
                                          </select>
                                    </div>
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Conversion rate (INR) *</label>
                                        <input type="text" class="form-control" value="" name="conversion_rate" id="conversion_rate" placeholder="Conversion rate">
                                    </div>
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Value in INR </label>
                                        <input type="text" readonly class="form-control" value="" name="value_inr" id="value_inr" placeholder="Value in INR">
                                    </div>




                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label> Test Report Number *</label>
                                        <input type="text" class="form-control" value="" name="test_report_no" placeholder="Test Report Number">
                                    </div>


                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label> Test Report Date *</label>
                                        <input type="date" class="form-control"value="" id="test_report_date" name="test_report_date" placeholder="Test Report Date">
                                    </div>

                                    <!-- form-group -->
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Vehicle Number </label>
                                        <input type="text" value="" class="form-control" name="vehicle_no" id="vehicle_no" placeholder="Vehicle Number">
                                    </div><!-- form-group -->
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label> Transporter Name </label>
                                        <input type="text" value="" class="form-control" name="transporter_name" id="transporter_name" placeholder="Transporter Name">
                                    </div>
    

                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label> Prepared By *</label>
                                        <select class="form-control user_list" name="prepared_by">
                                            @foreach ($data['users'] as $item)
                                             <option value="{{$item['user_id']}}"
                                             @if(!empty($data['simaster']) && $data['simaster']->created_by == $item['user_id']) selected @endif
                                             >{{$item['employee_id']}} - {{$item['f_name']}} {{$item['l_name']}}</option>
                                            @endforeach
                                        </select>                                    </div>

                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Approved By *</label>
                                        <select class="form-control user_list" name="approved_by">
                                            @foreach ($data['users'] as $item)
                                             <option value="{{$item['user_id']}}"
                                             @if(!empty($data['simaster']) && $data['simaster']->created_by == $item['user_id']) selected @endif
                                             >{{$item['employee_id']}} - {{$item['f_name']}} {{$item['l_name']}}</option>
                                            @endforeach
                                        </select>                                  </div>




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
                  
                            </form>
    
    
    
    
    
    
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <br>
                  {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
                </div>
              </div>
              
            </div>
        </div>
        <!-- </div> -->
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
            $('.user_list').select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search',
            });
            $('.rejected_user').select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search',
            });


            
            $(".lot-add").on( "click", function() {
                        $('#item_description').text('');$('#material_description').text('');
                        $('#material_code').val('');$('#invoice_no').val('');
                        $('#invoice_date').val('');$('#invoice_qty').val('');
                        $('#po_number').val('');$('#supplier').val('');
                        $('#supplier_name').val('');$('#po_id').val('');
                        $('#unit_name').val('');$('#unit').val('');
                        $('#invoice_id').val('');$('#inv_rate').text('')
                        $('#invoice_rate').val('')
                        var invoice_item_id = $(this).data('invoiceitem');
                        $.get("{{ url('inventory/get-single-invoice-item') }}/"+invoice_item_id,function(data){
                            $('#item_description').text(data.discription);
                            $('#material_description').text(data.specification);
                            $('#material_code').val(data.item_code);
                            $('#invoice_no').val(data.invoice_number);
                            $('#invoice_date').val(data.invoice_date);
                            $('#invoice_qty').val(data.invoice_qty);
                            $('#po_number').val(data.po_number);
                            $('#si_id').val(data.invoice_item_id);
                            $('#supplier').val(data.supplier_id);
                            $('#supplier_name').val(data.vendor_id+"-"+data.vendor_name);
                            $('#supplier').val(data.supplier_id);
                            $('#unit_name').val(data.unit_name);
                            $('#unit').val(data.unit_id);
                            $('#invoice_id').val(data.invoice_item_id);
                            $('#inv_rate').text("( Rate : "+data.rate+" , Discount(%) : "+data.discount+")")
                            $('#invoice_rate').val(data.total_rate);
                        });
            });


            jQuery.validator.addMethod("checkPrevValuePaxTo", function (value, element) {
                let qty_received =  $('#qty_received').val();
                let qtyaccepted = (qty_received - ((+$('#qty_accepted').val()) + (+$('#qty_rejected').val())));
                    if(qtyaccepted == 0 )
                    {
                        return true;
                    }else{
                        return false;
                    }
            }, "if Quantity accepted and Quantity rejected are added , The value of Quantity Received should be !");

            $('#qty_received').on('input',function(){
                let qty_received =  $('#qty_received').val();
                let qtyaccepted = (qty_received - (+$('#qty_rejected').val()));
                $('#qty_accepted').attr('value',qtyaccepted);
                reject_changes();
            });
            $('#qty_accepted').on('input',function(){
                let qty_received =  $('#qty_received').val();
                let qtyaccepted = (qty_received - (+$('#qty_accepted').val()));
                $('#qty_rejected').attr('value',qtyaccepted);
                reject_changes();
            });
            $('#qty_rejected').on('input',function(){
                let qty_received =  $('#qty_received').val();
                let qtyaccepted = (qty_received - (+$('#qty_rejected').val()));
                $('#qty_accepted').attr('value',qtyaccepted);
                reject_changes();
            });
            $('.rejobj').hide();
            function reject_changes(){
               let qty_rejected =  $('#qty_rejected').val();
                if(qty_rejected > 0){
                    $('.rejobj').show();
                }else{
                    $('.rejobj').hide();
                }
            }
            $("#conversion_rate").on('input',function(){
                curr_net_value()
            });
            $("#currency").on('change',function(){
                curr_net_value()
            });
            function curr_net_value(){
                $("#value_inr").val(($("#invoice_rate").val()*$("#conversion_rate").val()).toFixed(2));
            }
            


            $("#commentForm").validate({
                rules: {
                        document_no: {
                            required: true,
                        },
                        rev_no: {
                            required: true,
                        },
                        rev_date: {
                            required: true,
                        },
                        qty_accepted: {
                            required: true,
                            number: true,
                        },
                        qty_rejected: {
                            required: true,
                            number: true,
                            checkPrevValuePaxTo:true
                        },
                        qty_received: {
                            required: true,
                            number: true,
                        },
                        test_report_no: {
                            required: true,
                        },
                        test_report_date: {
                            required: true,
                        },
                        currency: {
                            required: true,
                        },
                        conversion_rate: {
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
                        return {
                                results: data
                        };
                    }
                }
            });    
        });

                </script>


@stop
