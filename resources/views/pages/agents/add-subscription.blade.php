@extends('layouts.default')
@section('content')
@inject('AgentController', 'App\Http\Controllers\Web\AgentController')
@inject('Controller', 'App\Http\Controllers\Controller')
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('agent/agents') }}" >Agents</a></span>
                    {{-- <span><a href="{{ url('agent/create/agent/' . $org) }}"
                            >ALL Agent</a></span> --}}
                    <span>  @if($subs_id) Update @else Create @endif an order request</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">@if($subs_id) Update @else Create @endif an order request for '{{$datas['suscriber']['subr_id']}} - {{$datas['suscriber']['f_name'].' '.$datas['suscriber']['l_name']}} '
                
                    @if (in_array('agent.orderrequest.add', config('permission')) && config('organization')['type'] == 5)  
                    @if($subs_id) <button style="float: right;font-size: 14px;" onclick="document.location.href='{{ url('agent-subscription/create/' . $org . '/' . $id)}}'" class="badge badge-pill badge-dark "><i
                        class="fas fa-plus"></i> Order request  </button> @endif
                    @endif
                    </h4>

                <div class="row">
                  
                    <div class="col-md-4 col-lg-3">
                        <div class="pd-20 bg-gray-200"
                            style="border: 1px solid rgba(28, 39, 60, 0.12);background-color: #ffffff;">
                               @include('includes.agent-nav')
                        </div><!-- pd-10 -->
                    </div>

                    <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">
                        @if ((in_array('agent.orderrequest.add', config('permission')) && config('organization')['type'] == 5) || (in_array('agent.orderrequest.edit', config('permission')) && $subs_id))  
                    <div style="border: 1px solid rgba(28, 39, 60, 0.12);padding: 13px 21px 0px 18px;margin-bottom:12px;">
                        <div class="row" >
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                @if($subs_id) <i class="fas fa-edit"></i> Edit @else  <i class="fas fa-paper-plane"></i>  Add @endif  order request @if($subs_id) ( Subscription ID : {{$datas['single_susn']['subscription_id'] }}  ) @endif</label>
                                <div class="form-devider"></div>
                            </div>
                        </div>
                        <form  method="post"  id="commentForm"  autocomplete="off">
                        <div class="row " >
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
                               <span style="font-size: 11px;color: #edb408;margin-bottom: 9px;border-radius: 34px;"> <i class="fas fa-exclamation-triangle"></i> {{$work_order_date}} - The work order issue is already done so in this month ({{date('M-Y')}}) you cannot order this magazine please try next month. </span><br>
                             @endif
                         </div>

                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>From month & year * </label>
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
                                    <label>To month & year *   <span class="diff_date"></span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="end_date" style="width: 80%;"  value="{{ $subs_id ? date('m-Y',strtotime($datas['single_susn']['subscription_to'])) : '' }}" class="form-control datepicker-end" placeholder="MM-YYYY">
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>Magazine *<span class="diff_date"></span></label>
                                 
                                        <select class="form-control" name="magazine">
                                            <option value="">Choose one</option>
                                            @foreach ($datas['magazine'] as $magazine)
                                                <option value="{{ $magazine['id'] }}" {{$subs_id ? ($magazine['id'] == $datas['single_susn']['book_id']) ? 'selected':'' :''}}>
                                                    {{ $magazine['name'] . '-' . $magazine['magazine_id'] }}</option>
                                            @endforeach
                                        </select>
                                  
                                </div>
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>	Quantity *</label>
                                <input type="text" name="quantity" min="1"  value="{{$subs_id ? $datas['single_susn']['quantity'] : ''}}" class="form-control" placeholder="how many magazines do you want ? eg : 10">
                                </div>
                            </div>
               


                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;">
                                    <span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span>&nbsp;<i
                                        class="fas fa-save"></i>   @if($subs_id) Update  @else Submit @endif  </button>
                            </div>
                        </div>
                    </form>
                    </div>
                     @endif

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">{{$datas['suscriber']['subr_id']}} - {{$datas['suscriber']['f_name'].' '.$datas['suscriber']['l_name']}}'s
                                    order request history</label>
                                <div class="form-devider"></div>
                            </div>
                        </div>


                        <div class="">
                            <table class="table table-bordered mg-b-0">
                                <thead>
                                    <tr>
                                        <th>request ID</th>
                                        <th>Magazine <br> & Quantity</th>
                                        <th>from </th>
                                        <th>to </th>
                                        <th>Created </th>
                                        {{-- <th>Total </th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas['subscription'] as $subscription)
                                       <tr  style="{{ ( strtotime(date('M-Y',strtotime($subscription['subscription_to']))) <   strtotime(date('M-Y'))) ? 'background: #ff572240;' :'' }}">
                                        <td>{{$subscription['subscription_id']}}</td>
                                        <th ><?=$AgentController->book_id($subscription['subn_id']);?></th>
                                        <td>{{date('M-Y',strtotime($subscription['subscription_from']))}}</td>
                                        <td>{{date('M-Y',strtotime($subscription['subscription_to']))}}</td>
                                        <td>{{date('d-m-Y',strtotime($subscription['created_at']))}}</td>
                                        {{-- <td style="text-align: right;">{{sprintf("%.3f",$subscription['total_amount'])}}</td> --}}
                                        <td>
                                            <button data-toggle="dropdown" class="badge  badge-primary">Active <i
                                                    class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                            <div class="dropdown-menu">
                                          
                                            <a href="#" id="{{$Controller->hashEncode($subscription['subn_id'])}}" class="dropdown-item subscription-view"><i class="fas fa-eye"></i> View</a>
                                          
                                            @if (in_array('agent.orderrequest.edit', config('permission')))  
                                                <a href="{{url('agent-subscription/create/'.$org.'/'.$id.'/'.$Controller->hashEncode($subscription['subn_id']))}}" class="dropdown-item "><i class="fas fa-edit"></i> Edit</a>
                                            @endif
                                            @if (in_array('agent.orderrequest.delete', config('permission')))  
                                                <a  onclick="return confirm('Are you sure you want to delete this ?');"  href="{{url('agent-subscription-delete/'.$org.'/'.$id.'/'.$Controller->hashEncode($subscription['subn_id']))}}" class="dropdown-item"><i class="fas fa-trash-alt"></i>Delete</a>
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
                $.get("<?= url('') ?>/agent-subscription-view/"+$(this).attr('id'), function( data ) {
                  $('.timelines').html(data);
                  $('#modaldemo2').modal('show');
                });
                });
            </script>

             <script>
               $(function () {
                      'use strict'
                      $('[data-toggle="tooltip"]').tooltip();
                        var start = new Date();
        // set end date to max one year period:
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
        $('#magazine_body').html('');
            $('.magazine_body_spinner').show();
            $.get("<?= url('') ?>/agent-magazine-list/"+moment(e.date).format('YYYY-MM-DD'), function( data ) {
                $('#magazine_body').html(data);
                $('[data-toggle="tooltip"]').tooltip();
                $('.magazine_body_spinner').hide();
            });
            
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
        });
        $('.datepicker-start').mask('99-9999');
        $('.datepicker-end').mask('99-9999');
        $("#commentForm").validate({
            rules: {
                start_date: {
                    required: true,
                },
                end_date: {
                    required: true,
                },
                magazine: {
                    required: true,
                },
                quantity: {
                    required: true,
                    number: true,
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
