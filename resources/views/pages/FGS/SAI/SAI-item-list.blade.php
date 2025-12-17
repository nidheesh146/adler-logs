@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="" style="color: #596881;">Stock Adjustment - Increase(SAI)</a></span>
             <span><a href="">SAI Item List</a></span>
        </div>

       
        <div class="row ">
            <div class="col-lg-12 col-xl-12 mg-t-20 mg-lg-t-0">
                <!-- <div class="card card-table-one" style="min-height: 500px;"> -->
                    <h4 class="az-content-title" style="font-size: 20px;">
                        SAI Item List({{$sai_data->sai_number}})
                        <div class="right-button">
                        <button style="float: right;font-size: 14px;" class="badge badge-pill badge-info item-upload" style="font-size: 13px;" href="#" data-saiId="{{$sai_id}}" data-master="{{$sai_data->sai_number}}" data-location="{{$sai_data->location_name}}" data-toggle="modal" data-target="#uploadModal"><i class="fas fa-upload"></i> Upload</a>
                        {{--<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/SAI/add-item/'.$sai_id)}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i>
                                SAI Item
                            </button>--}}
                        <div>  
                            
                        </div>
                    </div>
                    </h4>
                    @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                    </div>
                    @endif
                    @if (Session::get('error'))
                    <div class="alert alert-danger" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('error') }}
                    </div>
                    @endif
                    <div class="row row-sm mg-b-20 mg-lg-b-0">
                        
            </div>
            <p class="az-content-text mg-b-20"></p>
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0">
                    <thead>
                        <tr>
                        <th>Product</th>
						<th>HSN Code</th>
                        <th>Description</th>
						<th>Date of Mfg.</th>
						<th>Date of Expiry</th>	
                        <th>Batch No</th>
                        <th>Quantity</th>	
                        <th>Rate</th>	
                        </tr>
                    </thead>
                    <tbody id="prbody1">
                        @foreach($sai_items as $item)
                       <tr>
                           <td>{{$item->sku_code}}</td>
                           <td>{{$item->hsn_code}}</td>
                           <td>{{$item->discription}}</td>
                           <td>
    {{ ($item['manufacturing_date'] === '1970-01-01' || $item['manufacturing_date'] === '0000-00-00') ? 'N.A.' : date('d-m-Y', strtotime($item['manufacturing_date'])) }}
</td>
                           <td>@if($item['expiry_date']!='0000-00-00') {{date('d-m-Y', strtotime($item['expiry_date']))}} @else NA  @endif</td>   
                           <td>{{$item->batch_no}}</td>
                           <td>{{$item->quantity}} Nos</td>
                           <td>{{$item->rate}}</td>
                        </tr>
                        @endforeach  
                    </tbody>
                    </table>
                    <div class="box-footer clearfix">
                    {{ $sai_items->appends(request()->input())->links() }}
                    </div>
                </div><!-- table-responsive -->
                        <!-- </div>card -->
            </div>
        </div>
	</div>
</div>
	<!-- az-content-body -->
	<!-- Modal content-->
    <div class="modal fade" id="uploadModal" role="dialog">
		<div class="modal-dialog modal-xs">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header" style="display: block;">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Upload <span id="type"></span> SAI Item<span id="sai_master"></span></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
							<form method="POST" id="commentForm" action="{{url('fgs/SAI/item-upload/'.$sai_id)}}" novalidate="novalidate" enctype='multipart/form-data'>
								{{ csrf_field() }}
								<div class="row">
                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<label for="exampleInputEmail1">Location Increase</label>
										<input type="text" required class="form-control"  value="{{$sai_data->location_name}}" id="file" readonly>
									</div>
									<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<label for="exampleInputEmail1">Select File*</label>
										<input type="file" required class="form-control file" name="file" id="file">
										<a href="{{ asset('uploads/SAI_sample.xlsx') }}" target="_blank" style="float: right; font-size: 10px;"> Download Template</a>
										<input type="hidden" name="sai_id" id="sai_id" value="{{$sai_id}}">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
											Save
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	
      

<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script>
    $(".item-upload").on("click", function() {
		// var type = $(this).data('type');
		// $('#type').html(type);
		 var sai_master = $(this).data('master');
		 $('#sai_master').html(' (' + sai_master + ')');
		var pr_id = $(this).data('prid');
		$('#pr_id').val(pr_id);
	});
</script>

@stop