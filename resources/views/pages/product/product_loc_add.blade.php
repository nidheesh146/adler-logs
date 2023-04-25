@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="" style="color: #596881;">Product</a></span>
             <span><a href="">Product Location</a></span>
        </div>
        <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">Product Locations</h4>
        <div class="row "> 
            <div class="col-lg-7 col-xl-7 mg-t-20 mg-lg-t-0">
                <div class="card card-table-one" style="min-height: 500px;">
                    @if (Session::get('succs'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('succs') }}
                    </div>
                    @endif
                    <h6 class="card-title">Location List</h6>
                    <p class="az-content-text mg-b-20"></p>
                    <div class="table-responsive">
                        <table class="table">
                        <thead>
                        <tr>
                             <th>Location</th>
                             <th>Action</th>

                        </tr>
                        </thead>
                             <tbody>
                             @if(isset($data['location']))
                            @foreach ($data['location'] as $key => $location)
                            <tr style="{{($location['created_org']==0) ? 'background: #3466ff1a;' : ''}}">
                            <td>{{ $location['location_name'] }}</td>
                                                
                            <td> 
                                <button data-toggle="dropdown" class="badge badge-primary">Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                            <div class="dropdown-menu">
                            
                           <a href="{{url('product/location?id='.$location['id'])}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
                            <a href="{{url('product/location?id='.$location['id'])}}" onclick="return confirm('Are you sure want to delete this location? The user cannot sign in under this location ')"  class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>
                                                       
                                                        <!-- dropdown-menu -->
                            </div>
                            </td>
                            </tr>
                             @endforeach
                         @endif

                                    </tbody>
                                </table>
                            </div><!-- table-responsive -->
                        </div><!-- card -->
                    </div>


                    <div class="col-lg-5 col-xl-5 mg-t-20 mg-lg-t-0">
                        <div class="card card-table-one" style="min-height: 500px;">
                          @if (Session::get('success'))
                          <div class="alert alert-success " style="width: 100%;">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                              <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                            </div>
                            @endif
                          <form  method="POST"  id="commentForm">
                          <h6 class="card-title"> {{isset($edit) ? 'Update' : 'Create new' }} user location </h6>
                            <p class="az-content-text mg-b-20"></p>
                            {{ csrf_field() }}   
                            <div class="row">
                                <div class="form-group col-sm-12 ">
                                    <label>Location *</label>
                                <input type="text"  value="{{isset($edit) ? $edit['location'] : ''}}" name="location" class="form-control" placeholder="Enter Location Name ">
                                </div><!-- form-group -->
                                <div class="form-group col-sm-12 ">
                                   
                                </div><!-- form-group -->

                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded" style="float: right;"><i
                                    class="fas fa-save"></i>{{isset($edit) ? 'Update' : 'Submit' }}</button>
                                </div>
                            </div>


                          </form>





                        </div><!-- card -->
                    </div>
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
    $("#commentForm").validate({
            rules: {
             location:{
                  required: true,
                  minlength: 1,
                   maxlength: 115
               },
              
            }

     
          });
</script>
@stop