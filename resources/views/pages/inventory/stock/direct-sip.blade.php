@extends('layouts.default')
@section('content')

@inject('stock', 'App\Http\Controllers\Web\PurchaseDetails\StockController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Stock Issue To Production - Direct</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Stock Issue To Production - Direct
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
        <div class="row row-sm mg-b-20 mg-lg-b-0">
            <div class="table-responsive" style="margin-bottom: 13px;">
                
            </div>
        </div>
		<form method="post" action="">
            {{ csrf_field() }}
            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="exampleInputEmail1">Item Code*</label>
                    <select class="form-control  item_code" name="item_code">
                    </select> 
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label>Item Description </label>
                    <textarea type="text" class="form-control" name="item_description" id="item_description" placeholder="Item Description" readonly></textarea>
                </div><!-- form-group -->
                <br/>
            </div>
            <div class="form-devider"></div>
            <div class="hiddendiv" style="">
                <div class ="lotcards">
                </div>
                <div class ="batchcards">
                </div>
            </div>
            <div class="form-devider"></div>
            <div class="row savebtn" style="display:none;">
           
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <button type="submit" class="btn btn-primary btn-rounded submitbtn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
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
    $('.item_code').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true,
          ajax: {
          url: "{{ url('inventory/itemcodesearch') }}",
          processResults: function (data) {
            return { results: data };

          }
        }
    }).on('change', function (e) 
    {
         $("#item_description").text('');
        let res = $(this).select2('data')[0];
        if(typeof(res) != "undefined" )
        {
            if(res.discription){
                $("#item_description").text(res.discription);
            }
            $.get("{{ url('inventory/stock/fetchBatchCards') }}?item_id="+res.id,function(data)
            {
                //alert('kk');
                $('.batchcards').html(data['batchcards']);
                $('.lotcards').html(data['lotcards']);
                $('.spinner-button').show();
                $('.savebtn').show();
            });
        }

    });  

    $('.submitbtn').on('click', function (e) {
        var batch_tot = 0;
        var lot_qty = parseInt($('.lot-radio:checked').attr('lotqty'));
        //alert(lot_qty);
        $(".batchcard-checkbox:checked").each(function() {
            batch_tot= batch_tot+parseInt($(this).attr('batchqty'));
        });
        //alert(batch_tot);
       if(batch_tot>lot_qty)
       {
            e.preventDefault();
            alert('Total selected batch item quantity is greated than selected Lotcard')
       }
       else
       {
            form.submit();
       }
    });
     
          

</script>
@stop