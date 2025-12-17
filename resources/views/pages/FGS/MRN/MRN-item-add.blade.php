@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="" style="color: #596881;">
                    Material Receip Note(MRN)</a></span>
                    <span><a href="" style="color: #596881;">
                            MRN Item</a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                MRN Item
                </h4>
                <div class="az-dashboard-nav">
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <form method="post" id="commentForm" novalidate="novalidate" autocomplete="off">
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
                                                    <input type="text" readonly class="form-control" name="hsncode"
                                                        id="hsncode1" placeholder="HSN Code">
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
                                                <input type="hidden" id="is_sterile1" value="">
                                               {{--@if($product_cat->product_category==3)
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label for="exampleInputEmail1">Batch No* </label>
                                                    <input type="text"  class="form-control" name="moreItems[0][batch_no]" 
                                                        placeholder="Batch No">
                                                    <!-- <select class="form-control batch_number batch_no1" id="1"
                                                        name="moreItems[0][batch_no]" id="batch_no1">
                                                    </select> -->
                                                    <!-- <select class="form-control batch_number batch_no1" index="1"
                                                        name="moreItems[0][batch_no]" >
                                                    </select> -->
                                                    
                                                </div>
                                               @else--}}
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label for="exampleInputEmail1">Batch No* </label>
                                                    <!-- <select class="form-control batch_number batch_no1" id="1"
                                                        name="moreItems[0][batch_no]" id="batch_no1">
                                                    </select> -->
                                                    <select class="form-control batch_number batch_no1" index="1"
                                                        name="moreItems[0][batch_no]" >
                                                    </select>
                                                    
                                                </div>
                                                {{--@endif--}}
                                                </div>
                                            <div class="row">
                                           
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label>Quantity * </label>
                                                    <input type="number"  class="form-control" name="moreItems[0][qty]"
                                                        id="stock_qty1" placeholder="Stock Qty">
                                                </div>
                                            
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2"
                                                    style="float:left;">
                                                    <label>UOM </label>
                                                    <input type="text"  class="form-control" readonly name="uom"
                                                        id="uom1" placeholder="Nos">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label>Date of Mfg. * </label>
                                                    <input type="text"  class="form-control datepicker manufacturing_date" name="moreItems[0][manufacturing_date]" value="{{date('d-m-Y')}}"
                                                        id="manufacturing_date1" placeholder="Date of Mfg.">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label>Date of Expiry * </label>
                                                    @php $date= date('Y-m-d', strtotime('+5 years')) @endphp
                                                    <input type="text"  class="form-control datepicker expiry_date" name="moreItems[0][expiry_date]" value="{{date('d-m-Y', strtotime($date .' -2 day'))}}"
                                                        id="expiry_date1" placeholder="Date of Expiry">
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
                                    <input type="hidden" value="{{$product_cat->product_category}}" id="product_cat" name="product_cat">
                                    <button type="button" name="add" id="add" class="btn btn-success btn-xs" style="height:38px;float:right;margin-right:19px;" >
                                    <i class="fas fa-plus"></i></button>
                                </div>
                                {{--<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label for="exampleInputEmail1">Remarks  *</label>
                                    <textarea type="text" class="form-control" name="remarks" value="" placeholder=""></textarea>
                                </div>--}}
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
        $(document).ready(function() {
            $('form').submit(function() {
                $(this).find(':submit').prop('disabled', true);
            });
        });
  var divid = "";


