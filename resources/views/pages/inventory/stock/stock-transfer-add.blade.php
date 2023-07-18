@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Stock Transfer Order</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Stock Transfer Order
		  	<div class="right-button">
			  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
				  <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
			  <div class="dropdown-menu">
			  <a href="#" class="dropdown-item">Excel</a>

			  </div> -->
				
	  		</div>
		</h4>
		<div class="az-dashboard-nav">
			<nav class="nav"> </nav>	
		</div>
        @if (Session::get('success'))
		<div class="alert alert-success " style="width: 100%;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<i class="icon fa fa-check"></i> {{ Session::get('success') }}
		</div>
		@endif
        @if (Session::get('error'))
		<div class="alert alert-danger " style="width: 100%;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<i class="icon fa fa-check"></i> {{ Session::get('error') }}
		</div>
		@endif
        @foreach ($errors->all() as $errorr)
        <div class="alert alert-danger "  role="alert" style="width: 100%;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $errorr }}
        </div>
        @endforeach 
        <div class="row row-sm mg-b-20 mg-lg-b-0">
        </div>
		<form method="post" action="{{url('inventory/stock/transfer-order')}}">
            {{ csrf_field() }}
		    <table class="table table-bordered">
                <tbody id="dynamic_field"> 
                    <tr id="row1" rel="1">
                        <td>
                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                <label for="exampleInputEmail1">Item code * </label>
                                <select class="form-control Item-code item_code1" id="1" name="moreItems[0][Itemcode]" id="Itemcode">
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label>Description * </label>
                                <textarea type="text" readonly class="form-control" id="Itemdescription1"name="Description" placeholder="Description"></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                <label>Lot Number * </label>
                                <select class="form-control lot_number" count="1" name="moreItems[0][lot_number]" id="lot_number1" style="width:140px;">
                                    <option>Select One</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2 qty" style="float:left;">
                                <label>Transfer Qty*</label>
                                <div class="input-group mb-3">
                                    <input type="number" step="0.01" class="form-control" name="moreItems[0][transfer_qty]" id="transfer_qty1"
                                        placeholder="" aria-describedby="unit-div1" min="0" max="">
                                    <div class="input-group-append">
                                        <span class="input-group-text unit-div" id="unit-div1">Unit</span>
                                    </div>
                                </div>
                                Available:<span id="available1" style="font-weight:bold;"></span>
                            </div>
                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                <label>Reason for transfer*</label>
                                <textarea type="text"  class="form-control" id="reason" name="moreItems[0][reason]" placeholder=""></textarea>
                            </div>
                            <button type="button" name="add" id="add" class="btn btn-success" style="height:38px;margin-top:28px;">
                                <i class="fas fa-plus"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br/>
            <div class="form-devider"></div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                            Save 
                    </button>
                </div>
            </div> 
        </form>
		</div>
	</div>
</div>
	<!-- az-content-body -->
	<!-- Modal content-->

	
      

