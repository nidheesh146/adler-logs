@extends('layouts.default')
@section('content')
    @inject('Controller', 'App\Http\Controllers\Controller')
    @php
    $type = [1 => 'state', 2 => 'district', 3 => 'mekhala', 4 => 'unit', 5 => 'agent'];
    @endphp
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
        }

    </style>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('magazine/authors') }}">Authors</a></span>
                    <span>{{ $id ? 'Update' : 'Create' }} an author</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">{{ $id ? 'Update' : 'Create' }} an author
                    @if (in_array('authors.add',config('permission'))) 
                    @if ($id)
                        <button style="float: right;font-size: 14px;" onclick="document.location.href='{{ url('magazine/add-authors') }}'"
                            class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Add author </button>
                    @endif
                    @endif
                </h4>


                <div class="row">

                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12"
                        style="border: 1px solid rgba(28, 39, 60, 0.12);padding: 29px;">

                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger " role="alert" style="width: 100%;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                {{ $error }}
                            </div>
                        @endforeach

                        @if (Session::get('success'))
                            <div class="alert alert-success " style="width: 100%;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                            </div>
                        @endif

                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <form method="POST" id="commentForm">
                            {{ csrf_field() }}

                            {{-- </div> --}}
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Basic details {{ $id ? '( ID : '.$data['author']['author_id'].' )' : ''}}</label>
                                    <div class="form-devider"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label for="exampleInputEmail1">First name *</label>
                                <input type="text" class="form-control" name="f_name" value="{{$id ? $data['author']['f_name'] : ''}}"
                                        placeholder="Enter first name">
                                </div>

                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Last name *</label>
                                    <input type="text" class="form-control" value="{{$id ? $data['author']['l_name'] : ''}}" name="l_name"
                                        placeholder="Enter last name">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Email *</label>
                                    <input type="email" value="{{$id ? $data['author']['email'] : ''}}" class="form-control" name="email"
                                        placeholder="Enter email">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Phone *</label>
                                    <input type="text" value="{{$id ? $data['author']['mobile'] : ''}}" class="form-control" name="phone"
                                        placeholder="Enter phone number">
                                </div><!-- form-group -->
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Place *</label>
                                    <input type="text" name="place" value="{{$id ? $data['author']['place'] : ''}}"  class="form-control" placeholder="Enter Place">
                                </div><!-- form-group -->
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>House name</label>
                                    <input type="text" value="{{$id ? $data['author']['house_name'] : ''}}"  class="form-control" name="house_name"
                                        placeholder="Enter House name / House Number">
                                </div><!-- form-group -->
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Post Office *</label>
                                    <input type="text" class="form-control" value="{{$id ? $data['author']['post_office'] : ''}}"  name="postoffice"
                                        placeholder="Enter Post Office">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Pincode *</label>
                                    <input type="text" value="{{$id ? $data['author']['pincode'] : ''}}" class="form-control" name="pincode"
                                        placeholder="Enter Pincode">
                                </div><!-- form-group -->
                                {{-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Number of journals published </label>
                                    <input type="text" class="form-control" value="" name="journals"
                                        placeholder="Enter Number of journals published">
                                </div><!-- form-group --> --}}

                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Identity document </label>
                                    <select class="form-control  under_state" name="documents">
                                        <option value="">Choose one</option>
                                        <option value="Aadhaar Card" {{$id ? ($data['author']['documents'] == 'Aadhaar Card') ? 'selected' :'': ''}} >Aadhaar Card</option>
                                        <option value="Passport" {{$id ? ($data['author']['documents'] == 'Passport') ? 'selected' :'': ''}}>Passport</option>
                                        <option value="Driving License" {{$id ? ($data['author']['documents'] == 'Driving License') ? 'selected' :'': ''}}>Driving License</option>
                                        <option value="PAN Card" {{$id ? ($data['author']['documents'] == 'PAN Card') ? 'selected' :'': ''}}>PAN Card</option>

                                    </select>
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Document Number </label>
                                    <input type="text" value="{{$id ? $data['author']['documents_number'] : ''}}" class="form-control" name="documents_number"
                                        placeholder="Enter Pincode">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12  col-md-3 col-lg-3 col-xl-3 " style=" ">
                                    <label>Shipping address * </label>
                                    <textarea class="form-control" name="ship_address"
                                        placeholder="Enter Shipping address">{{$id ? $data['author']['shipping_address'] : ''}}</textarea>
                                </div>



                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                        <i class="fas fa-money-check-alt"></i> Bank details</label>
                                    <div class="form-devider"></div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Account number </label>
                                    <input type="text" class="form-control"  value="{{$id ? $data['author']['account_number'] : ''}}" name="account_number"
                                        placeholder="Enter account number">
                                </div><!-- form-group -->
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Bank name </label>
                                    <input type="text" class="form-control" value="{{$id ? $data['author']['bank_name'] : ''}}" name="bank_name"
                                        placeholder="Enter bank name">
                                </div><!-- form-group -->
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Branch</label>
                                    <input type="text" class="form-control" value="{{$id ? $data['author']['branch'] : ''}}" name="branch"
                                        placeholder="Enter Branch">
                                </div><!-- form-group -->
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>IFSC code</label>
                                    <input type="text" class="form-control" value="{{$id ? $data['author']['ifsc'] : ''}}" name="ifsc"
                                        placeholder="Enter IFSC code">
                                </div><!-- form-group -->
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>UPI ID</label>
                                    <input type="text" class="form-control" value="{{$id ? $data['author']['upi_id'] : ''}}" name="upi_id"
                                        placeholder="Enter UPI ID">
                                </div><!-- form-group -->
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;">
                                        <span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                            {{$id ? 'Update' : 'Submit'}}</button>
                                </div>
                            </div>
                        </form>

                    </div>
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

            <script>
              $(function(){
                'use strict'
                $('[data-toggle="tooltip"]').tooltip();
           
              });
              </script>
              @if($id)
              <script>
              $("#commentForm").validate({
                rules: {
                    f_name: {
                        required: true,
                        minlength: 1,
                        maxlength: 50
                    },
                    l_name: {
                        required: true,
                        minlength: 1,
                        maxlength: 50
                    },
                     email: {
                         email: true,
                    },
                    phone: {
                        required: true,
                        number: true,
                        minlength: 10,
                        maxlength: 12
                    },
                    place:{
                        required: true,
                        minlength: 1,
                        maxlength: 50
                    },
                    postoffice:{
                        required: true,
                        minlength: 1,
                        maxlength: 50
                    },
                    pincode:{
                        required: true,
                        number: true,
                        minlength: 6,
                        maxlength: 6
                    },
                    ship_address:{
                        required: true, 
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: "<?= url('') ?>/check-email?module=author_reg_edit&id=<?=$id;?>"
                    },
                    account_number: {
                        number: true
                    },
          
                },
                messages: {
                    email: {
                        remote: "given email address is already taken",
                    },
                },
                submitHandler: function(form) {
                    $('.spinner-button').show();
                    form.submit();
                }
            });
            </script>
            @else

            <script>
                $("#commentForm").validate({
                  rules: {
                      f_name: {
                          required: true,
                          minlength: 1,
                          maxlength: 50
                      },
                      l_name: {
                          required: true,
                          minlength: 1,
                          maxlength: 50
                      },
                       email: {
                           email: true,
                      },
                      phone: {
                          required: true,
                          number: true,
                          minlength: 10,
                          maxlength: 12
                      },
                      place:{
                          required: true,
                          minlength: 1,
                          maxlength: 50
                      },
                      postoffice:{
                          required: true,
                          minlength: 1,
                          maxlength: 50
                      },
                      pincode:{
                          required: true,
                          number: true,
                          minlength: 6,
                          maxlength: 6
                      },
                      ship_address:{
                          required: true, 
                      },
                      email: {
                          required: true,
                          email: true,
                          remote: "<?= url('') ?>/check-email?module=author_reg"
                      },
  
                      account_number: {
                          number: true
                      },
            
                  },
                  messages: {
                      email: {
                          remote: "given email address is already taken",
                      },
                  },
                  submitHandler: function(form) {
                      $('.spinner-button').show();
                      form.submit();
                  }
              });
              </script>



            @endif

@stop
