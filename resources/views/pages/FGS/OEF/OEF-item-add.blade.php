@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="" style="color: #596881;">
                    Order Execution Form(OEF)</a></span>
                    <span><a href="" style="color: #596881;">
                            OEF Item</a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                OEF Item
                </h4>
                <div class="az-dashboard-nav">
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <form method="post" id="commentForm" novalidate="novalidate">
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
                                        <input type="hidden" value="{{$oef_id}}" name="oef_id" id="oef_id">
                                        <tr id="row1" rel="1">
                                            <td>
                                                <div class="row">
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label for="exampleInputEmail1">Product code * </label>
                                                    <select class="form-control product product_code1" id="1"
                                                        name="moreItems[0][product]" id="product">
                                                    </select>
                                                    
                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2"
                                                    style="float:left;">
                                                    <label>HSN Code * </label>
                                                    <input type="text" readonly class="form-control" name="Itemtype"
                                                        id="hsn_code1" placeholder="HSN Code">
                                                    <input type="hidden"
                                                        value="{{ !empty($datas) ? $datas['item']['item_type_id'] : '' }}"
                                                        name="Itemtypehidden" id="Itemtypehidden">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label>Description * </label>
                                                    <textarea type="text" readonly class="form-control" id="Itemdescription1"name="Description"
                                                        placeholder="Description"></textarea>
                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2"
                                                    style="float:left;">
                                                    <label>Quantity * </label>
                                                    <input type="text"  class="form-control" name="moreItems[0][quantity]"
                                                        id="quantity1" placeholder="Quantity">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-1 col-lg-1 col-xl-1"
                                                    style="float:left;">
                                                    <label>UOM </label>
                                                    <input type="text"  class="form-control" readonly name="uom"
                                                        id="uom1" placeholder="Nos">
                                                </div>
                                            </div>
                                            <div class="row"> 
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label>Rate* </label>
                                                    <input type="text"  class="form-control" name="moreItems[0][rate]" id="rate1" placeholder="Rate" readonly>
                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2"
                                                    style="float:left;">
                                                    <label>Discount %* </label>
                                                    <input type="text"  class="form-control" name="moreItems[0][discount]"
                                                        id="discount1" placeholder="Discount">
                                                </div>
                                               {{-- <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label> IGST ( % ) </label>
                                                    <input type="hidden" name="gst" id="gst-id1" index="1" value="">
                                                    <select class="form-control IGST" id="IGST1" name="moreItems[0][IGST]">
                                                        <option value="">--- select one ---</option>
                                                        <option class="zero-option-igst" value="" style="display:none;">0%</option>
                                                        @foreach ($data['gst'] as $item)
                                                            @if($item['igst']!=0)
                                                            <option value="{{ $item['id'] }}" >{{ $item['igst'] }} %</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-12 col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label> SGST ( % ) </label>
                                                    <select class="form-control SGST" id="SGST1" index="1" name="moreItems[0][SGST]">
                                                        <option value="">--- select one ---</option>
                                                        
                                                        <option  class="zero-option" value="" style="display:none;">0%</option>
                                                        @foreach ($data['gst'] as $item)
                                                            @if($item['sgst']!=0)
                                                            <option value="{{ $item['id'] }}" >{{ $item['sgst'] }} %</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-12 col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label> CGST ( % ) </label>
                                                    <select class="form-control CGST" id="CGST1" index="1" name="moreItems[0][CGST]">
                                                        <option value="">--- select one ---</option>
                                                        <option class="zero-option" value="" style="display:none;">0%</option>
                                                        @foreach ($data['gst'] as $item)
                                                            @if($item['cgst']!=0)
                                                            <option value="{{ $item['id'] }}" >{{ $item['cgst'] }} %</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>--}}
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label> IGST ( % ) </label>
                                                    <input type="hidden" name="moreItems[0][gst]" id="gst-id1" index="1" value="">
                                                    <input type="text" class="form-control" name="igst" id="igst1" value="" readonly>
                                                </div>
                                                <div class="form-group col-sm-12 col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label> SGST ( % ) </label>
                                                    <input type="text" class="form-control" name="sgst" id="sgst1" value="" readonly>
                                                </div>
                                                <div class="form-group col-sm-12 col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label> CGST ( % ) </label>
                                                    <input type="text" class="form-control" name="cgst" id="cgst1" value="" readonly>
                                                </div>
                                               
                                                <button type="button" name="add" id="add" class="btn btn-success"
                                                    style="height:38px;margin-top:28px;"><i
                                                        class="fas fa-plus"></i></button>
                                            </div>
                                            </td>
                                        </tr>


                                    </tbody>

                                </table>
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label for="exampleInputEmail1">Remarks  *</label>
                                    <textarea type="text" class="form-control" name="remarks" value="" placeholder=""></textarea>
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


