@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="" style="color: #596881;">
                    Mailing Label</a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                Mailing Label
                </h4>
                <div class="az-dashboard-nav">
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <form method="post" id="commentForm" novalidate="novalidate" action="{{url('label/generate-mailing-label')}}">
                            {{ csrf_field() }}
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
                                @foreach ($errors->all() as $errorr)
                                    <div class="alert alert-danger "  role="alert" style="width: 100%;">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        {{ $errorr }}
                                    </div>
                                @endforeach
                                <table class="table table-bordered">
                                    <tbody id="dynamic_field">
                                        <tr id="row1" rel="1">
                                            <td>
                                                <div class="row">
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4"
                                                    style="float:left;">
                                                    <label for="exampleInputEmail1">Customer * </label>
                                                    <select class="form-control customer customer1" id="1"
                                                        name="moreItems[0][customer]" id="customer" required>
                                                    </select>
                                                    
                                                </div>
                                               
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4"
                                                    style="float:left;">
                                                    <label>Shipping Address  </label>
                                                    <textarea type="text" readonly class="form-control" id="shipping_address1"name="shipping_address"
                                                        placeholder="Shipping Address"></textarea>
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;" readonly>
                                                    <label>Zone </label>
                                                    <input type="text"  class="form-control" name="moreItems[0][zone]" id="zone1" placeholder="Zone" readonly>
                                                </div>
                                            </div>
                                            <div class="row"> 
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label>Contact Person</label>
                                                    <input type="text"  class="form-control" name="moreItems[0][contact_person]"
                                                        id="contact_person1" placeholder="Contact Person" readonly>
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label>Mobile Number</label>
                                                    <input type="text"  class="form-control" name="moreItems[0][mobile]"
                                                        id="mobile1" placeholder="Mobile Number" readonly>
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label>Quantity * </label>
                                                    <input type="number"  class="form-control" name="moreItems[0][quantity]"
                                                        id="quantity1" placeholder="Quantity" value="1">
                                                </div>
                                               
                                                <!-- <button type="button" name="add" id="add" class="btn btn-success"
                                                    style="height:38px;margin-top:28px;"><i
                                                        class="fas fa-plus"></i></button> -->
                                            </div>
                                            </td>
                                        </tr>


                                    </tbody>

                                </table>
                                <div class=" col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="button" name="add" id="add" class="btn btn-success btn-xs" style="height:38px;float:right;margin-right:78px;">
                                    <i class="fas fa-plus"></i></button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                            class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                            Print
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
        <script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
        <script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
        <script src="<?= url('') ?>/js/azia.js"></script>
        <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
        <script src="<?= url('') ?>/js/jquery.validate.js"></script>
        <script src="<?= url('') ?>/js/additional-methods.js"></script>
        <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
        <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
        <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
        <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
       

       <script>
  var divid = "";

