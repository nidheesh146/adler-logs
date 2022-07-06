@extends('layouts.default')
@section('content')
    <style>
        #ui-datepicker-div {
            width: 239px !important;
        }

    </style>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('magazine/edition') }}" >Edition</a></span>
                    <span>{{ $id ? 'Edit' : 'Add' }} Edition</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">{{ $id ? 'Edit' : 'Add' }} Edition
                    @if ($id)
                        <button style="float: right;font-size: 14px;" onclick="document.location.href='{{ url('magazine/add-edition') }}'"
                        class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Add Edition   </button>
                    @endif
                </h4>


                <div class="row">
                    <div class="col-md-4 col-lg-3">
                        <div class="pd-20 bg-gray-200"
                            style="border: 1px solid rgba(28, 39, 60, 0.12);background-color: #ffffff;">
                            <nav class="nav az-nav-column">
                               
                                @if (in_array('edition.add',config('permission')) || in_array('edition.edit',config('permission'))) 
                                <a class="nav-link active"  style="{{ !$id ? 'cursor: no-drop;' : ''}}" href="{{ $id ?  url('magazine/add-edition/'.$id) :'#' }}">
                                     <i class="fas fa-book" style="font-size: 13px;margin: 0;"></i>Edition
                                </a>
                                @endif

                                @if (in_array('edition.article',config('permission'))) 
                                 <a class="nav-link"  style="{{ !$id ? 'cursor: no-drop;' : ''}}" href="{{ $id ? url('magazine/article/'.$id) : '#' }}">
                                    <i class="fas fa-book-reader"  style="font-size: 13px;margin: 0;"></i>
                                    Articles</a>
                                @endif



                                <a class="nav-link" data-toggle="tab" href="#"></a>
                            </nav>
                        </div><!-- pd-10 -->
                    </div>

                    <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9"
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
                        <form method="POST" id="commentForm" >
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i
                                            class="fas fa-address-card"></i> Basic details </label>
                                    <div class="form-devider"></div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label for="exampleInputEmail1">Magazine *</label>
                                    <select class="form-control attr_magazine" style="{{ ($id && $data['sptl']['approve']) ? 'pointer-events:none' : ''}}" {{ ($id && $data['sptl']['approve']) ? 'readonly' : ''}} name="magazine" >
                                        <option value="">Choose one</option>
                                        @foreach ($data['magazine'] as $magazine)
                                            <option value="{{ $magazine['id'] }}"
                                                {{ $id ? ($data['sptl']['book_id'] == $magazine['id'] ? 'selected' : '') : '' }}>
                                                {{ $magazine['name'] . '-' . $magazine['magazine_id'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label for="exampleInputEmail1">Edition name / Version *</label>
                                    <input type="text" class="form-control" name="edition_name" value="{{ $id ? $data['sptl']['edition_name'] : '' }}"
                                        placeholder="Enter first name">
                                </div>

                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label for="exampleInputPassword1">Month and Year *</label>
                                    <input type="text"
                                        value="{{ $id ? date('m-Y', strtotime($data['sptl']['date'])) : '' }}"
                                        readonly 
                                        class="form-control 
                                        @if($id) 
                                        @if(!$data['sptl']['approve'])
                                          datepicker 
                                        @endif
                                        @else
                                           datepicker 
                                        @endif" 
                                        @if($id) 
                                        @if(!$data['sptl']['approve'])
                                           style="background: white;"
                                        @endif
                                        @else
                                           style="background: white;"
                                        @endif
                                      
                                        @if($id && $data['sptl']['approve'])
                                        data-toggle="tooltip" data-html="true" data-placement="top"
                                        title="The edition is Approved so you can't edit the price"
                                        @endif

                                        name="month_year" placeholder="MM / YYYY">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label for="exampleInputPassword1">Number of pages *</label>
                                    <input type="number" value="{{ $id ? $data['sptl']['pages'] : '' }}" class="form-control" name="pages" placeholder="Enter number of pages">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label for="exampleInputPassword1">Price *</label>
                                    <input type="text" 
                                       {{ ($id && $data['sptl']['approve']) ? 'readonly' : ''}}
                                        value="{{ $id ? $data['sptl']['price'] : ''}}" 
                                        @if($id && $data['sptl']['approve'])
                                        data-toggle="tooltip" data-html="true" data-placement="top"
                                        title="The edition is Approved so you can't edit the price"
                                        @endif
                                        class="form-control"
                                        name="price" placeholder="Price">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label for="exampleInputPassword1">Work order issue date *</label>
                                    <input type="text"  readonly  value="{{ $id ? date('d-m-Y', strtotime($data['sptl']['work_order_date'])) : '' }}" 
                                    
                                    class="form-control 
                                        @if($id) 
                                        @if(!$data['sptl']['approve'])
                                        datepickers 
                                        @endif
                                        @else
                                        datepickers 
                                        @endif" 
                                        @if($id) 
                                        @if(!$data['sptl']['approve'])
                                           style="background: white;"
                                        @endif
                                        @else
                                           style="background: white;"
                                        @endif
                                      
                                        @if($id && $data['sptl']['approve'])
                                        data-toggle="tooltip" data-html="true" data-placement="top"
                                        title="The edition is Approved so you can't edit the price"
                                        @endif
                                        
                                        name="work_order_date" placeholder="Work order date">
                                </div><!-- form-group -->
                                

                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;">
                                        <span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span>
                                        <i class="fas fa-save"></i> {{ $id ?  'Update' : 'Save & Next' }} </button>
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
                        $('[data-toggle="tooltip"]').tooltip();
                      </script>
              <script>
                $(function () {
                  'use strict'
                    var date = new Date();
                    date.setDate(date.getDate());
                    $(".datepicker").datepicker({
                    format: " mm-yyyy",
                    viewMode: "months",
                    minViewMode: "months",
                    startDate: date,
                    autoclose:true
                    }).on('changeDate', function(e){
                       $('.datepickers').val('');
                        if(moment(e.date).isSame(date, 'month')){
                               e.date =new Date();
                        }
                       $(".datepickers").datepicker('setStartDate',e.date).datepicker('update');
                       $(".datepickers").datepicker('setEndDate',moment(e.date).endOf('month').toDate()).datepicker('update');
                    });
                    $(".datepickers").datepicker({
                    format: "dd-mm-yyyy",
                    startDate: date,
                    autoclose:true
                    });
                });
              </script>
    @if ($id)
    <script>
        $(function () {
          'use strict'
          $("#commentForm").validate({
                rules: {
                    magazine:{
                      required: true,
                   },
                   edition_name:{
                      required: true,
                   },
                   pages:{
                      required: true,
                      number:true
                   },
                   month_year:{
                      required: true,
                      remote:{
                        url: "<?= url('') ?>/check-email-profile",
                        type: "get",
                        data: {
                            magazine: function() {
                               return $(".attr_magazine").val();
                            },
                            module: function() {
                               return 'add-special-price';
                            },
                            id: function() {
                               return '<?= $id ?>';
                            }
                          }
                       }
                   },
                   price:{
                    required: true,
                    number:true
                   },
                   work_order_date:{
                    required: true,
                   },
                },
                messages: {
                    month_year: {
                        remote:"Date and month already exists",
                    },
                   },
                   submitHandler: function(form) {
                        $('.spinner-button').show();
                        form.submit();
                    }
              });
        });

      </script>
<script>
  let monthandyear =  moment("<?= date('Y-m-d', strtotime($data['sptl']['date']));?> 00:00:00");
  $(".datepickers").datepicker({format: "dd-mm-yyyy",autoclose:true});
  $(".datepickers").datepicker('setStartDate',monthandyear.toDate()).datepicker('update');
  $(".datepickers").datepicker('setEndDate',monthandyear.endOf('month').toDate()).datepicker('update');

  $(".datepickers").datepicker(  ).datepicker('update');
  

</script>
@else
    <script>
        $(function () {
          'use strict'
          $("#commentForm").validate({
                rules: {
                    magazine:{
                      required: true,
                   },
                   edition_name:{
                      required: true,
                   },
                   pages:{
                      required: true,
                      number:true
                   },
                   month_year:{
                      required: true,
                      remote:{
                        url: "<?= url('') ?>/check-email-profile",
                        type: "get",
                        data: {
                            magazine: function() {
                               return $(".attr_magazine").val();
                            },
                            module: function() {
                               return 'add-special-price';
                            }
                          }
                       }
                   },
                   price:{
                    required: true,
                    number:true
                   },
                   work_order_date:{
                    required: true,
                   },
                },
                messages: {
                    month_year: {
                        remote:"Date and month already exists",
                    },
                   },
                   submitHandler: function(form) {
                        $('.spinner-button').show();
                        form.submit();
                    }
              });
        });
      </script>

    @endif
  
@stop
