@extends('layouts.default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
@inject('MagazineController', 'App\Http\Controllers\Web\MagazineController')

<div class="az-content az-content-dashboard">
    <div class="container">
      <div class="az-content-body">
      <div class="az-content-breadcrumb">
      <span>Magazine</span>
            <span>Edition</span>
          </div>
          <h4 class="az-content-title" style="font-size: 20px;">Edition
          
            @if (in_array('edition.add',config('permission'))) 
          <a href="{{url('magazine/add-edition')}}" style="float: right;font-size: 14px;" class="badge badge-pill badge-dark ">
            <i class="fas fa-plus"></i> Add edition </a>
              @endif
          
          </h4>

          @include('includes.magazine-nav')
          <div class="row row-sm mg-b-20 mg-lg-b-0">
            <div class="table-responsive" style="margin-bottom: 13px;">
               
                @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                    </div>
                @endif

                <table class="table table-bordered mg-b-0">
                    <tbody>
                        <tr>
                            <th scope="row">
                              <form >
                                <div class="row filter_search">
                              
                                    <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
                                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                          <label for="exampleInputEmail1">Magazine</label>
                                          <select class="form-control attr_magazine" name="magazine">
                                              <option value="">Choose one</option>
                                              @foreach ($data['magazine'] as $magazine)
                                                  <option value="{{ $magazine['id'] }}" {{(request()->get('magazine') == $magazine['id']) ? 'selected' : ''}}>
                                                      {{ $magazine['name'] . '-' . $magazine['magazine_id'] }}</option>
                                              @endforeach
                                          </select>
                                        </div>

                                      <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <label> Edition</label>
                                      <input type="text" class="form-control" name="edition" value="{{request()->get('edition')}}" placeholder="Enter name">
                                    </div>
                                        <!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                            <label>Month & year</label>
                                            <input type="text"
                                            value="{{request()->get('month_year')}}" 
                                            class="form-control datepicker"  autocomplete="off" name="month_year" placeholder="MM / YYYY">
                                        </div><!-- form-group -->

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

                                      
                                            {{-- <a href="#" class="show_password" style="
                                            float: right;
                                            font-size: 10px;
                                        ">Change Password</a> --}}
                                    </div>
                                    
                                </div>
                              </form>





                            </th>
                    </tbody>
                </table>
            </div>
        </div>

         
<div class="row row-sm mg-b-20 mg-lg-b-0">
      <div class="table-responsive">
            <table class="table table-bordered mg-b-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Magazine</th>
                  <th>Edition </th>
                  <th>Month & year</th>
                  <th>NO: OF Article</th>
                  <th>Pages</th>
                  <th>Price</th>
                  <th></th>
            
                </tr>
              </thead>
           

              <tbody>
                @foreach ($data['edition']  as $edition)
                <tr>  
                  <td>   
                    @if($edition['approve'])  
                    <i class="fas fa-lock" data-toggle="tooltip" data-html="true" data-placement="top"
                    title="Issued Work order"></i>
                    @endif
                    @if(!$edition['approve'])  
                    {{-- <i class="fas fa-unlock-alt" data-toggle="tooltip" data-html="true" data-placement="top"
                    title="Waiting for Approved"></i> --}}
                    -
                    @endif
                  </td>
                <td>{{$edition['magazine_id'].'-'.$edition['name']}}</td>
                  <th scope="row">
                   @if (in_array('edition.edit',config('permission'))) 
                    <a href="{{url('magazine/add-edition/'.$Controller->hashEncode($edition['special_id']))}}" >{{$edition['edition_name']}}</a>
                  @else
                     {{$edition['edition_name']}}
                  @endif
                  </th>
                <td style="{{(date('M-Y',strtotime($edition['date'])) == date('M-Y')) ? 'color:green;font-weight: bold;
              ': ''}}">{{date('M-Y',strtotime($edition['date']))}}</td>
                 <td><a href="{{url('magazine/article/'.$Controller->hashEncode($edition['special_id']))}}"  >{{ $MagazineController->no_of_article($edition['special_id']) }}</a></td>
                <td>{{$edition['pages']}}</td>
             
                  <td style="text-align: right;">{{sprintf("%.3f",$edition['price'])}}</td>
                
                
                  @if (in_array('edition.article',config('permission')) || in_array('edition.edit',config('permission')) || in_array('edition.delete',config('permission'))) 
              <td>
                <button data-toggle="dropdown" style="width: 64px;" class="badge badge-success">Action<i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
               <div class="dropdown-menu">
                @if (in_array('edition.article',config('permission'))) 
                    <a href="{{url('magazine/article/'.$Controller->hashEncode($edition['special_id']))}}"  class="dropdown-item "><i class="fas fa-book-reader" style="font-size: 13px;margin: 0;"></i> Article </a>
                @endif
                @if (in_array('edition.edit',config('permission'))) 
                   <a href="{{url('magazine/add-edition/'.$Controller->hashEncode($edition['special_id']))}}"  class="dropdown-item "><i class="fas fa-edit"></i> Edit</a>
                @endif
                @if (in_array('edition.delete',config('permission'))) 
                  @if(!$edition['approve'])
                    <a href="{{url('magazine/edition-delete/'.$Controller->hashEncode($edition['special_id']))}}"  onclick="return confirm('Are you sure you want to delete this ?');"  class="dropdown-item "><i class="fas fa-trash-alt"></i> Delete</a>
                  @endif
               @endif
              </div>
              </td>
              @endif






                </tr>
                @endforeach
              </tbody>

            

    

            </table>
            <div class="box-footer clearfix">
              {{ $data['edition']->links() }}
              </div>
          </div>
</div>





      </div><!-- az-content-body -->
    </div>
  </div><!-- az-content -->
  <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?=url('');?>/js/azia.js"></script>
  <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>

              <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
              <script src="<?= url('') ?>/lib/spectrum-colorpicker/spectrum.js"></script>
              <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
              <script src="<?= url('') ?>/lib/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
              <script src="<?= url('') ?>/lib/pickerjs/picker.min.js"></script>
              <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>

              <script src="<?= url('') ?>/js/jquery.validate.js"></script>
              <script src="<?= url('') ?>/js/additional-methods.js"></script> 

              <script>
                $(function () {
                  'use strict'
                    var date = new Date();
                    date.setDate(date.getDate());
                    $(".datepicker").datepicker({
                    format: " mm-yyyy",
                    viewMode: "months",
                    minViewMode: "months",
                   // startDate: date,
                    autoclose:true
                    });

                    $('.datepicker').mask('99-9999');
                });
              </script>
                <script>
                  $('[data-toggle="tooltip"]').tooltip();
                </script>
@stop