// $(".datepicker").datepicker({
//     format: " dd-mm-yyyy",
//     autoclose:true
//     });

    function selectItem(itemId,divId){
        $('#Itemtype'+divId).val('');
        $.get("<?=url('inventory/get-single-item');?>?id="+itemId,function(response){
            let datas = [JSON.parse(response)];
            $(".item_code"+divId).select2({
                data: datas
            });
            $('#Itemtype'+divId).val(datas[0].type_name);
            $('#Itemdescription'+divId).val(datas[0].discription);
            $("#unit-div"+divId+"").text(datas[0].unit_name);
            //$('#u'+divId).val(datas[0].discription);
            initSelect2();
        });
    }

        $(document).ready(function(){
            initSelect2();
            var i = 1;
            $('#add').click(function(){
                i++;
                $('#dynamic_field').append(`
                      <tr id="row${i}" rel="${i}">
                      <td>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-3 col-lg-4 col-xl-4" style="float:left;">
                                <label for="exampleInputEmail1">Customer * </label>
                                <select class="form-control customer customer${i}" id="${i}" name="moreItems[${i}][customer]" id="customer" required>
                                </select>                        
                            </div>
                            <div class="form-group col-sm-12 col-md-3 col-lg-4 col-xl-4" style="float:left;">
                                <label>Shipping Address  </label>
                                <textarea type="text" readonly class="form-control" id="shipping_address${i}" name="moreItems[${i}][shipping_address]"
                                                        placeholder="Shipping Address"></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;" readonly>
                                <label>Zone </label>
                                <input type="text"  class="form-control" name="moreItems[${i}][zone]" id="zone${i}" placeholder="Zone" readonly>
                            </div>
                            
                        </div>
                        <div class="row"> 
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label>Contact Person</label>
                                <input type="text"  class="form-control" name="moreItems[${i}][contact_person]" id="contact_person${i}" placeholder="Contact Person" readonly>
                            </div>
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label>Mobile Number</label>
                                <input type="text"  class="form-control" name="moreItems[${i}][mobile]" id="mobile${i}" placeholder="Mobile Number" readonly>
                            </div>
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label>No Of Labels * </label>
                                <input type="number"  class="form-control" name="moreItems[${i}][quantity]" id="quantity${i}" placeholder="Quantity" value="1">
                            </div>
                            <button name="remove" id="${i}" class="btn btn-danger btn_remove" style="height:38px;margin-top:28px;margin-left: 18px;">X</button>
                        </div>
                    </td>                        
                </tr>`);
                initSelect2();
               
            });
            $(document).on('click','.btn_remove', function(){
                var button_id = $(this).attr("id");
                $("#row"+button_id+"").remove();
            });
        });
       
            $(function(){
                $("#commentForm").validate({
                    rules: {
                        customer: {
                                required: true,
                        },
                        quantity: {
                                    required: true,
                                    number: true
                        },
                    },
                     submitHandler: function(form) {
                         form.submit();
                     }
                });
            });
            function initSelect2() {
                
                //alert(oef_id);
                $(".customer").select2({
                    placeholder: 'Choose one',
                    searchInputPlaceholder: 'Search',
                    minimumInputLength: 4,
                    allowClear: true,
                    ajax: {
                        url: "{{ url('fgs/customersearch') }}/",
                        processResults: function (data) {
                                return { results: data };
                                // return {
                                //     q: term,
                                //     oef_id: oef_id,
                                // }
                        }
                    }
                }).on('change', function (e) {
                    var select_id = $(this).attr("id");
                    $('#Itemcode-error').remove();
                    $("#shipping_address"+select_id+"").text('');
                    $("#zone"+select_id+"").val('');
                    $("#mobile"+select_id+"").val('');
                    $("#contact_person"+select_id+"").val('');
                    let res = $(this).select2('data')[0];
                        if(typeof(res) != "undefined" ){
                            if(res.zone_name){
                                $("#zone"+select_id+"").val(res.zone_name);
                            }
                            if(res.shipping_address){
                                $("#shipping_address"+select_id+"").val(res.shipping_address);
                            }
                            if(res.contact_person){
                                $("#contact_person"+select_id+"").val(res.contact_person);
                            }
                            if(res.contact_number){
                                $("#mobile"+select_id+"").val(res.contact_number);
                            }
                       }
                    });   
            }  
            
            $('.IGST').on('change', function() {
                var select_id = $(this).attr("index");
                let igst = $(this).val();
                $('.append-option').remove();
                $('.edit-zero').remove();
                $('#gst-id'+select_id+'').val('');
                // $('#CGST').load();
                // $('#SGST').load();
                $.ajax ({
                    type: 'GET',
                    url: "{{url('getSGSTandCGST')}}",
                    data: { id: '' + igst + '' },
                    success : function(data) {
                        $('#gst-id'+select_id+'').val(data.id);
                       $('#SGST'+select_id+'').append('<option class="append-option" value=' + data.id + ' selected>' + data.sgst + '%</option>');
                       $('#CGST'+select_id+'').append('<option class="append-option" value=' + data.id + ' selected>' + data.cgst + '%</option>');
    
                    }
                });
                
            });
            $('.SGST').on('change', function() {
                var select_id = $(this).attr("index");
                let sgst = $(this).val();
                $('#gst-id'+select_id+'').val('');
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
                       $('#gst-id'+select_id+'').val(data.id);
                       $('#IGST'+select_id+'').append('<option class="append-option" value=' + data.id + ' selected>' + data.igst + '%</option>');
                       $('#CGST'+select_id+'').append('<option class="append-option" value=' + data.id + ' selected>' + data.cgst + '%</option>');
    
                    }
                });
                
            });
            $('.CGST').on('change', function() {
                var select_id = $(this).attr("index");
                let cgst = $(this).val();
                $('.append-option').remove();
                $('.edit-zero').remove();
                $('#gst-id'+select_id+'').val('');
                //$("#SGST").selectmenu("refresh");
                $.ajax ({
                    type: 'GET',
                    url: "{{url('getSGSTandCGST')}}",
                    data: { id: '' + cgst + '' },
                    success : function(data) {
                        $('#gst-id'+select_id+'').val(data.id);
                       $('#IGST'+select_id+'').append('<option class="append-option" value=' + data.id + ' selected>' + data.igst + '%</option>');
                       $('#SGST'+select_id+'').append('<option class="append-option" value=' + data.id + ' selected>' + data.sgst + '%</option>');
    
                    }
                });
                
            });
    </script>
@stop
