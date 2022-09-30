@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="{{url('inventory/get-purchase-reqisition')}}" style="color: #596881;">PURCHASE DETAILS</a></span> 
                <span><a href="{{url('inventory/get-purchase-reqisition')}}" style="color: #596881;">@if(request()->get('prsr')=="sr" || request()->sr_id) SERVICE REQUISITION @else PURCHASE REQUISITION @endif</a></span>
                <span><a href="">
                   @if((request()->pr_id) AND (!request()->sr_id)) 
                        Edit Purchase Requestor Details
                    @elseif((!request()->pr_id) AND (request()->sr_id))
                        Edit Service Requestor Details  
                    @elseif(request()->get('prsr')=="sr")
                        Add Service Requestor Details
                    @else
                        Add Purchase Requestor Details
                   @endif
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                    @if((request()->pr_id) AND (!request()->sr_id)) 
                        Edit Purchase Requestor Details
                    @elseif((!request()->pr_id) AND (request()->sr_id))
                        Edit Service Requestor Details  
                    @elseif(request()->get('prsr')=="sr")
                        Add Service Requestor Details
                    @else
                        Add Purchase Requestor Details
                   @endif
            </h4>
            <div class="az-dashboard-nav">
                <nav class="nav">
                    <a class="nav-link  active  " href="">@if(request()->get('prsr')=="sr" || request()->sr_id) Service @else Purchase @endif Requestor Details </a>
                     <a class="nav-link  " @if(request()->pr_id) href="{{url('inventory/get-purchase-reqisition-item?pr_id='.request()->pr_id)}}" @endif >  @if(request()->get('prsr')=="sr" || request()->sr_id) Service @else Purchase @endif Requisition Details </a>
                     <a class="nav-link  " href=""> </a>
                </nav>
           
            </div>

			<div class="row">
                    
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
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
                   
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" id="commentForm" autocomplete="off" >
               

                        {{ csrf_field() }}  
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Basic details  
                                
                                    @if(!empty($data['inv_purchase_req_master']))
                                        @if(request()->pr_id)
                                            ( PR NO : {{$data['inv_purchase_req_master']['pr_no']}} )
                                        @else
                                            ( SR NO : {{$data['inv_purchase_req_master']['pr_no']}} )
                                        @endif
                                    @endif
                                
                        
                                </label>
                                <div class="form-devider"></div>
                            </div>
                         </div>

                        <div class="row">


                            {{-- <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">PR NO: *</label>
                                <input type="text" class="form-control" name="f_name" value="" placeholder="Enter PR NO">
                            </div> --}}

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Requestor *</label>
                            <!-- <input type="text" class="form-control select2"  
                            value="{{(!empty($data['inv_purchase_req_master']) ? $data['inv_purchase_req_master']['f_name'].' '.$data['inv_purchase_req_master']['l_name']  :  (config('user')['f_name'] ? config('user')['f_name'].' '.config('user')['l_name']  : 'Requestor 1'))}}" 
                            name="Requestor" placeholder="Requestor"> -->
                                <select class="form-control select2 requestor" name="Requestor">
                                    @foreach($data['users'] as $user)
                                     <option value="{{$user['user_id']}}"
                                     @if(!empty($data['inv_purchase_req_master']))
                                       @if($user['user_id'] == $data['inv_purchase_req_master']['requestor_id'])
                                           selected
                                       @endif
                                     @endif
                                     >{{$user['f_name']}} {{$user['l_name']}}</option>
                                    @endforeach
                                </select>
                            </div><!-- form-group -->


                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Department *</label>
                                <select class="form-control select2" name="Department">
                                    <option value="">--- select one ---</option>
                                    @foreach($data['Department'] as $item)
                                     <option value="{{$item['id']}}"
                                     @if(!empty($data['inv_purchase_req_master']))
                                       @if($item['id'] == $data['inv_purchase_req_master']['department'])
                                           selected
                                       @endif
                                     @endif
                                     >{{$item['dept_name']}}</option>
                                    @endforeach
                                </select>
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Date *</label>
                            <input type="text" 
                                value="{{(!empty($data['inv_purchase_req_master'])) ? date('d-m-Y',strtotime($data['inv_purchase_req_master']['date'])) : date('d-m-Y')}}"
                                class="form-control datepicker" name="Date" placeholder="Date">
                            </div><!-- form-group -->

                        </div> 
                      

              
            
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                @if(!empty($data['response']['purchase_requisition'][0]))
                                    Update
                                @else 
                                     Save & Next
                                @endif
                                
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
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>

<script>
  $(function(){
    'use strict'

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true
    });
  //  .datepicker('update', new Date());

    $('.datepicker').mask('99-99-9999');
              

    $("#commentForm").validate({
            rules: {
                Requestor: {
                    required: true,
                },
                Department: {
                    required: true,
                },
                Date: {
                    required: true,
                },
                
                
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });

    
  });
  $('.requestor').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
    });
</script>


@stop