function getsearch(){
 return   table.search();
}
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
            $('#myModal1').modal('hide');
            initSelect2();
        });
    }
    function get_data(id){
                    $('#myModal1').modal('show');
                    divid = id 
                    table.search('').columns().search('').draw();            
    }

        $(document).ready(function(){
            initSelect2();
            var i = 1;
            $('#add').click(function(){
                //alert('kk');
                i++;
                $('#dynamic_field').append(`
                      <tr id="row${i}" rel="${i}">
                      <td>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label for="exampleInputEmail1">Product code * </label>
                                <select class="form-control product item_code${i}" id="${i}" name="moreItems[${i}][product]" id="product">
                                </select>                        
                            </div>
                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                <label>HSN Code * </label>
                                <input type="text" readonly class="form-control" name="Itemtype" id="hsn_code${i}" placeholder="HSN Code">
                                <input type="hidden" value="{{ !empty($datas) ? $datas['item']['item_type_id'] : '' }}" name="Itemtypehidden" id="Itemtypehidden">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label>Description * </label>
                                <textarea type="text" readonly class="form-control" id="Itemdescription${i}"name="Description" placeholder="Description"></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                <label>Quantity * </label>
                                <input type="text"  class="form-control" name="moreItems[${i}][quantity]" id="quantity${i}" placeholder="Quantity">
                            </div>
                            <div class="form-group col-sm-12 col-md-1 col-lg-1 col-xl-1" style="float:left;">
                                <label>UOM </label>
                                <input type="text"  class="form-control" readonly name="uom"
                                                        id="uom${i}" placeholder="Nos">
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                <label>Rate* </label>
                                <input type="text"  class="form-control" name="moreItems[${i}][rate]" id="rate${i}" placeholder="Rate" readonly>
                            </div>
                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                <label>Discount %* </label>
                                <input type="text"  class="form-control" name="moreItems[${i}][discount]" id="discount${i}" placeholder="Discount">
                            </div>
                           
                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                <label> IGST ( % ) </label>
                                <input type="hidden" name="moreItems[${i}][gst]" id="gst-id${i}" index="${i}" value="">
                                <input type="text" class="form-control" name="igst" id="igst${i}" value="" readonly>
                            </div>
                            <div class="form-group col-sm-12 col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                <label> SGST ( % ) </label>
                                <input type="text" class="form-control" name="sgst" id="sgst${i}" value="" readonly>
                            </div>
                            <div class="form-group col-sm-12 col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                <label> CGST ( % ) </label>
                                <input type="text" class="form-control" name="cgst" id="cgst${i}" value="" readonly>
                            </div>
                            <button name="remove" id="${i}" class="btn btn-danger btn_remove" style="height:38px;margin-top:28px;">X</button>
                        </div>
                    </td>                        
                </tr>`);
                initSelect2();
                $(".manufacturing_date").datepicker({
                    format: " dd-mm-yyyy",
                    autoclose:true
                });
                $(".expiry_date").datepicker({
                    format: " dd-mm-yyyy",
                    autoclose:true
                });
                $(".manufacturing_date").datepicker("setDate", new Date());
               
            });
            $(document).on('click','.btn_remove', function(){
                var button_id = $(this).attr("id");
                $("#row"+button_id+"").remove();
            });
        });
       
            $(function(){
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
            });
            function initSelect2() {
                var oef_id = $('#oef_id').val();
                //alert(oef_id);
                $(".product").select2({
                    placeholder: 'Choose one',
                    searchInputPlaceholder: 'Search',
                    minimumInputLength: 6,
                    allowClear: true,
                    ajax: {
                        url: "{{ url('fgs/OEFproductsearch') }}/"+oef_id,
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
                    $("#Itemdescription"+select_id+"").text('');
                    $("#Itemtype"+select_id+"").val('');
                    $("#Itemdescription"+select_id+"").val('');
                    $("#rate"+select_id+"").val('');
                    Itemdescription1
                    let res = $(this).select2('data')[0];
                        if(typeof(res) != "undefined" ){
                            if(res.type_name){
                                $("#Itemtype"+select_id+"").val(res.type_name);
                            }
                            if(res.unit_name){
                                $('#Unit').val(res.unit_name);
                                $("#unit-div"+select_id+"").text(res.unit_name);
                            }
                            if(res.discription){
                                $("#Itemdescription"+select_id+"").val(res.discription);
                            }
                            if(res.hsn_code){
                                $("#hsn_code"+select_id+"").val(res.hsn_code);
                            }
                            if(res.sales){
                                $("#rate"+select_id+"").val(res.sales);
                            }
                            if(res.gst_id){
                                $("#gst-id"+select_id+"").val(res.gst_id);
                            }
                            if(res.igst){
                                $("#igst"+select_id+"").val(res.igst);
                            }
                            if(res.cgst){
                                if(res.cgst==0)
                                $("#cgst"+select_id+"").val(0);
                                else
                                $("#cgst"+select_id+"").val(res.cgst);
                            }
                            if(res.sgst){
                                $("#sgst"+select_id+"").val(res.sgst);
                            }
                            if(res.gst_id){
                                $("#gst_id"+select_id+"").val(res.gst_id);
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
