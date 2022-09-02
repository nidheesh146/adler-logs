@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">EMPLOYEE </a></span> 
                <span><a href="">EMPLOYEE @if(request()->id)  EDIT @else ADD @endif </a></span>
            </div>
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">Employee  @if(request()->id)  Edit @else Add @endif</h4>
            
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
                    <form method="POST" id="commentForm" autocomplete="off" enctype="multipart/form-data">
                        {{ csrf_field() }}  
                        <div class="form-devider"></div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>First Name *</label>
                                <input type="text" class="form-control"  value="{{ !empty($user) ? $user['f_name'] : '' }}" name="f_name" placeholder="First Name">
                                <input type="hidden" name="user_id" id="user_id" value="{{ !empty($user) ? $user['user_id'] : '' }}"> 
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Last Name *</label>
                                <input type="text" class="form-control"  value="{{ !empty($user) ? $user['l_name'] : '' }}" name="l_name" id="l_name" placeholder="Last Name">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Employee Code *</label>
                                <input type="text" class="form-control"  value="{{ !empty($user) ? $user['employee_id'] : '' }}" name="employee_code" id="employee_code" placeholder="Employee Code">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>Profile Image *</label>
                                <input type="file" class="form-control" name="profile_img"  id="profile_img" value="{{ !empty($user) ? $user['profile_img'] : '' }}" placeholder="Department">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Department *</label>
                                <select class="form-control" name="department" id="department">
                                    <option value="">--- select one ---</option>
                                    @foreach($department as $dept)
                                    <option value="{{$dept['id']}}"
                                        @if(!empty($user))  
                                        @if($user["department"]==$dept["id"])
                                            selected
                                        @endif
                                        @endif>{{$dept['dept_name']}}</option>
                                    @endforeach
                                </select>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>Designation *</label>
                                <input type="text" class="form-control" name="designation" value="{{ !empty($user) ? $user['designation'] : '' }}" id="designation" placeholder="Designation">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Role *</label>
                                <select class="form-control" name="role_permission" id="role_permission">
                                    <option value="">--- select one ---</option>
                                    @foreach($roles as $role)
                                    <option value="{{$role['role_id']}}"
                                        @if(!empty($user))  
                                        @if($user["role_permission"]==$role["role_id"])
                                            selected
                                        @endif
                                        @endif>{{$role['role_name']}}</option>
                                    @endforeach
                                </select>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>Date of hire *</label>
                                <input type="text" class="form-control datepicker" name="date_of_hire" value="{{ !empty($user) ? date('d-m-Y',strtotime($user['date_of_hire'])) : '' }}"  id="date_of_hire" placeholder="Date of hire ">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>Email *</label>
                                <input type="text" class="form-control" name="email" id="email"  value="{{ !empty($user) ? $user['email'] : '' }}" placeholder="email">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>Phone *</label>
                                <input type="text" class="form-control" name="phone"  id="phone" value="{{ !empty($user) ? $user['phone'] : '' }}" placeholder="Phone">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Address *</label>
                                <textarea value="" class="form-control" name="address" id="address" placeholder="Address">{{ !empty($user) ? $user['address'] : '' }}</textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>Username *</label>
                                <input type="text" class="form-control" name="username"  id="username" value="{{ !empty($user) ? $user['username'] : '' }}" placeholder="Username">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>Password *</label>
                                <input type="password" class="form-control" name="password"  id="password" placeholder="Password">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>Confirm Password *</label>
                                <input type="password" class="form-control" name="confirm_password"  id="confirm_password" placeholder="Password">
                            </div><!-- form-group -->
                        </div> 
            
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                @if(request()->id) 
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
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>

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
                f_name: {
                    required: true,
                },
                l_name: {
                    required: true,
                },
                employee_code: {
                    required: true,
                },
                department: {
                    required: true,
                },
                profile_img:{
                    //required: true,
                },
                designation: {
                    required: true,
                },
                date_of_hire: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                phone: {
                    required: true,
                    number:true,
                    matches:"[0-9]+",
                    minlength:10,
                     maxlength:10,
                },
                address: {
                    required: true,
                },
                username: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 30,
                    pwcheck: true ,
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password",
                }

            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });

    
  });
    $('.Product').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 4,
        allowClear: true,
        ajax: {
            url: "{{ url('batchcard/productsearch') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    });
</script>


@stop