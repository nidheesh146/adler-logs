@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
      <div class="az-content-body">
          <div class="az-content-breadcrumb"> 
               <span><a href="">Terms and Conditions</a></span>
          </div>
          <h4 class="az-content-title" style="font-size: 20px;">Terms and Conditions 
                <div class="right-button">
                <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                    <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                <div class="dropdown-menu">
                <a href="#" class="dropdown-item">Excel</a>
  
                </div> -->
            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/terms-and-conditions-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Terms and Conditions </button> 
                </div>
          </h4>
          <div class="az-dashboard-nav">
              <nav class="nav"> </nav>	
          </div>
          @if (Session::get('success'))
          <div class="alert alert-success " style="width: 100%;">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <i class="icon fa fa-check"></i> {{ Session::get('success') }}
          </div>
          @endif
               
          <div class="table-responsive">
              <table class="table table-bordered mg-b-0" id="example1">
                  <thead>
                      <tr>
                          <th style="width:70%">Title</th>
                          <th>Action</th>
                      
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ( $data['terms'] as $item)
                     <tr>
                          <td > {{$item->title}}</td>
                          <td>
                          <a class="badge badge-info lot-edit" style="font-size: 13px;" href="#"  id="{{$item->id}}" data-toggle="modal" data-target="#myModal"><i class="fas fa-eye"></i> View </a>  
                          <a class="badge badge-primary" style="font-size: 13px;" href="{{url('inventory/terms-and-conditions-add/'.$item->id)}}"><i class="fas fa-edit"></i> Edit</a>
                                  
                          </td>
                      </tr>
                      @endforeach
                </tbody>
              </table>
              <div class="box-footer clearfix">
                {{ $data['terms']->appends(request()->input())->links() }}
            </div>
          </div>
      </div>
  </div>
      <!-- az-content-body -->
      <!-- Modal content-->
  
      <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog modal-lg" style="max-width: 97% !important;">
              
                <!-- Modal content-->
              <div class="modal-content">
                  <div class="modal-header" style="display: block;">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Terms and Condition </h4>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                            <table class="table table-bordered mg-b-0">
                       
                           
                            
                              
                              
                                  <tr>
                                    <th>Title</th>
                                    <td class="ts_title"></td>
                             
                                  </tr>
                                    
                                  <tr>
                                    <th>Terms and Condition</th>
                                    <td class="ts_tc"></td>
                             
                                  </tr>
                            
                              </table>

                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <br>
                    
                  </div>
                </div>
                
              </div>
          </div>

          <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
          <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
 
  

  </div>
<script>
$('.lot-edit').click(function(){
    let id = $(this).attr('id');
        $('.ts_tc').html("");
        $('.ts_title').html("");
    $.get("{{url('inventory/terms-and-conditions-get')}}/"+id,function(response){

        $('.ts_tc').html(response.terms_and_conditions.replace(/\n/g, "<br />"));
        $('.ts_title').html(response.title);
    });

})

</script>




@stop