<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script>
    $(".datepicker").datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        // startDate: date,
        autoclose:true
    });
    $(document).ready(function(){
            initSelect2();
            var i = 1;
            $('#add').click(function(){
                //alert('kk');
                i++;
                $('#dynamic_field').append(`
                      <tr id="row${i}" rel="${i}">
                            <td>
                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;"><label for="exampleInputEmail1">Item code * </label>
                                    <select class="form-control Item-code item_code${i}" id="${i}" name="moreItems[${i}][Itemcode]"  id="" required></select>
                                </div>
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                    <label>Description * </label>
                                    <textarea type="text" readonly  class="form-control "  name="Description" id="Itemdescription${i}"  placeholder="Description"></textarea>
                                </div>
                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                    <label>Lot Number * </label>
                                    <select class="form-control lot_number" count="${i}" name="moreItems[${i}][lot_number]" id="lot_number${i}" style="width:140px;">
                                        <option>Select One</option>
                                    </select>
                                </div>
                                <div class="col-lg-2" style="float:left;">
                                    <label>Transfer Qty*</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" value=""  name="moreItems[${i}][transfer_qty]"  id="transfer_qty${i}" placeholder="" min="0" max="" step="0.01" aria-describedby="unit-div${i}" >
                                        <div class="input-group-append"><span class="input-group-text unit-div" id="unit-div${i}">Unit</span></div>
                                    </div>
                                    Available:<span id="available${i}" style="font-weight:bold;"></span>
                                </div>
                                
                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                    <label>Reason for transfer*</label>
                                    <textarea type="text"  class="form-control" id="reason" name="moreItems[${i}][reason]" placeholder=""></textarea>
                                </div>
                                <button name="remove" id="${i}" class="btn btn-danger btn_remove" style="height:38px;margin-top:28px;">X</button>
                            </td>
                        </tr>`);
                initSelect2();
            });
            $(document).on('click','.btn_remove', function(){
                var button_id = $(this).attr("id");
                $("#row"+button_id+"").remove();
            });
        });
        function initSelect2() {
                $(".Item-code").select2({
                    placeholder: 'Choose one',
                    searchInputPlaceholder: 'Search',
                    minimumInputLength:4,
                    allowClear: true,
                    ajax: {
                        url: "{{ url('inventory/stock/item_qty_in_mac') }}",
                        processResults: function (data) {
                                return { results: data };
                        }
                    }
                }).on('change', function (e) {
                    var select_id = $(this).attr("id");
                    $('#Itemcode-error').remove();
                    $("#Itemdescription"+select_id+"").text('');
                    $("#Itemtype"+select_id+"").val('');
                    $("#Itemdescription"+select_id+"").val('');
                    $("#lot_number"+select_id+"").val('');
                    $("#available"+select_id+"").text('');
                    $("#transfer_qty"+select_id+"").text('');
                    let res = $(this).select2('data')[0];
                        if(typeof(res) != "undefined" ){
                            if(res.unit_name){
                                $('#Unit').val(res.unit_name);
                                $("#unit-div"+select_id+"").text(res.unit_name);
                            }
                            if(res.discription){
                                $("#Itemdescription"+select_id+"").val(res.discription);
                            }
                            if(res.type_name!='Direct Items')
                            {
                               
                                if(res.stock_qty)
                                {
                                    $("#available"+select_id+"").text(res.stock_qty+' '+res.unit_name);
                                    $("#transfer_qty"+select_id+"").attr('max',parseFloat(res.stock_qty));
                                    $("#lot_number"+select_id+"").attr('disabled','disabled')
                                }
                            }
                            else{
                                $("#lot_number"+select_id+"").removeAttr('disabled')
                                $.get("{{ url('inventory/stock/fetchLotCard_for_sto') }}?row_material_id="+res.id,function(response){
                                    $.each(response,function(key, value)
                                    {
                                        $("#lot_number"+select_id+"").append('<option value=' + value.lotid + '>' + value.lot_number + '</option>');
                                    });
                                });
                            }
                            // $.get("{{ url('inventory/stock/fetchSIPlist_for_sto') }}?row_material_id="+res.id,function(response)
                            // {
                            //     $.each(response,function(key, value)
                            //     {
                            //         $("#sip_number"+select_id+"").append('<option qty=' + value.available_qty +' macItemId=' + value.mac_item_id +' value=' + value.sip_id + '>' + value.sip_number + '</option>');
                            //     });
                            //     //alert(response);
                            // });
                       }
                    });   
            } 
            $('.lot_number').on('change',function(e){
                var count =$(this).attr('count');
                var lot_id = $(this).val();

                //var qty = $("#lot_number"+count+"").children(":selected").attr('qty');
                //alert(count);
                //$("#available"+count+"").text(qty);
                $.get("{{ url('inventory/stock/fetchLotStock') }}?lot_id="+lot_id,function(data)
                {
                    var unit = $("#unit-div"+count+"").text();
                    $("#available"+count+"").text(data+' '+unit);
                    $("#transfer_qty"+count+"").attr('max',parseFloat(res.stock_qty));
                });
                
            });
            
</script>
@stop