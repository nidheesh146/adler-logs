@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="" style="color: #596881;">Satellite Stock</a></span>
             <span><a href="">Location</a></span>
        </div>
        <br/><br/>
        <div class="row ">
            <div class="col-lg-7 col-xl-7 mg-t-20 mg-lg-t-0">
                <div class="card card-table-one" style="min-height: 500px;">
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
                    <h6 class="card-title"></h6>
                    <p class="az-content-text mg-b-20"></p>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Location Name</th>
                                    {{--<th>Zone</th>
                                    <th>Description</th>--}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data['location']))
                                    @foreach ($data['location'] as $key => $location)
                                        <tr style="">
                                            <td>{{ $location['location_name'] }}</td>
                                            {{--<td>{{ $location['zone'] }}</td>
                                            <td>{{ $location['description'] }}</td>--}}
                                            <td> <button data-toggle="dropdown" class="badge badge-primary">Active <i
                                                            class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                                <div class="dropdown-menu">
                                                    <a href="{{url('fgs/satellite-stock/location/'.$location['id'])}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
                                                    <a href="{{url('fgs/satellite-stock/location/delete/'.$location['id'])}}" onclick="return confirm('Are you sure want to delete this location?')"  class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a> 
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            {{ $data['location']->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-5 col-xl-5 mg-t-20 mg-lg-t-0">
                <div class="card card-table-one" style="min-height: 500px;">
                    <!-- @if (Session::get('success'))
                        <div class="alert alert-success " style="width: 100%;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                        </div>
                    @endif -->
                    <form  method="POST"  id="commentForm">
                        <h6 class="card-title"> {{isset($edit) ? 'Update' : 'Create' }} Satellite Stock Location </h6>
                        <p class="az-content-text mg-b-20"></p>
                        {{ csrf_field() }}   
                        <div class="row">
                            {{--<div class="form-group col-sm-12 ">
                                <label>Zone Name *</label>
                                <select name="zone" class="form-control" >
                                    <option value="">..Select One..</option>
                                    <option value="North" @if(isset($edit)) @if($edit['zone']=="North") selected @endif @endif>North</option>
                                    <option value="South" @if(isset($edit)) @if($edit['zone']=="South") selected @endif @endif>South</option>
                                    <option value="East" @if(isset($edit)) @if($edit['zone']=="East") selected @endif @endif>East</option>
                                    <option value="West" @if(isset($edit)) @if($edit['zone']=="West") selected @endif @endif>West</option>
                                </select>
                                <input type="hidden"  value="{{isset($edit) ? $edit['id'] : ''}}" name="location_id" class="form-control">
                            </div><!-- form-group -->--}}
                            <div class="form-group col-sm-12 ">
                                <label>Location Name *</label>
                                <input type="text"  value="{{isset($edit) ? $edit['location_name'] : ''}}" name="location_name" class="form-control" placeholder="Enter Location Name ">
                            </div><!-- form-group -->
                            
                            {{--<div class="form-group col-sm-12 ">
                                <label>Description *</label>
                                <textarea class="form-control" name="description" placeholder="Enter Description">{{isset($edit) ? $edit['description'] : ''}}</textarea>
                            </div><!-- form-group -->--}}
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded" style="float: right;"><i
                                    class="fas fa-save"></i>  {{isset($edit) ? 'Update' : 'Submit' }}</button>
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
                location_name:{
                  required: true,
                  minlength: 1,
                  maxlength: 20
               },
               zone:{
                  required: true,
               },
               description:{
                  required: true,
                  minlength: 1,
                   maxlength: 115
               },
              
            }

     
          });
</script>
@stop