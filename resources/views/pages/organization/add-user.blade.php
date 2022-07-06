@extends('layouts.default')
@section('content')
    @inject('Controller', 'App\Http\Controllers\Controller')
    <?php $type = [1 => 'State', 2 => 'District', 3 => 'Mekhala', 4 => 'Unit', 5 => 'Agent']; ?>

    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('organization/' . $org) }}" >Organization</a></span>
                    <span><a href="{{ url('organization/users/' . $org . '/' . $orgid) }}"
                            >USERS</a></span>
                    <span>Create user</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;"> {{ $user_id ? 'Update' : 'Create a new' }} user (
                    {{ $type[$permission['type_id']] }} - {{ $permission['org_name'] }} )</h4>


                <div class="row row-sm mg-b-20 mg-lg-b-0">
                    @if (Session::get('success'))
                        <div class="alert alert-success " style="width: 100%;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                        </div>
                    @endif
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="post" style=" border: 1px solid rgba(28, 39, 60, 0.12);padding: 29px;" id="commentForm"
                        class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i
                                        class="fas fa-address-card"></i> Basic details</label>
                                <div class="form-devider"></div>
                            </div>

                        </div>

                        <div class="row">


                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label for="exampleInputEmail1">First name *</label>
                                <input type="text" name="f_name" class="form-control" placeholder="Enter first name"
                                    value="{{ $user_id ? $data['user_data']['f_name'] : '' }}">
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label for="exampleInputEmail1">Last name *</label>
                                <input type="text" name="l_name" class="form-control" placeholder="Enter last name"
                                    value="{{ $user_id ? $data['user_data']['l_name'] : '' }}">
                            </div>

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ $user_id ? $data['user_data']['phone'] : '' }}"
                                    placeholder="Enter phone number">
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Employee ID</label>
                                <input type="text" name="employee_id" class="form-control"
                                    value="{{ $user_id ? $data['user_data']['employee_id'] : '' }}"
                                    placeholder="Enter employee ID">
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Designation</label>
                                <input type="text" name="designation" class="form-control"
                                    value="{{ $user_id ? $data['user_data']['designation'] : '' }}"
                                    placeholder="Enter Designation">
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Role Permission *</label>

                                <select name="role" class="form-control">
                                    <option value="">Choose one</option>
                                    @foreach ($data['role'] as $role)
                                        <option {{$user_id ? ($data['user_data']['role_permission'] == $role['role_id']) ? 'selected' : '' : ''}} value="{{ $role['role_id'] }}">{{ $role['role_name'] }}</option>
                                    @endforeach
                                </select>
                                @if (in_array('role.list', config('permission')))
                                <a href="{{ url('organization/role/' . $org . '/' . $orgid) }}" target="_blank" style="
                                        float: right;
                                        font-size: 10px;
                                    ">Add / edit / view role permission </a>
                                @endif
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Status</label>

                                <select class="form-control " name="action">
                                    <option value="1" {{$user_id ? ($data['user_data']['status'] == 1) ? 'selected' : '' : ''}}>Active</option>
                                    <option value="0"  {{$user_id ? ($data['user_data']['status'] == 0) ? 'selected' : '' : ''}}>Deactive</option>
                                </select>

                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label> Date of hire</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input id="dateMask" type="text"
                                        value="{{ $user_id ? ($data['user_data']['date_of_hire'] ? date('d/m/Y', strtotime($data['user_data']['date_of_hire'])) : '') : '' }}"
                                        name="dateofhire" class="form-control" placeholder="MM/DD/YYYY">
                                </div><!-- input-group -->
                            </div>


                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Address *</label>
                                <textarea class="form-control" name="address"
                                    placeholder="Enter Address">{{ $user_id ? $data['user_data']['address'] : '' }}</textarea>
                            </div>

                        </div>


                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i
                                        class="fas fa-user-lock"></i> Login details </label>
                                <div class="form-devider"></div>
                            </div>
                        </div>


                        <div class="row ">

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Email *</label>
                                <input type="text" name="email" class="form-control"
                                    value="{{ $user_id ? $data['user_data']['email'] : '' }}" placeholder="Enter  email">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Password *</label>
                                <input type="text" name="password"
                                    value="{{ $user_id ? $Controller->decrypt($data['user_data']['password']) : '' }}"
                                    class="form-control" placeholder="Enter password">
                            </div><!-- form-group -->


                        </div>


                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><i
                                        class="fas fa-save"></i> {{ $user_id ? 'Update' : 'Submit' }}</button>
                            </div>
                        </div>
                    </form>

                </div>



            </div><!-- az-content-body -->
        </div>
    </div><!-- az-content -->

               <script src="<?= url('') ?>/lib/jquery/jquery.min.js"></script>
                <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
                <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
                <script src="<?= url('') ?>/js/azia.js"></script>
                <script src="<?= url('') ?>/lib/jquery-ui/ui/widgets/datepicker.js"></script>
                <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
                <script src="<?= url('') ?>/lib/spectrum-colorpicker/spectrum.js"></script>
                <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
                <script src="<?= url('') ?>/lib/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
                <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js"></script>
                <script src="<?= url('') ?>/lib/jquery-simple-datetimepicker/jquery.simple-dtpicker.js"></script>
                <script src="<?= url('') ?>/lib/pickerjs/picker.min.js"></script>
                
                <script src="<?= url('') ?>/js/jquery.validate.js"></script>
                <script src="<?= url('') ?>/js/additional-methods.js"></script>
    @if (!$user_id)
            <script>
                  // Additional code for adding placeholder in search box of select2
                  (function($) {


        $("#commentForm").validate({
            rules: {
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
               role:{
                 required: true,
         
               },
               address:{
                 required: true,
                 minlength: 1,
                 maxlength: 300
               },
               email:{
                  required: true,
                  email: true,
                  remote: "<?= url('') ?>/check-email-profile?module=user-add" 
               },
               password:{
                required: true,
                  minlength: 5,
                  maxlength: 12
               },
            },
            messages: {
            email: {
                remote:"given email address is already taken",
            },

           }

          });

                  })(window.jQuery);
                </script>
@else
    <script>
        // Additional code for adding placeholder in search box of select2
        (function($) {

    $("#commentForm").validate({
    rules: {
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
     role:{
       required: true,

     },
     address:{
       required: true,
       minlength: 1,
       maxlength: 300
     },
     email:{
        required: true,
        email: true,
        remote: "<?= url('') ?>/check-email-profile?module=user-edit&user_id=<?=$user_id;?>" 
     },
     password:{
      required: true,
        minlength: 5,
        maxlength: 12
     },
    },
    messages: {
    email: {
      remote:"given email address is already taken",
    },

    }

    });

        })(window.jQuery);
      </script>
    @endif

                <script>
                  $(function(){
                    'use strict'
                   // $('#phoneMask').mask('9999999999');


                    $('.select2').select2({
                        placeholder: 'Choose one',
                        searchInputPlaceholder: 'Search'
                      });

                      $('#dateMask').mask('99/99/9999');

                  });
                </script>
@stop
