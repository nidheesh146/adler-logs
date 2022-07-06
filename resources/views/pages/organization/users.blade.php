@extends('layouts.default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
<?php
$type = [1=>'State',2=>'District',3=>'Mekhala',4=>'Unit',5=>'Agent'];
?>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{url('organization/'.$org)}}" >Organization</a></span>
                    <span>Users</span>
                </div>
            <h4 class="az-content-title" style="font-size: 20px;">{{$type[$permission['type_id']]}} - {{$permission['org_name']}}'s users
            
            
            
           @if (in_array('user.add', config('permission')))
            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{ url('organization/add-user/' . $org.'/'.$orgid) }}'" class="badge badge-pill badge-dark "><i
                        class="fas fa-plus"></i> Add user  </button>
             @endif           
            
            </h4>
              

            
            <div class="az-dashboard-nav">
                    @include('includes.user-nav')

                    <nav class="nav">
                        <a class="nav-link" href="#"></a>
                        <a class="nav-link" href="#"><i class="fas fa-ellipsis-h"></i></a>
                    </nav>




                    
                </div>
                <div class="row row-sm mg-b-20 mg-lg-b-0">


                    <div class="table-responsive" style="margin-bottom: 13px;">
                        <table class="table table-bordered mg-b-0">
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <form>
                                        <div class="row filter_search">
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label> Emp ID</label>
                                                <input type="text" name="emp_id"  value="{{request()->get('emp_id')}}"  class="form-control"
                                                    placeholder="Employee ID">
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label> Name</label>
                                                <input type="text" name="name"  value="{{request()->get('name')}}"  class="form-control"
                                                    placeholder="Name">
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label>Email *</label>
                                                <input type="email" name="email"  value="{{request()->get('email')}}"   class="form-control" placeholder="Email">
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label>Phone</label>
                                                <input type="type"  name="phone"  value="{{request()->get('phone')}}"  class="form-control"
                                                    placeholder=" Phone number">
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                              <label>Designation</label>
                                              <input type="text"  name="designation"  value="{{request()->get('designation')}}"  class="form-control"
                                                  placeholder="Designation">
                                          </div>
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2"
                                                style="padding: 0 0 0px 6px;">
                                                <label style="    width: 100%;">&nbsp;</label>
                                                <button type="submit" class="badge badge-pill badge-primary"
                                                style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                              @if(count(request()->all('')) > 1)
                                                <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                              @endif
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                    </th>
                            </tbody>
                        </table>
                    </div>
                </div>

                @if (Session::get('success'))
                <div class="alert alert-success " style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                </div>
            @endif
                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Designation</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            
                            @foreach ($data['users'] as $users)
                            <tr>
                            <th>{{$users->employee_id}}</th>
                            <th>{{$users->f_name}} {{$users->l_name}}</th>
                            <td>{{$users->email}}</td>
                            <td>{{$users->phone}}</td>
                            <td>{{$users->designation}}</td>
                            <td>{{$users->role_name}}</td>
                                <td>
                                  <button data-toggle="dropdown" class="badge {{($users->status == 1 ) ? 'badge-success' : 'badge-danger' }}">{{($users->status == 1 ) ? 'Active' : 'Deactive' }} <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                  <div class="dropdown-menu">
                                    @if (in_array('user.edit', config('permission')))
                                       <a href="{{ url('organization/edit-user/' . $org.'/'.$orgid.'/'.$Controller->hashEncode($users->user_id))}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
                                    @endif
                                    @if (in_array('user.delete', config('permission')))
                                        <a href="{{url('organization/action-user/'.$org.'/'.$orgid.'/'.$Controller->hashEncode($users->user_id).'/action')}}" class="dropdown-item"><?=($users->status == 1) ? '<i class="fas fa-times"></i> Deactive' : '<i class="fas fa-check"></i> Active' ;?></a>
                                    @endif
                                    @if (in_array('user.delete', config('permission')))
                                       <a href="{{url('organization/delete-user/'.$org.'/'.$orgid.'/'.$Controller->hashEncode($users->user_id).'/delete')}}"onclick="return confirm('Are you sure want to delete this user ? ')" class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>
                                    @endif
                                </div>
                            </td>
                             
                            </tr>
                            @endforeach 



                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- az-content-body -->
    </div>
    </div><!-- az-content -->



    <script src="<?=url('');?>/lib/jquery/jquery.min.js"></script>
    <script src="<?=url('');?>/lib/ionicons/ionicons.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?=url('');?>/js/azia.js"></script>
    <script src="<?=url('');?>/lib/jquery-ui/ui/widgets/datepicker.js"></script>
    <script src="<?=url('');?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?=url('');?>/lib/spectrum-colorpicker/spectrum.js"></script>
    <script src="<?=url('');?>/lib/select2/js/select2.min.js"></script>
    <script src="<?=url('');?>/lib/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
    <script src="<?=url('');?>/lib/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js"></script>
    <script src="<?=url('');?>/lib/jquery-simple-datetimepicker/jquery.simple-dtpicker.js"></script>
    <script src="<?=url('');?>/lib/pickerjs/picker.min.js"></script>

    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>
    
      <script>
     $("#commentForm").validate({
            rules: {
              pro_pic:{
                extension: "jpeg|png|jpg",
              },
              f_name:{
                  required: true,
                  minlength: 1,
                  maxlength: 115
               },
               l_name:{
                  required: true,
                  minlength: 1,
                   maxlength: 115
               },
               employee_id:{
                 // required: true,
                   minlength: 1,
                   maxlength: 115
               },
               phone:{
                   required: true,
                   number: true,
                   minlength: 10,
                   maxlength: 12
               },
               designation:{
                 required: true,
                  minlength: 1,
                  maxlength: 115
               },
               address:{
                 required: true,
                 minlength: 1,
                 maxlength: 300
               },
               email:{
                  email: true,
                  remote: "<?=url('');?>/check-email-profile?module=profile_reg" 
               },
               password:{
                  minlength: 5,
                  maxlength: 12
               },
               c_password:{
                equalTo : '[name="password"]'
               }
            },
            messages: {
            email: {
                remote:"given email address is already taken",
            },
  
           }
     
          });

          </script>


@stop