function getsearch(){
 return   table.search();
}
// $(".manufacturing_date").datepicker({
//                     format: " dd-mm-yyyy",
//                     autoclose:true,
//                     endDate: new Date()
//                 });
   
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
    
        // $("#manufacturing_date"+select_id+"").datepicker({
                                //     format: " dd-mm-yyyy",
                                //     autoclose:true
                                // });
                                // //$("#manufacturing_date"+select_id+"").datepicker('setEndDate', new Date());
                                // $("#manufacturing_date"+select_id+"").datepicker('setDate',new Date());
        $(document).ready(function(){
            
            initProductSelect2();
            var i = 1;
            
            $('#add').click(function(){
                i++;
                const myInput = document.getElementById("product_cat");
                const inputValue = myInput.value;
                // if(inputValue==3){
                //     $('#dynamic_field').append(`
                //       <tr id="row${i}" rel="${i}">
                //       <td>
                //         <div class="row">
                //             <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                //                 <label for="exampleInputEmail1">Product code * </label>
                //                 <select class="form-control product item_code${i}" id="${i}" name="moreItems[${i}][product]" id="product">
                //                 </select>                        
                //             </div>
                //             <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                //                 <label>HSN Code * </label>
                //                 <input type="text" readonly class="form-control" name="hsncode" id="hsncode${i}" placeholder="HSN Code">
                //                 <input type="hidden" value="{{ !empty($datas) ? $datas['item']['item_type_id'] : '' }}" name="Itemtypehidden" id="Itemtypehidden">
                //             </div><!-- form-group -->
                //             <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                //                 <label>Description * </label>
                //                 <textarea type="text" readonly class="form-control" id="Itemdescription${i}"name="Description" placeholder="Description"></textarea>
                //             </div>
                //             <input type="hidden" id="is_sterile${i}" value="">
                           
                //             <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                //                 <label for="exampleInputEmail1">Batch No* </label>
                //                 <input type="text"  class="form-control" name="moreItems[${i}][batch_no]"  placeholder="Batch No">
                //             </div>
                           
                //         </div>
                //         <div class="row"> 
                //             <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                //                 <label>Quantity * </label>
                //                 <input type="number"  class="form-control" name="moreItems[${i}][qty]" id="stock_qty${i}" placeholder="Quantity">
                //             </div>
                //             <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                //                 <label>UOM </label>
                //                 <input type="text"  class="form-control" readonly name="moreItems[${i}][uom]" id="uom${i}" placeholder="NOS">
                //             </div>
                //             <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                //                 <label>Date of Mfg. * </label>
                //                 <input type="text"  class="form-control datepicker manufacturing_date" name="moreItems[${i}][manufacturing_date]" id="manufacturing_date${i}" value="">
                //             </div>
                //             <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                //                 <label>Date of Expiry * </label>
                //                 <input type="text"  class="form-control datepicker expiry_date" name="moreItems[${i}][expiry_date]" id="expiry_date${i}" value="">
                //             </div>
                //             <button name="remove" id="${i}" class="btn btn-danger btn_remove" style="height:38px;margin-top:28px;">X</button>
                //         </div>
                //     </td>                        
                // </tr>`);
                // }else{
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
                                <input type="text" readonly class="form-control" name="hsncode" id="hsncode${i}" placeholder="HSN Code">
                                <input type="hidden" value="{{ !empty($datas) ? $datas['item']['item_type_id'] : '' }}" name="Itemtypehidden" id="Itemtypehidden">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label>Description * </label>
                                <textarea type="text" readonly class="form-control" id="Itemdescription${i}"name="Description" placeholder="Description"></textarea>
                            </div>
                            <input type="hidden" id="is_sterile${i}" value="">
                           
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label for="exampleInputEmail1">Batch No* </label>
                                <select class="form-control batch_number batch_no${i}" index="${i}" name="moreItems[${i}][batch_no]" >
                                </select>                
                            </div>
                            
                        </div>
                        <div class="row"> 
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label>Quantity * </label>
                                <input type="number"  class="form-control" name="moreItems[${i}][qty]" id="stock_qty${i}" placeholder="Quantity">
                            </div>
                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                <label>UOM </label>
                                <input type="text"  class="form-control" readonly name="moreItems[${i}][uom]" id="uom${i}" placeholder="NOS">
                            </div>
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label>Date of Mfg. * </label>
                                <input type="text"  class="form-control datepicker manufacturing_date" name="moreItems[${i}][manufacturing_date]" id="manufacturing_date${i}" value="">
                            </div>
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label>Date of Expiry * </label>
                                <input type="text"  class="form-control datepicker expiry_date" name="moreItems[${i}][expiry_date]" id="expiry_date${i}" value="">
                            </div>
                            <button name="remove" id="${i}" class="btn btn-danger btn_remove" style="height:38px;margin-top:28px;">X</button>
                        </div>
                    </td>                        
                </tr>`);
                
                
                initProductSelect2();
                //initBatchSelect2();
                // $(".manufacturing_date").datepicker({
                //     format: " dd-mm-yyyy",
                //     autoclose:true,
                //     //endDate: new Date()
                // });
                // $(".expiry_date").datepicker({
                //     format: " dd-mm-yyyy",
                //     autoclose:true,
                //     //endDate: "today"
                // });
                // $(".manufacturing_date").datepicker("setDate", new Date());
               
                //$(".manufacturing_date").datepicker('setEndDate', new Date());
               
            });
            $(document).on('click','.btn_remove', function(){
                var button_id = $(this).attr("id");
                $("#row"+button_id+"").remove();
            });
        });
        // $('.batch_number').on('change', function (){
        //     var batch_id = $(this).val();
        //     var select_id = $(this).attr("index");
        //     // var element = $("option:selected", this); 
        //     // var stock_qty = element.attr("qty"); 
        //     // $("#stock_qty"+select_id+"").val(stock_qty);
        //     $.get("{{ url('fgs/fetchBatchCardQty') }}?batch_id="+batch_id,function(data){
                
        //     });
        //   // alert(select_id);
        // }); 
        $(document).on('change', '.batch_number', function (e) {
           // const inputValue = myInput.value; 
                var batch_id = $(this).val();
                var select_id = $(this).attr("index");
                $("#stock_qty"+select_id+"").val('');
                $("#stock_qty"+select_id+"").attr('max','');
                $("#stock_qty"+select_id+"-error").text('');
                $("#stock_qty"+select_id+"").removeClass("error");
                $.get("{{ url('fgs/fetchBatchCardQty') }}?batch_id="+batch_id,function(data){
                    $("#stock_qty"+select_id+"").val(data);
                    $("#stock_qty"+select_id+"").attr('max',data);
                    $("#stock_qty"+select_id+"").attr('min',0);
                });
            
    // do something 
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
            function initProductSelect2() {
                const myInput = document.getElementById("product_cat");
                const inputValue = myInput.value;
               
                $(".product").select2({
                    placeholder: 'Choose one',
                    searchInputPlaceholder: 'Search',
                    minimumInputLength: 3,
                    allowClear: true,
                    ajax: {
                        url: "{{ url('fgs/search') }}",
                        processResults: function (data) {
                            
                                return { results: data };
                        }
                    }
                    
                }).on('change', function (e) {
                    var select_id = $(this).attr("id");
                    $('#Itemcode-error').remove();
                    $("#Itemdescription"+select_id+"").text('');
                    $("#hsncode"+select_id+"").val('');
                    $("#Itemdescription"+select_id+"").val('');
                    $(".batch_number"+select_id+"").val('');
                    $("#stock_qty"+select_id+"").val('');
                    $("#stock_qty"+select_id+"").removeAttr('max');
                    $("#stock_qty"+select_id+"-error").text('');
                    Itemdescription1
                    let res = $(this).select2('data')[0];
                        if(typeof(res) != "undefined" ){
                            if(res.hsn_code){
                                $("#hsncode"+select_id+"").val(res.hsn_code);
                            }
                            if(res.unit_name){
                                $('#Unit').val(res.unit_name);
                                $("#unit-div"+select_id+"").text(res.unit_name);
                            }
                            if(res.discription){
                                $("#Itemdescription"+select_id+"").val(res.discription);
                            }                           
                            if(res.is_sterile==0){
                                //$(".expiry_date"+select_id+"").datepicker();
                                $("#is_sterile"+select_id+"").val(0);
                                $("#expiry_date"+select_id+"").val('N.A');
                                // $("#manufacturing_date"+select_id+"").datepicker({
                                //     format: " dd-mm-yyyy",
                                //     autoclose:true
                                // });
                                // //$("#manufacturing_date"+select_id+"").datepicker('setEndDate', new Date());
                                // $("#manufacturing_date"+select_id+"").datepicker('setDate',new Date());
                            }
                            else
                            {
                                //$("#manufacturing_date"+select_id+"").datepicker('setDate',new Date());
                                
                                $("#is_sterile"+select_id+"").val(1);
                                $(".expiry_date"+select_id+"").datepicker();
                                $("#expiry_date"+select_id+"").datepicker({
                                    format: " dd-mm-yyyy",
                                    autoclose:true
                                });
                                var date = new Date();
                                date.setFullYear(date.getFullYear() + 5);
                                date.setDate(date.getDate() - 2);
                                //var expiry_date = ( ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear());
                                $("#expiry_date"+select_id+"").datepicker("setDate", date);
                                //$("#expiry_date"+select_id+"").val(expiry_date);
                            }
                            $("#manufacturing_date"+select_id+"").datepicker({
                                    format: " dd-mm-yyyy",
                                    autoclose:true,
                                    "setDate": new Date(),
                                    onSelect: function(date) {
                                    $(this).change();
                                    },
                            }).on("change",function (){ 
                                    // alert('inp changed');
                                    //alert($(this).val());
                                    if($("#is_sterile"+select_id+"").val()!=0)
                                    {
                                        var date = $("#manufacturing_date"+select_id+"").datepicker('getDate');  

                                        //var date = $(this).val();
                                        //alert(date);
                                        var new_date = new Date(date);
                                        new_date.setFullYear(new_date.getFullYear() + 5);
                                        new_date.setDate(new_date.getDate() - 2);
                                        //alert(new Date(new_date));
                                        // var expiry_date = ( ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear());
                                        // $("#expiry_date"+select_id+"").val(expiry_date);
                                        $("#expiry_date"+select_id+"").datepicker("setDate", new_date);
                                    }
                            });
                            $("#manufacturing_date"+select_id+"").datepicker("setDate", new Date());
                            $("#manufacturing_date"+select_id+"").datepicker('setEndDate', new Date());
                            $.get("{{ url('fgs/fetchProductBatchCards') }}?product_id="+res.id,function(data)
                            {
                                $(".batch_no"+select_id+"").find('option').remove();
                                if(data.length>0)
                                {
                                    $(".batch_no"+select_id+"").append('<option>..Select One..</option>')
                                $.each(data, function(index, item) {   
                                    $(".batch_no"+select_id+"").append($("<option qty="+item.quantity+" value="+item.batch_id+">"+item.batch_no+"</option>"));
                                });
                                }
                                else
                                {
                                    alert('Out of stock...');
                                }
                            });
                       }
                    }); 
                
            }   
            
    </script>
@stop
