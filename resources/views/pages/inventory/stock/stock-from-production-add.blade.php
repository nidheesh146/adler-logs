@extends('layouts.default')
@section('content')

@inject('stock', 'App\Http\Controllers\Web\PurchaseDetails\StockController')
<style>
  .select2-container .select2-selection--single
  {
    width:475px;
  }
</style>
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Stock Return From Production</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Stock Return From Production
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
        <!--div class="row">
          <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
              <label for="exampleInputEmail1">Item Types*</label><br/>
              <input name="rdio" type="radio" value="direct" style="height:18px;width:20px;"> Direct 
              &nbsp;&nbsp;&nbsp;&nbsp;
              <input name="rdio" type="radio" value="indirect" style="height:18px;width:20px;"> Indirect
              
          </div>
        </div-->
        <form method="post" action="{{url('inventory/stock/return-FromProductionAdd')}}" id="direct-form">
            {{ csrf_field() }}
                <div class="row">
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <label for="exampleInputEmail1">Item Code*</label>
                      <select class="form-control  item_code" name="item_code">
                      </select> 
                  </div>
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <label for="exampleInputEmail1">Item Types*</label>
                      <input type="text" class="form-control item_type" id="item_type"  readonly>
                      <input type="hidden" class="form-control item_type" id="item_type" name="item_type" >
                      <!-- <input name="rdio" type="radio" value="direct" style="height:18px;width:20px;"> Direct 
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <input name="rdio" type="radio" value="indirect" style="height:18px;width:20px;"> Indirect -->
                  </div>
                </div>
                <div class="form-devider"></div>
                <div class="visible-for-direct row" style="display:none;"> 
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <label for="exampleInputEmail1">Lot Card</label>
                      <select class="form-control  lotcard" name="lotcard">
                        <option>Select One</option>
                      </select> 
                  </div>
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <label for="exampleInputEmail1">SIP Number*</label>
                      <input type="text" class="form-control sip_number" name="sip_number" readonly>
                      <input type="hidden" class="form-control sip_id" name="sip_id" >
                      <!-- <input name="rdio" type="radio" value="direct" style="height:18px;width:20px;"> Direct 
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <input name="rdio" type="radio" value="indirect" style="height:18px;width:20px;"> Indirect -->
                  </div>
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <label for="exampleInputEmail1">Batch Card*</label><br/>
                      <select class="form-control  batchcard" name="batchcard">
                        <option>Select One</option>
                      </select> 
                  </div>
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <label for="exampleInputEmail1">Item Quantity issued to Production*</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control qty_to_production" name="qty_to_production"   aria-describedby="unit-div1" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text unit-div" id="unit-div1">Unit</span>
                        </div>
                      </div>
                  </div>
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <label for="exampleInputEmail1">Item Quantity return from Production*</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control return_qty" name="return_qty"   aria-describedby="unit-div2" >
                        <div class="input-group-append">
                            <span class="input-group-text unit-div" id="unit-div2">Unit</span>
                        </div>
                      </div>
                    </div>
                </div>   
                <div class="visible-for-indirect row" style="display:none;"> 
                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <label for="exampleInputEmail1">SIP Number*</label><br/>
                      <select class="form-control  sipnumber" name="sipnumber">
                        <option>Select One</option>
                      </select> 
                    </div>
                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <label for="exampleInputEmail1">Item Quantity issued to Production*</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control qty_to_production1" name="qty_to_production1"   aria-describedby="unit-div3" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text unit-div" id="unit-div3">Unit</span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <label for="exampleInputEmail1">Item Quantity return from Production*</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control return_quantity" name="return_quantity"   aria-describedby="unit-div4">
                        <div class="input-group-append">
                            <span class="input-group-text unit-div" id="unit-div4">Unit</span>
                        </div>
                      </div>
                    </div>

                </div>    
           <div class="form-devider"></div>
            <div class="row save-btn" style="">
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <button type="submit" class="btn btn-primary btn-rounded submit-btn" id="submit-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
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
    //$('.item_code').select2();
    $('.az-toggle').on('click', function(){
          $(this).toggleClass('on');
        });
    $(".item_code").select2({
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
    }).on('change', function (e) 
    {
        var select_id = $(this).attr("id");
        $("#Itemtype").val('');
        $("#Itemdescription").val('');
        $('.batchcard option:gt(0)').remove();
        $('.lotcard option:gt(0)').remove();
        $('.sipnumber option:gt(0)').remove();
        $('.sip_number').val('');
        $('.sip_id').val('');
        $('.qty_to_production').val('');
        $('.qty_to_production1').val('');
        let res = $(this).select2('data')[0];
        if(typeof(res) != "undefined" )
        {
            if(res.type_name)
            {
              $(".item_type").val(res.type_name);
              if(res.type_name=='Direct Items')
              {
                $.get("{{ url('inventory/stock/fetchDirectItemLotCards') }}?row_material_id="+res.id,function(response)
                {
                  if(response.length>0)
                  {
                    $('.visible-for-direct').show();
                    $('.visible-for-indirect').hide();
                    $.each(response,function(key, value)
                    {
                      $(".lotcard").append('<option  value=' + value.id + '>' + value.lot_number + '</option>');
                    });
                  }
                  else
                  {
                    // $('.submit-btn').attr('disabled','disabled');
                    alert("This item didn't issued to Production");
                  }
                });
              }
              else
              {
                $.get("{{ url('inventory/stock/fetchSIPinfoIndirect') }}?row_material_id="+res.id,function(response){
                  if(response.length>0)
                  {
                    $('.visible-for-direct').hide();
                    $('.visible-for-indirect').show();
                    $.each(response,function(key, value)
                    {
                      $(".sipnumber").append('<option  qty=' + value.qty_to_production +' value=' + value.sip_id + '>' + value.sip_number + '</option>');
                    });
                    
                  }
                  else
                  {
                    // $('.submit-btn').attr('disabled','disabled');
                    alert("This item didn't issued to Production");
                  }
                 
                });

              }
            }
            if(res.unit_name)
            {
              //$('#Unit').val(res.unit_name);
              $("#unit-div1").text(res.unit_name);
              $("#unit-div2").text(res.unit_name);
              $("#unit-div3").text(res.unit_name);
              $("#unit-div4").text(res.unit_name);
            }   
        }
    });
    $('.lotcard').on('change',function(e){
      var lot_id = $(this).val();
      $('.batchcard option:gt(0)').remove();
      $.get("{{ url('inventory/stock/lotcardInfo') }}?lot_id="+lot_id,function(response){
        if(response)
        {
          $('.sip_number').val(response['sip']['sip_number']);
          $('.sip_id').val(response['sip']['id']);
          $.each(response['batchcards'],function(key, value)
          {
            //alert('kkk');
            $(".batchcard").append('<option qty=' + value.qty_to_production +' value=' + value.batchcard_id + '>' + value.batch_no + '</option>');
          });
        }
      });
    });
    $('.batchcard').on('change',function(e){
        var qty = $(this).children(":selected").attr('qty');
        //alert(qty);
        $('.qty_to_production').val(qty);
        
    });
    $('.sipnumber').on('change',function(e){
        var qty = $(this).children(":selected").attr('qty');
        //alert(qty);
        $('.qty_to_production1').val(qty);
        
    });
    /*$('#submit-btn').on('click', function(e){    
        var qty_to_production = parseFloat($('.qty_to_production').val());
        var qty_to_production1 = parseFloat($('.qty_to_production1').val());
        var return_quantity  = parseFloat($('.return_quantity').val());
        if(isNaN(qty_to_production1))
        {
          if(qty_to_production>return_quantity)
          {
            alert(qty_to_production1);
            alert('Return quantity do not exceed Production  quantity...');
            e.preventDefault();
          }
          else
          {
            e.preventDefault();
          }
        }
        else
        {
          if(qty_to_production1>return_quantity)
          {
          alert('Return quantity do not exceed Production  quantity...');
          e.preventDefault();
          }
          else
          {
            e.preventDefault();
          }
        }

       
    })*/


</script>
@stop