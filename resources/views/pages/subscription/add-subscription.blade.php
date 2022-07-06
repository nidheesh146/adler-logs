@extends('layouts.default')
@section('content')
@inject('SubscriptionController', 'App\Http\Controllers\Web\SubscriptionController')
@inject('Controller', 'App\Http\Controllers\Controller')
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('subscribers/' . $org) }}" >SUBSCRIBERS</a></span>
                    <span><a href="{{ url('subscribers/' . $org) }}">ALL SUBSCRIBERS</a></span>
                    <span>  @if($subs_id) Update @else Create @endif  a subscription</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">@if($subs_id) Update @else Create @endif a subscription for '{{$datas['suscriber']['subr_id']}} - {{$datas['suscriber']['f_name'].' '.$datas['suscriber']['l_name']}} '
                
                    @if($subs_id)    <button style="float: right;font-size: 14px;" onclick="document.location.href='{{ url('subscription/create/' . $org . '/' . $id)}}'" class="badge badge-pill badge-dark "><i
                        class="fas fa-plus"></i> subscription  </button> @endif
                
                
                </h4>


                <div class="row">
                    <div class="col-md-4 col-lg-3">
                        <div class="pd-20 bg-gray-200"
                            style="border: 1px solid rgba(28, 39, 60, 0.12);background-color: #ffffff;">
                            <nav class="nav az-nav-column">
                                @if (in_array('subscribers.edit', config('permission')))  
                                    <a class="nav-link " style="{{ !$id ? 'cursor: no-drop;' : '' }}"
                                        href="{{ $id ? url('subscribers/create/' . $org . '/' . $id) : '#' }}"><i
                                            class="fas fa-user-plus" style="font-size: 13px;margin: 0;"></i> Subscriber
                                    </a>
                                @endif
                                @if (in_array('subscribers.subscription.list', config('permission')))  
                                    <a class="nav-link active" style="{{ !$id ? 'cursor: no-drop;' : '' }}"
                                        href="{{ $id ? url('subscription/create/' . $org . '/' . $id) : '#' }}"><i
                                            class="fas fa-money-check-alt" style="font-size: 13px;margin: 0;"></i>
                                        Subscription</a>
                                @endif
                                <a class="nav-link" data-toggle="tab" href="#"></a>
                            </nav>
                        </div><!-- pd-10 -->
                    </div>



                    <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">


