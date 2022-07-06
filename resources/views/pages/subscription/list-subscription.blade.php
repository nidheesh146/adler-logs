@extends('layouts.default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
@inject('SubscriptionController', 'App\Http\Controllers\Web\SubscriptionController')
<?php
$type = [1=>'state',2=>'district',3=>'mekhala',4=>'unit',5=>'agent'];
?>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span>Subscribers</span>
                    <span>All Subscribers</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Subscribers
                    <div>
                        <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;"
                            class="badge badge-pill badge-info ">
                            <i class="fa fa-download" aria-hidden="true"></i> Download <i
                                class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-item">Excel</a>
                        </div>
                    </div>
                    <div>
                        {{-- <button data-toggle="dropdown" style="float: right;" class="badge badge-pill badge-dark "><i
                                class="fas fa-plus"></i> Subscriber <i
                                class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button> --}}
                        {{-- <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('subscribers/create/'.$type[config('organization')['type']]) }}'" class="badge badge-pill badge-dark "><i
                                    class="fas fa-plus"></i> Subscriber  </button> --}}

                        {{-- <div class="dropdown-menu">
                            <a href="{{ url('subscribers/create/state') }}" class="dropdown-item">Under my
                                organization </a>
                            <a href="{{ url('subscribers/create/district') }}" class="dropdown-item"> Under
                                District</a>
                            <a href="{{ url('subscribers/create/mekhala') }}" class="dropdown-item">Under Mekhala</a>
                            <a href="{{ url('subscribers/create/unit') }}" class="dropdown-item">Under Unit</a>
                        </div> --}}
                    </div>
                </h4>

             @include('includes.subscribers')

                <div class="row row-sm mg-b-20 mg-lg-b-0">
                    <div class="table-responsive" style="margin-bottom: 13px;">
                        <table class="table table-bordered mg-b-0">
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <form>
                                        <div class="row filter_search" style="margin-left: 0px;">

                                            <div class="col-sm-10 col-md-8 col-lg-8 col-xl-8 row">
    
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label>Subscription ID</label>
                                                    <input type="text" class="form-control" value="{{request()->get('subscription_id')}}"  name="subscription_id" placeholder="Subscription ID">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label>Subscriber ID</label>
                                                    <input type="text" class="form-control" value="{{request()->get('subscriber_id')}}"  name="subscriber_id" placeholder="Subscriber ID">
                                                </div><!-- form-group -->
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
                                       


                                            </div>

                                            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12"
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
                                        </div>
                                        </form>
                                    </th>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="table-responsive">
                    @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                    </div>
                @endif

                    <table class="table table-bordered mg-b-0">
                        <thead>
                            <tr>
                                <th>SUBSCRIPTION ID</th>
                                <th>Suscriber ID</th>
                                <th>Magazine & Quantity </th>
                                <th>From</th>
                                <th>to</th>
                                <th>CREATED</th>
                                <th style="text-align: right;" >TOTAL</th>
                     
                                <th>Action</th>

                                {{-- <th>Users</th> --}}
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($data['suscription'] as $suscriber)
                                <tr>
                                    <th><a href="{{url('subscription/create/'.$type[config('organization')['type']].'/'.$Controller->hashEncode($suscriber['subr_id']).'/'.$Controller->hashEncode($suscriber['subn_id']))}}" >{{$suscriber['subscription_id']}}</a></th>
                                    <th><a   href=" {{ url('subscribers/create/' . $type[config('organization')['type']].'/'.$Controller->hashEncode($suscriber['subr_id'])) }}">{{$suscriber['subscriber_id']}}</a></th>
                                    <th> <?=$SubscriptionController->book_id($suscriber['subn_id']);?> </th>
                                    <td>{{date('M-Y',strtotime($suscriber['subscription_from']))}}</td>
                                    <td>{{date('M-Y',strtotime($suscriber['subscription_to']))}}</td>
                           
                                    <td>{{date('d-m-Y',strtotime($suscriber['created_at']))}}</td>
                                    <td style="text-align: right;">{{sprintf("%.3f",$suscriber['total_amount'])}}</td>
                                    <td>
                                        <button data-toggle="dropdown" class="badge  badge-primary">Active <i
                                            class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                    <div class="dropdown-menu">
                                    <a href="#" id="{{$Controller->hashEncode($suscriber['subn_id'])}}" class="dropdown-item subscription-view"><i class="fas fa-eye"></i> View</a>
                                    <a href="{{url('subscription/create/'.$type[config('organization')['type']].'/'.$Controller->hashEncode($suscriber['subr_id']).'/'.$Controller->hashEncode($suscriber['subn_id']))}}" class="dropdown-item "><i class="fas fa-edit"></i> Edit</a>
                                    <a  onclick="return confirm('Are you sure you want to delete this ?');"  href="{{ url('subscription-delete/'.$type[config('organization')['type']].'/'.$Controller->hashEncode($suscriber['subr_id']).'/'.$Controller->hashEncode($suscriber['subn_id'])) }}?list=true" class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>
                                    </div>
                                    </td>

                                </tr>
                            @endforeach




                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $data['suscription']->links(); }}
                    </div>
                </div>
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
    
    




        </div><!-- az-content-body -->
    </div>
    </div><!-- az-content -->




    <script src="<?= url('') ?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>


    <script>
          $('[data-toggle="tooltip"]').tooltip();
        $('.subscription-view').click(function(){
            $.get("<?= url('') ?>/subscription-view/"+$(this).attr('id'), function( data ) {
              $('.timelines').html(data);
              $('#modaldemo2').modal('show');
            });
            });

        </script>

@stop
