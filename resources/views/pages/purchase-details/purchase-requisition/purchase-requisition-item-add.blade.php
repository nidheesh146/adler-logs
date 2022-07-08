@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="{{url('inventory/get-purchase-reqisition')}}" style="color: #596881;">PURCHASE DETAILS</a></span> 
                <span><a href="{{url('inventory/get-purchase-reqisition')}}" style="color: #596881;">PURCHASE REQISITION</a></span>
                <span><a href="">{{ request()->pr_id? 'Edit' : 'Add' }} purchase reqisition master</a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">{{ request()->pr_id? 'Edit' : 'Add' }} purchase reqisition master</h4>
            <div class="az-dashboard-nav">
                <nav class="nav">
                    <a class="nav-link    " href="{{url('inventory/add-purchase-reqisition?pr_id='.request()->pr_id)}}">Purchase reqisition master </a>
                <a class="nav-link  active" @if(request()->pr_id) href="{{url('inventory/get-purchase-reqisition-item?pr_id='.request()->pr_id)}}" @endif >  Purchase reqisition item </a>
                     <a class="nav-link  " href="http://kssp.com/order-return"> </a>
                </nav>
           
            </div>

			<div class="row">
                    
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                   
                                          
                   
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" id="commentForm" novalidate="novalidate">
                      <input type="hidden" name="_token" value="3tPVZlU0KhPxPciwFoMILtAvlF3QleCcMJuoiRRS">  

                        
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Basic details  </label>
                                <div class="form-devider"></div>
                            </div>
                        </div>

                        <div class="row">


                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">First name *</label>
                                <input type="text" class="form-control" name="f_name" value="" placeholder="Enter first name">
                            </div>

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Last name *</label>
                            <input type="text" class="form-control" value="" name="l_name" placeholder="Enter last name">
                            </div><!-- form-group -->


                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Organization Email *</label>
                                <input type="email" value="" class="form-control" name="email" placeholder="Enter Email">
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Mobile Number *</label>
                                <input type="text" value="" class="form-control" name="phone" placeholder="Enter mobile Number">
                            </div><!-- form-group -->


                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Organization name (school / shop etc.)*</label>
                                <input type="text" value="" class="form-control" name="house_name" placeholder="school / shop etc...">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Local place / Via </label>
                                <input type="text" value="" class="form-control" name="LocalPlace" placeholder="Local Place name / Via ">
                            </div><!-- form-group -->


                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Place</label>
                                <input type="text" id="_place" name="place" readonly="" value="" class="form-control" placeholder=" Place">
                            </div><!-- form-group -->
                 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label> District 
                                 </label>
                             <input type="text" id="_district" readonly="" class="form-control" value="" name="postoffice" placeholder="Enter Post Office">
                             </div>

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label> State 
                                 </label>
                               <input type="text" id="_state" readonly="" class="form-control" value="" name="postoffice" placeholder="State">
                             </div>
                          


                        </div> 
                      

              
            
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><i class="fas fa-save"></i> Save</button>
                            </div>
                        </div>
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


<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>

<script>
  $(function(){
    'use strict'

    $('#example1').DataTable({
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
        lengthMenu: '_MENU_ items/page',
      }
    });

    
  });
</script>


@stop