@if (in_array('subscribers.subscription.add', config('permission')) || (in_array('subscribers.subscription.edit', config('permission')) && $subs_id) )             
<div style="border: 1px solid rgba(28, 39, 60, 0.12);
padding: 12px 16px 1px 19px;
margin-bottom: 19px;">

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                @if($subs_id) <i class="fas fa-edit"></i> Edit @else  <i class="fas fa-folder-plus"></i> Add @endif  Subscription @if($subs_id) ( Subscription ID : {{$datas['single_susn']['subscription_id'] }}  ) @endif</label>
                                <div class="form-devider"></div>
                            </div>
                        </div>
                        <form  method="post"  id="commentForm" autocomplete="off">
                        <div class="row ">
                            {{ csrf_field() }}  
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
                           @endif
                       
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>Subscription Date * </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                        </div>
                                    <input type="text" name="start_date" style="width: 80%;" value="{{ $subs_id ? date('m-Y',strtotime($datas['single_susn']['subscription_from'])) : date('m-Y') }}" class="form-control datepicker-start" placeholder="MM-YYYY">
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>Expiry Date *   <span class="diff_date"></span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="end_date" style="width: 80%;"  value="{{ $subs_id ? date('m-Y',strtotime($datas['single_susn']['subscription_to'])) : '' }}" class="form-control datepicker-end" placeholder="MM-YYYY">
                                    </div>
                                </div>
                             
                                @if(!$subs_id)
                                @php 
                                $work_order_date = "";
                                 foreach($datas['magazine'] as $magazine){
                                  if(!empty($magazine['work_order_date'])){
                                      if(date('Y-m-d') >= $magazine['work_order_date']  && $magazine['approve']){
                                      $work_order_date =  $work_order_date ? ','. $magazine['magazine_id'].'-'.$magazine['name']: $magazine['magazine_id'].'-'.$magazine['name'];
                                   }
                                  }
                                 } 
                                  @endphp 
                                  @if( $work_order_date )
                                  <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="
                                  margin: 0 0 0 0;
                              ">
                                     <span style="font-size: 11px;color: #edb408;margin-bottom: 9px;border-radius: 34px;"> <i class="fas fa-exclamation-triangle"></i> {{$work_order_date}} - The work order issue is already done so in this month ({{date('M-Y')}}) you cannot order this magazine please try next month. </span><br>
                                  </div>
                                     @endif
                          
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12 magazine_body">
                               
                                  <?=$SubscriptionController->add_subscription($id,date('Y-m-d'),$subs_id);?>

                                </div>
                                <div style="margin-left: 36%;display:none;" class="magazine_body_spinner">
                                    <div class="spinner-grow text-primary" role="status">
                                      <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-secondary" role="status">
                                      <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-success" role="status">
                                      <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-danger" role="status">
                                      <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-warning" role="status">
                                      <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-info" role="status">
                                      <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-light" role="status">
                                      <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-dark" role="status">
                                      <span class="sr-only">Loading...</span>
                                    </div>
                                  </div>

                                @else


                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    @php 
                          
                                $work_order_date = "";
                                 foreach($datas['magazine'] as $magazine){
                                  if(!empty($magazine['work_order_date'])){
                                      if(date('Y-m-d') >= $magazine['work_order_date']  && $magazine['approve']){
                                      $work_order_date =  $work_order_date ? ','. $magazine['magazine_id'].'-'.$magazine['name']: $magazine['magazine_id'].'-'.$magazine['name'];
                                   }
                                  }
                                 } 
                                  @endphp 
                                  @if( $work_order_date )
                                  <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="
                                     margin: 0px 0px 0px -14px;
                              ">
                                     <span style="font-size: 11px;color: #edb408;margin-bottom: 9px;border-radius: 34px;"> <i class="fas fa-exclamation-triangle"></i> {{$work_order_date}} - The work order issue is already done so in this month ({{date('M-Y')}}) you cannot order this magazine please try next month. </span><br>
                                  </div>
                                     @endif
                          
                                    <table class="table table-bordered mg-b-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Magazine</th>
                                                <th style="text-align: right;">Price * Month</th>
                                                <th >Quantity</th>
                                                @if(config('organization')['type']  == 1)
                                                <th style="width: 105px;">Paid/Free <i class="fas fa-info-circle"
                                                        data-toggle="tooltip" data-html="true" data-placement="top"
                                                        title="Paid or Free Subscription.<br> Paid = checked <br>  Free = Unchecked"></i>
                                                </th>
                                                @endif
                                                <th style="text-align: right;">Total price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($datas['magazine'] as $magazine)
                                                @php
                                                    $price_month = sprintf("%.3f",$magazine['price']);
                                                    $quantity = 1;
                                                    $datas['suscriber']['type']  = (config('organization')['type'] != 1) ? 'paid' : $datas['suscriber']['type'];
                                                    $paid_free = ($datas['suscriber']['type'] == 'paid') ? 'checked' : '' ;
                                                    $book_total_price = '0.000';
                                                    $select_magazine = "";
                                                    if($subs_id){
                                                       $SubsControl=$SubscriptionController->showbookprice($subs_id,$magazine['id']);
                                                       if($SubsControl){
                                                           $price_month = sprintf("%.3f",$SubsControl['price_month']);
                                                           $quantity = $SubsControl['quantity'];
                                                           $SubsControl['type']  = (config('organization')['type'] != 1) ? 'paid' : $SubsControl['type'];
                                                           $paid_free = ($SubsControl['type'] == 'paid' ) ? 'checked' : '' ;
                                                           $book_total_price =  sprintf("%.3f",$SubsControl['total_price']);
                                                           $select_magazine = $SubsControl['book_status'] ? "checked" : '';
                                                       }
                                                    }
                                                @endphp
                                                <tr>
                                                    <td><input class="select-magazine" {{$select_magazine }} name="magazine[{{$magazine['id']}}]" type="checkbox" value="{{ $magazine['id'] }}"></td>
                                 
                                                    <td>{{ $magazine['magazine_id'].'-'.$magazine['name'] }}</td>
                                                    <td style="text-align: right;"><span class="total_{{ $magazine['id'] }}" >{{$price_month}}</span> * <span class="total_mag_months">{{$subs_id ? $datas['single_susn']['total_month']:'0';}}</span></td>

                                                    <td><input type="number" 
                                                            name="quantity[{{$magazine['id']}}]"
                                                    class="count_{{ $magazine['id'] }} count_magazine"  value="{{$quantity}}"
                                                            style="width: 50px;" min="1" ></td>
                                                   
                                                   
                                                      <td style="padding-left: 40px; 
                                                      {{(config('organization')['type'] != 1) ? 'display:none;' : ''}}
                                                      ">
                                                        <input type="checkbox"
                                                            name="paid_free[{{$magazine['id']}}]"
                                                            class="paid_free_{{ $magazine['id'] }} free_magazine" 
                                                            {{$paid_free}} 
                                                            value="{{ $magazine['id'] }}" >
                                                        </td>



                                                    <td style="text-align: right;"><span
                                                            class="item_total_{{ $magazine['id'] }}">{{$book_total_price}}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td></td>
                                                @if(config('organization')['type']  == 1)
                                                <td></td>
                                                @endif
                                                <td></td>
                                                <td></td>
                                                <td>Total:</td>
                                                <td class="total_magazine" style="text-align: right;">@if($subs_id) {{ sprintf("%.3f",$datas['single_susn']['total_amount']) }} @else {{ '0.000' }} @endif</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                @endif














                            </div>
               
              




                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;">
                                    <span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span> <i
                                        class="fas fa-save"></i>   @if($subs_id) Update  @else Save @endif  </button>
                            </div>
                        </div>



                    </form>


                    </div>
                    @endif






                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">{{$datas['suscriber']['subr_id']}} - {{$datas['suscriber']['f_name'].' '.$datas['suscriber']['l_name']}}'s
                                    subscription history</label>
                                <div class="form-devider"></div>
                            </div>
                        </div>


                        <div class="">
                            <table class="table table-bordered mg-b-0">
                                <thead>
                                    <tr>
                                        <th>Subscription ID</th>
                                        <th>Book ID</th>
                                        <th>Subscription </th>
                                        <th>Expiry </th>
                                        <th>Created </th>
                                        <th>Total </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas['subscription'] as $subscription)
                                <tr  style="{{ ( strtotime(date('M-Y',strtotime($subscription['subscription_to']))) <   strtotime(date('M-Y'))) ? 'background: #ff572240;' :'' }}">
                                    <td> <?=($subscription['org_id'] == config('organization')['org_id']) ? '<i class="far fa-hand-point-right"></i> ' : '';?>{{$subscription['subscription_id']}}</td>
                                    <th ><?=$SubscriptionController->book_id($subscription['subn_id']);?></th>
                                    <td>{{date('M-Y',strtotime($subscription['subscription_from']))}}</td>
                                        <td>{{date('M-Y',strtotime($subscription['subscription_to']))}}</td>
                                        <td>{{date('d-m-Y',strtotime($subscription['created_at']))}}</td>
                                        <td style="text-align: right;">{{sprintf("%.3f",$subscription['total_amount'])}}</td>
                                        <td>
                                            <button data-toggle="dropdown" class="badge  badge-primary">Active <i
                                                    class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                            <div class="dropdown-menu">
                                            <a href="#" id="{{$Controller->hashEncode($subscription['subn_id'])}}" class="dropdown-item subscription-view"><i class="fas fa-eye"></i> View</a>


                                            @if($subscription['org_id'] == config('organization')['org_id'] || config('organization')['type'] == 1)
                                            @if(in_array('subscribers.subscription.edit', config('permission')))             
                                              <a href="{{url('subscription/create/'.$org.'/'.$id.'/'.$Controller->hashEncode($subscription['subn_id']))}}" class="dropdown-item "><i class="fas fa-edit"></i> Edit</a>
                                            @endif
                                            @if(in_array('subscribers.subscription.delete', config('permission'))) 
                                                <a  onclick="return confirm('Are you sure you want to delete this ?');"  href="{{url('subscription-delete/'.$org.'/'.$id.'/'.$Controller->hashEncode($subscription['subn_id']))}}" class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>
                                            @endif
                                            @endif


                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="box-footer clearfix">
                                {{ $datas['subscription']->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- az-content-body -->
        </div>



        <div id="modaldemo2" class="modal">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>


                        <div class="row  timelines">

                        
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- modal-dialog -->


        
      </div><!-- modal -->
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
            $('.subscription-view').click(function(){
                $.get("<?= url('') ?>/subscription-view/"+$(this).attr('id'), function( data ) {
                  $('.timelines').html(data);
                  $('#modaldemo2').modal('show');
                });
                });

            </script>

             <script>
               $(function () {
                      'use strict'
                      $('[data-toggle="tooltip"]').tooltip();
                      $('.datepicker-start').mask('99-9999');
                      $('.datepicker-end').mask('99-9999');

            function amountcalc(){
                let total_amount = 0;
               let month_diff = 0;

                if($('.datepicker-start').val() && $('.datepicker-end').val()){
                    let startDate = moment($('.datepicker-start').val(), "MM-YYYY");
                    let endDate = moment($('.datepicker-end').val(), "MM-YYYY");
                    let month_diff  =  ( endDate.diff(startDate, 'months') < 0 ) ? 0 : endDate.diff(startDate, 'months')+1;
                    $('.total_mag_months').text(month_diff);
                    $('.select-magazine').each(function( i ) {
                    let bookid = $(this).val();
                    let item_price = $('.total_'+bookid).text();
                    let QUANTITY =  $('.count_'+bookid).val() ? $('.count_'+bookid).val() : 0;
                    if(QUANTITY < 0) { QUANTITY = 0; }
                    let total_item_price = ( (item_price * QUANTITY * month_diff) ).toFixed(3);

                    if(!$(this).is(':checked') || !$('.paid_free_'+bookid).is(':checked')){
                        total_item_price=(0).toFixed(3);
                    }
                   
                    $('.item_total_'+bookid).text(total_item_price);
                    total_amount = ((+total_amount)+(+total_item_price));
                    $('.total_magazine').text(total_amount.toFixed(3));

                });

                }else{
                    $('.select-magazine').each(function( i ) {
                    let bookid = $(this).val();
                    $('.item_total_'+bookid).text('0.000');
                    $('.total_magazine').text('0.000');
                });
                }
                }

                $('.select-magazine').click(function(){
                    amountcalc();
                });
                $( ".count_magazine" ).on('input', function() {
                    amountcalc();
                });
                $('.free_magazine').click(function(){
                    amountcalc();
                });


                    let susid ='{{$subs_id}}';
                    var start = new Date();
                    var end = new Date(new Date().setYear(start.getFullYear()+2));

                    $('.datepicker-start').datepicker({
                        startDate : start,
                        endDate   : end,
                        format: "mm-yyyy",
                                    viewMode: "months",
                                    minViewMode: "months",
                                    autoclose:true
                    // update "toDate" defaults whenever "fromDate" changes
                    }).on('changeDate', function(e){
                    var endd = new Date(new Date().setYear(e.date.getFullYear()+2));
                    $('.datepicker-end').val('');
                    $(".datepicker-end").datepicker('setStartDate',e.date).datepicker('update');
                    $(".datepicker-end").datepicker('setEndDate',endd).datepicker('update');
                    $(".datepicker-end").datepicker('show');
                    if(!susid){
                    $('.magazine_body').html('');
                    $('.magazine_body_spinner').show();
                    $.get("<?= url('') ?>/add_subscription/{{$id}}/"+moment(e.date).format('YYYY-MM-DD'), function( data ) {
                        $('.magazine_body').html(data);
                        $('[data-toggle="tooltip"]').tooltip();
                        $('.magazine_body_spinner').hide();
                        $('.select-magazine').click(function(){
                            amountcalc();
                        });
                        $( ".count_magazine" ).on('input', function() {
                            amountcalc();
                        });
                        $('.free_magazine').click(function(){
                            amountcalc();
                        });
                    });
                    }
                    amountcalc();
                    }); 

                    $('.datepicker-end').datepicker({
                        startDate : start,
                        endDate   : end,
                        format: "mm-yyyy",
                        viewMode: "months",
                        minViewMode: "months",
                        autoclose:true
                    }).on('changeDate', function(e){
                        if(!$('.datepicker-start').val()){
                            $('.datepicker-end').val('');
                        }
                        amountcalc();
                    });
  
                        $("#commentForm").validate({
                            rules: {
                                start_date: {
                                    required: true,
                                },
                                end_date: {
                                    required: true,
                                },
                            },
                            submitHandler: function(form) {
                                $('.spinner-button').show();


                                form.submit();
                            }
                        });
                    });
            
            
            
          

              




              </script>

@stop
