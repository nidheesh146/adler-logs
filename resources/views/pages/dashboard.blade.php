@extends('layouts.subscriber-default')
@section('content')

<style>
  .select2-container .select2-selection--single {
  height: 38px !important;
  }
</style>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
            <h4 class="az-content-title" style="font-size: 20px;"> {{$id ? 'Update' : 'Add' }} Label</h4>
                <div class="az-dashboard-nav">
                    <nav class="nav">
                        <a class="nav-link  active" href="{{ url('') }}">Add Label </a>
                        <a class="nav-link " href="{{ url('label/list') }}">List Label</a>
                        {{-- <a class="nav-link " href="{{ url('subscribers-renew') }}">Subscription renew</a> --}}
                    </nav>
                    <nav class="nav">
                    </nav>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 "
                        style="border: 1px solid rgba(28, 39, 60, 0.12);padding: 29px;">
{{--                        
                        @foreach ($errors->all() as $error)
                        <div class="alert alert-danger "  role="alert" style="width: 100%;">
                           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          {{ $error }}
                        </div>
                       @endforeach
                      
                       @if (Session::get('success'))
                       <div class="alert alert-success " style="width: 100%;">
                           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                           <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                       </div>
                       @endif --}}

                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <form method="POST" id="commentForm" enctype="multipart/form-data">
                          {{ csrf_field() }}  
               
                            <div class="row"   >
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i
                                            class="fas fa-address-card"></i> Basic details </label>
                                    <div class="form-devider"></div>
                                </div>
                            </div>

                            <div class="row">

                          

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label for="exampleInputEmail1">REF *</label>
                                    <input type="text" class="form-control" name="ref"
                                value="{{$id ? $data->ref :''}}"  placeholder="Enter REF">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>LOT *</label>
                                <input type="text" class="form-control" value="{{$id ? $data->lot :''}}" name="lot" placeholder="Enter LOT ">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Sterile </label>

                                    <div class="row">

                                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <select class="form-control" name="sterile_type">
                                            <option {{$id ? ($data->sterile_type == 'R') ? 'selected' :'' :''}} value="R">R</option>
                                            <option {{$id ? ($data->sterile_type == 'N') ? 'selected' :'' :''}} value="N">N</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8">
                                        <input type="text" class="form-control" value="{{$id ? $data->sterile :''}}" name="sterile" placeholder="Enter Sterile">
                                    </div>
                                  
                                  
                                    </div>
                              
                              
                              
                              
                              
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Created Date </label>
                                    <input type="text" value="{{$id ? date('d-m-Y',strtotime($data->created_date)) :''}}" autocomplete="off"  class="form-control  datepicker" name="c_date" placeholder="Enter created date">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Expiry Date *</label>
                                    <input type="text" value="{{$id ? date('d-m-Y',strtotime($data->expiry_date)) :''}}" autocomplete="off"  class="form-control  datepicker" name="expire_date" placeholder="Enter Expiry Date">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>QTY *</label>
                                    <input type="text"  value="{{$id ? $data->qty :''}}" class="form-control" name="qty" placeholder="Enter QTY">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>MIDDLE DES1 *</label>
                                    <textarea name="middle_disc_1" class="form-control"  placeholder="Enter MIDDLE DES1" >{{$id ? $data->middle_desc_1 :''}}</textarea>
                                
                                </div><!-- form-group -->
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>MIDDLE DES2*</label>
                                    <textarea name="middle_disc_2" class="form-control"  placeholder="Enter MIDDLE DES2" >{{$id ? $data->middle_desc_2 :''}}</textarea>
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>LEFT *</label>
                                    <select class="form-control" name="position">
                                        <option {{$id ? ($data->position == 'LEFT') ? 'selected' :'' :''}} value="LEFT">LEFT</option>
                                        <option {{$id ? ($data->position == 'RIGHT') ? 'selected' :'' :''}} value="RIGHT">RIGHT</option>
                                    </select>
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>DIST HUM *</label>
                                    <input type="text"  value="{{$id ? $data->dist_hum :''}}" class="form-control" name="dist_hum" placeholder="Enter DIST HUM">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>TOOL *</label>
                                    <input type="file"  value="" class="form-control" name="tool" placeholder="Enter TOOL">
                                   
                                @if($id)
                                   <a href="{{ url('img/profile/'.$data->tool) }}" target="_blank" style="
                                    float: right;
                                    font-size: 10px;
                                ">View image</a>
                                @endif


                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Barcode 1 *</label>
                                    <input type="file"  value="" class="form-control" name="barcode1" placeholder="Enter Barcode 1">
                                    @if($id)
                                   <a href="{{ url('img/profile/'.$data->barcode1) }}" target="_blank" style="
                                    float: right;
                                    font-size: 10px;
                                ">View image</a>
                                 @endif
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Barcode 2 *</label>
                                    <input type="file"  value="" class="form-control" name="barcode2" placeholder="Enter Barcode 2">
                                    @if($id)
                                   <a href="{{ url('img/profile/'.$data->barcode2) }}"  target="_blank" style="
                                    float: right;
                                    font-size: 10px;
                                ">View image</a>
                                     @endif
                                </div><!-- form-group -->
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>MFG*</label>
                                    <input type="text" class="form-control" value="{{$id ? $data->mfg :''}}" name="mfg" placeholder="Enter MFG">
                                </div><!-- form-group -->



                            </div> 

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><i
                                            class="fas fa-save"></i> {{$id ? ' Update' : ' Submit' }}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>


            </div><!-- az-content-body -->
        </div>
    </div><!-- az-content -->
  
    <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('') ?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/lib/spectrum-colorpicker/spectrum.js"></script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
    <script src="<?= url('') ?>/lib/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
    <script src="<?= url('') ?>/lib/pickerjs/picker.min.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/js/moment.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>
    <script>
    var start = new Date();

    $('.datepicker').datepicker({
                         setDate : start,
                        format: "dd-mm-yyyy",
                        autoclose:true
                    });


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
            careof: {
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
            house_name:{
                required: true,
                minlength: 1,
                maxlength: 50
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
            bill_address:{
                required: true, 
            }
          
        },
       
    });
    </script>

@stop