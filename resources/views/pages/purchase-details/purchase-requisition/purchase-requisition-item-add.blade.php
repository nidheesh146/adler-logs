@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/get-purchase-reqisition') }}" style="color: #596881;">PURCHASE
                            DETAILS</a></span>
                    <span><a href="{{ url('inventory/get-purchase-reqisition') }}" style="color: #596881;">
                            REQUISITION</a></span>
                    <span><a href="">
                    @if(request()->pr_id)
                        {{ request()->item ? 'Edit' : 'Add' }} Purchase Requisition Details ( {{$data["master"]['pr_no']}}  )
                    @endif
                    @if(request()->sr_id)
                        {{ request()->item ? 'Edit' : 'Add' }} service requisition Details ( {{$data["master"]['pr_no']}}  )
                    @endif
                    </a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                    @if(request()->pr_id)
                        {{ request()->item ? 'Edit' : 'Add' }} Purchase Requisition Details ( {{$data["master"]['pr_no']}}  )
                    @endif
                    @if(request()->sr_id)
                            {{ request()->item ? 'Edit' : 'Add' }} Service Requisition Details ( {{$data["master"]['pr_no']}}  )
                    @endif
                </h4>
                <div class="az-dashboard-nav">
                    <nav class="nav">
                    @if(request()->pr_id)
                        <a class="nav-link    "
                            href="{{ url('inventory/edit-purchase-reqisition?pr_id=' . request()->pr_id) }}">Purchase
                            Requestor Details </a>
                        <a class="nav-link  active" @if (request()->pr_id) href="{{ url('inventory/get-purchase-reqisition-item?pr_id=' . request()->pr_id) }}" @endif> Purchase Requisition Details  </a>
                        <a class="nav-link  " href=""> </a>
                    @endif
                    @if(request()->sr_id)
                        <a class="nav-link    "
                            href="{{ url('inventory/edit-purchase-reqisition?sr_id=' . request()->sr_id) }}">Service 
                            Requestor Details </a>
                        <a class="nav-link  active" @if (request()->sr_id) href="{{ url('inventory/get-purchase-reqisition-item?sr_id=' . request()->sr_id) }}" @endif> Service Requisition Details </a>
                        <a class="nav-link  " href=""> </a>
                    @endif
                    </nav>

                </div>

                <div class="row">

                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">

                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <form method="POST" id="commentForm" novalidate="novalidate">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                        <i class="fas fa-address-card"></i> Basic details </label>
                                    <div class="form-devider"></div>
                                </div>
                            </div>

                            <div class="row">

                            @foreach ($errors->all() as $errorr)
                    <div class="alert alert-danger "  role="alert" style="width: 100%;">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      {{ $errorr }}
                    </div>
                   @endforeach 
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label for="exampleInputEmail1">Item code * </label>


                                    <select class="form-control Item-code" name="Itemcode" id="Itemcode">
                                        @if (!empty($datas["item"]))
                                            <option value="{{$datas["item"]['Item_code']}}" selected>{{$datas["item"]['item_code']}}</option>
                                        @endif
                                    </select>



                                    {{-- <input type="text" class="form-control" name="Itemcode" id="Itemcode" 
                                    value="{{ (!empty($datas)) ? $datas['item']['item_code'] : ''}}"
                                        placeholder="Item code">
                                        <input type="hidden" value="{{ (!empty($datas)) ? $datas['item_code'] : ''}}" name="Itemcodehidden" id="Itemcodehidden" > --}}
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Item type * </label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['type_name'] : '' }}"
                                        name="Itemtype" id="Itemtype" placeholder="Item type">
                                    <input type="hidden"
                                        value="{{ !empty($datas) ? $datas['item']['item_type_id'] : '' }}"
                                        name="Itemtypehidden" id="Itemtypehidden">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Item description *</label>
                                    <textarea readonly class="form-control" id="Itemdescription" name="Itemdescription"
                                        placeholder="">{{ !empty($datas) ? $datas['item']['discription'] : '' }}</textarea>

                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>HSN/SAC code *</label>
                                    <input type="text" readonly
                                        value="{{ !empty($datas) ? $datas['item']['hsn_code'] : '' }}"
                                        class="form-control" name="HSNSAC" id="HSNSAC" placeholder="">
                                </div><!-- form-group -->

                                <!-- form-group -->
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Unit name*</label>
                                    <input type="text" readonly
                                        value="{{ !empty($datas) ? $datas['item']['unit_name'] : '' }}"
                                        class="form-control" name="Unit" id="Unit" placeholder="">
                                    <input type="hidden"
                                        value="{{ !empty($datas) ? $datas['item']['unit_id'] : '' }}"
                                        name="Unithidden" id="Unithidden">
                                </div><!-- form-group -->
                               
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Actual order Qty *</label>
                                    <input type="text" class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['actual_order_qty'] : '' }}"
                                        name="ActualorderQty" id="ActualorderQty" placeholder="Actual order Qty">
                                </div>                               
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                            class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                        {{ request()->item ? 'Update' : 'Save' }}
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

            function  netvalue() {
                let Rate = $('#Rate').val();
                let actual_qty = $('#ActualorderQty').val();
                let total = Rate*actual_qty;
                let Discount = $('#Discount').val() ? $('#Discount').val() : 0;
                let discount_rate = (actual_qty*Rate*Discount)/100;
                let netvalue = (total-discount_rate);
                $('#Netvalue').val(netvalue.toFixed(2));

            }

            function basicValue() {
                let actual_qty = $('#ActualorderQty').val();
                let Rate = $('#Rate').val();
                let basic = actual_qty*Rate;
                $('#BasicValue').val(basic);
            }
            function DiscountValue() {
                let actual_qty = $('#ActualorderQty').val();
                let Rate = $('#Rate').val();
                let discout_percent = $("#Discount").val();
                let discount_rate = (actual_qty*Rate*discout_percent)/100;
                let discount_value = (actual_qty*Rate)-discount_rate;
                $('#DiscountRate').val(discount_rate); 
                $('#DiscountValue').val(discount_value); 
            }

            $( "#Rate" ).on( "input",function() {
                basicValue();
                netvalue();   
            });
            $( "#Discount" ).on( "input",function() {
                netvalue();
                DiscountValue();
            });
            $('.GST').on('change', function(e) {
                let gst= $(this).val();
                $.ajax ({
                    type: 'GET',
                    url: "{{url('getGST')}}",
                    data: { id: '' + gst + '' },
                    success : function(data) {
                       $('#IGST').val(data.igst);
                       $('#CGST').val(data.cgst);
                       $('#SGST').val(data.sgst);
                    }
                });
                netvalue();
            });

            $("#commentForm").validate({
                rules: {
                    Itemcode: {
                            required: true,
                    },
                    ActualorderQty: {
                                required: true,
                                number: true
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

            $('.Item-code').select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search',
                minimumInputLength: 6,
                allowClear: true,
                ajax: {
                    url: "{{ url('inventory/itemcodesearch') }}",
                    processResults: function (data) {
                            return { results: data };
                    }
                }
            }).on('change', function (e) {
                $('#Itemcode-error').remove();
                $('#Itemdescription').text('');
                $('#Itemtype').val('');
                $('#HSNSAC').val('');
                $('#Unit').val('');
                $('#MinLevel').val('');
                $('#MaxLevel').val('');
                $('#Itemtypehidden').val('');
                 
                let res = $(this).select2('data')[0];
                    if(res.short_description){
                        $('#Itemdescription').text(res.short_description);
                    }
                    if(res.type_name){
                        $('#Itemtype').val(res.type_name);
                    }
                    if(res.hsn_code){
                        $('#HSNSAC').val(res.hsn_code);
                    }
                    if(res.unit_name){
                        $('#Unit').val(res.unit_name);
                    }
                    if(res.min_stock || res.min_stock == 0 ){
                        $('#MinLevel').val(res.min_stock);
                    }
                    if(res.max_stock || res.max_stock == 0){
                        $('#MaxLevel').val(res.max_stock);
                    }
                    if(res.opening_quantity || res.opening_quantity == 0){
                        $('#OpenPOQty').val(res.opening_quantity);
                    }
                    if(res.availble_quantity || res.availble_quantity == 0){
                        $('#StockQty').val(res.availble_quantity);
                    }
                    if(res.id){
                        $('#Itemcodehidden').val(res.id);
                    }
                });       
            });
            $('#IGST').on('change', function() {
                let igst = $(this).val();
                let igst_percent = $(this).find('option:selected').text();
                var igst_val = parseInt(igst_percent.split('%', 1)[0]);
               
                let Rate = $('#Rate').val();
                let actual_qty = $('#ActualorderQty').val();
                let total = Rate*actual_qty;
                let Discount = $('#Discount').val() ? $('#Discount').val() : 0;
                let discount_rate = (actual_qty*Rate*Discount)/100;
                let netvalue = (total-discount_rate);

                new_net_val = (netvalue*igst_val/100)+netvalue;
                $('#Netvalue').val(new_net_val.toFixed(2));

                $('.append-option').remove();
                $('.edit-zero').remove();
                $('#gst-id').val('');
                // $('#CGST').load();
                // $('#SGST').load();
                $.ajax ({
                    type: 'GET',
                    url: "{{url('getSGSTandCGST')}}",
                    data: { id: '' + igst + '' },
                    success : function(data) {
                        $('#gst-id').val(data.id);
                       $('#SGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.sgst + '%</option>');
                       $('#CGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.cgst + '%</option>');
    
                    }
                });
                
            });
            $('#SGST').on('change', function() {
                $('#Netvalue').val('');
                let sgst = $(this).val();
                let sgst_percent = $(this).find('option:selected').text();
                var sgst_val = parseInt(sgst_percent.split('%', 1)[0]);
               
                let Rate = $('#Rate').val();
                let actual_qty = $('#ActualorderQty').val();
                let total = Rate*actual_qty;
                let Discount = $('#Discount').val() ? $('#Discount').val() : 0;
                let discount_rate = (actual_qty*Rate*Discount)/100;
                let netvalue = (total-discount_rate);
                
                new_net_val = (netvalue*sgst_val/100)+(netvalue*sgst_val/100)+netvalue;
                $('#Netvalue').val(new_net_val.toFixed(2));

                $('#gst-id').val('');
                $('.append-option').remove();
                $('.edit-zero').remove();
                $.ajax ({
                    type: 'GET',
                    url: "{{url('getSGSTandCGST')}}",
                    data: { id: '' + sgst + '' },
                    success : function(data) {
                        // if(data.igst==0){
                        //     $('.zero-option-igst').attr('value',data.id).show();
                        //     $('.zero-option-igst').attr('selected','selected').show();
                        // }
                        // $('.zero-option-igst').hide();
                       $('#gst-id').val(data.id);
                       $('#IGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.igst + '%</option>');
                       $('#CGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.cgst + '%</option>');
    
                    }
                });
                
            });
            $('#CGST').on('change', function() {
                $('#Netvalue').val('');
                let cgst = $(this).val();
                let cgst_percent = $(this).find('option:selected').text();
                var cgst_val = parseInt(cgst_percent.split('%', 1)[0]);
               
                let Rate = $('#Rate').val();
                let actual_qty = $('#ActualorderQty').val();
                let total = Rate*actual_qty;
                let Discount = $('#Discount').val() ? $('#Discount').val() : 0;
                let discount_rate = (actual_qty*Rate*Discount)/100;
                let netvalue = (total-discount_rate);
                
                new_net_val = (netvalue*cgst_val/100)+(netvalue*cgst_val/100)+netvalue;
                $('#Netvalue').val(new_net_val.toFixed(2));

                $('.append-option').remove();
                $('.edit-zero').remove();
                $('#gst-id').val('');
                //$("#SGST").selectmenu("refresh");
                $.ajax ({
                    type: 'GET',
                    url: "{{url('getSGSTandCGST')}}",
                    data: { id: '' + cgst + '' },
                    success : function(data) {
                        // if(data.igst==0){
                        //     $('.zero-option-igst').attr('value',data.id).show();
                        //     $('.zero-option-igst').attr('selected','selected').show();
                        // }
                        // $('.zero-option-igst').hide();
                        $('#gst-id').val(data.id);
                       $('#IGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.igst + '%</option>');
                       $('#SGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.sgst + '%</option>');
    
                    }
                });
                
            });

        </script>


@stop
