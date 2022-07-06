@extends('layouts.default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
<div class="az-content az-content-dashboard">
    <div class="container">
      <div class="az-content-body">
      <div class="az-content-breadcrumb">
      <span>Magazine</span>
            <span>Magazine List</span>
          </div>
          <h4 class="az-content-title" style="font-size: 20px;">Magazine ( {{date('M-Y')}} )
          
            {{-- @if (in_array('magazine.edit',config('permission'))) 
          <a href="{{url('magazine/special-price')}}" style="float: right;" class="badge badge-pill badge-dark "><i
              class="fas fa-plus"></i> Special price </a>
              @endif --}}
          
          </h4>
          
          
          @include('includes.magazine-nav')
    

         
<div class="row row-sm mg-b-20 mg-lg-b-0">
      <div class="table-responsive">
        @if (Session::get('success'))
        <div class="alert alert-success " style="width: 100%;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="icon fa fa-check"></i> {{ Session::get('success') }}
        </div>
    @endif
   
   @if(config('organization')['type'] == 1)
    <span style="float: right;
    font-size: 11px;color: #edb408;
    border: solid 1px #edb408;
    padding: 0px 13px 0px 13px;
    margin-bottom: 9px;
    border-radius: 34px;"> <i class="fas fa-exclamation-triangle"></i> You can't modify the edition price , month , delete after approval</span>
    @endif     
           
           
           
           <table class="table table-bordered mg-b-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>ID</th>
                  <th>Magazine</th>
                  <th style="text-align: right;"> Magazine price</th>
                  @if(config('organization')['type'] == 1 || config('organization')['type'] == 2 )
                  <th>Edition</th>
                  <th>Work order date</th>
                 
                  <th style="text-align: right;">edition price</th>
                
                  @if (in_array('magazine.edit',config('permission')) || in_array('magazine.IssueWorkOrder',config('permission')) || in_array('magazine.invoice',config('permission'))) 
                  <th>	</th>
                  @endif
                  @endif

                </tr>
              </thead>
      

              <tbody>
                @foreach ( $data['magazine']  as $magazine)
                
                <tr >
                  <td>
                  @if($magazine['special_id'])
                  @if(!$magazine['approve'])
                  <svg xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-html="true" data-placement="top"  title="Not issue work order" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 309.321 309.321" style="enable-background:new 0 0 309.321 309.321;width: 17px;" xml:space="preserve">
                    <g>
                      <path style="fill:#010002;" d="M309.168,100.613c-6.124-49.631-29.23-91.072-80.074-91.501V9.068c-0.131,0-0.261,0.022-0.392,0.022   s-0.261-0.022-0.392-0.022v0.044c-50.844,0.43-70.441,41.87-76.566,91.501c-1.104,8.942,3.911,16.274,9.964,16.274   c6.054,0,10.829-7.343,12.102-16.263c5.385-37.899,15.306-69.756,54.896-69.968c39.591,0.212,53.02,32.074,58.405,69.968   c1.267,8.926,6.048,16.263,12.102,16.263C305.268,116.886,310.272,109.554,309.168,100.613z"/>
                      <path style="fill:#010002;" d="M178.407,126.889c-1.643,1.648-4.226,2.877-8.289,2.877h-22.322c0,0-5.091,0.31-8.654-2.687   c-3.911-3.291-10.704-8.284-19.717-8.284H84.028c-9.013,0-16.111,4.563-19.564,8.023c-1.681,1.681-4.226,2.953-8.088,2.953H34.043   c-3.851,0-6.44-1.392-8.18-3.198c-3.459-3.595-11.683-8.474-18.596-2.687C3.247,127.248,0,132.681,0,141.465v134.671   c0,0-0.337,24.117,23.742,24.117h157.374c0,0,23.013-0.37,23.013-22.349V157.783c0-9.013-0.865-24.062-6.456-31.133   c-0.56-0.707-1.159-1.398-1.811-2.051C189.514,118.208,181.915,123.359,178.407,126.889z M114.645,238.221   c0,7.707-5.629,13.957-12.581,13.957c-6.946,0-12.575-6.249-12.575-13.957c0-7.713-3.563-16.502-5.852-21.109   c-1.343-2.709-2.105-5.755-2.105-8.985c0-11.34,9.197-20.532,20.538-20.532c11.346,0,20.538,9.192,20.538,20.532   c0,3.236-0.761,6.277-2.105,8.985C118.207,221.713,114.645,230.508,114.645,238.221z"/>
                    </g>
                    </svg>
                    @else 
                      <i class="fas fa-lock" data-toggle="tooltip" data-html="true" data-placement="top"  title="Issued work order"></i>
                    @endif
                  @endif
                </td>
                <td> 
                  @if (in_array('magazine.edit',config('permission'))) 
                     <a href="{{url('magazine/update/'.$magazine['id'])}}"> {{$magazine['magazine_id']}}</a>
                 @else
                     {{$magazine['magazine_id']}}
                 @endif
                </td>
                 
                  <th scope="row">
                    {{$magazine['name']}}     
                  </th>
                  <td style="text-align: right;">
                    {{sprintf("%.3f",$magazine['price'])}}
                  </td>
                  @if(config('organization')['type'] == 1 || config('organization')['type'] == 2 )
                  <th >
                    @if (in_array('edition.edit',config('permission'))) 
                   @if($magazine['edition_name'])
                    <a href="{{url('magazine/add-edition/'.$Controller->hashEncode($magazine['special_id']))}}" >{{$magazine['edition_name']}} </a> @else -  @endif
                    @else
                    @if($magazine['edition_name']){{$magazine['edition_name']}}  @else -  @endif
                    @endif
                  </th>

                  <td >
                    @if($magazine['work_order_date'])
                    {{date('d-m-Y', strtotime($magazine['work_order_date']))}}
                    @else 
                      -
                    @endif
                  </td>


                  <td style="text-align: right;">
                    @if($magazine['edition_price'])
                    {{sprintf("%.3f",$magazine['edition_price'])}}
                    @else 
                      -
                    @endif
                  </td>
               
               
                  @if (in_array('magazine.edit',config('permission')) || in_array('magazine.IssueWorkOrder',config('permission')) || in_array('magazine.invoice',config('permission'))) 
                  <td>
                <button data-toggle="dropdown" style="width: 64px;" class="badge badge-success">Action<i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                 <div class="dropdown-menu">
                
                  @if (in_array('edition.edit',config('permission'))) 
                  @if($magazine['edition_price'])
                    <a class="dropdown-item" href="{{url('magazine/add-edition/'.$Controller->hashEncode($magazine['special_id']))}}" ><i class="fas fa-edit"></i> Edition </a>
                    @endif
                    @endif
                 
                   @if (in_array('magazine.edit',config('permission'))) 
                      <a class="dropdown-item" href="{{url('magazine/update/'.$magazine['id'])}}" ><i class="fas fa-edit"></i> Magazine</a>
                   @endif
                  
                   @if (in_array('magazine.IssueWorkOrder',config('permission'))) 
                  @if($magazine['special_id'])
                  @if(!$magazine['approve'])
                       <a class="dropdown-item" onclick="return confirm('Are you sure you want to approve this {{$magazine['magazine_id'].'-'.$magazine['name'].'-'.$magazine['edition_name']}} edition ?');"  href="{{url('magazine/edition-approve/'.$Controller->hashEncode($magazine['special_id']))}}" ><i class="fas fa-check"></i> issue work order</a>
                  @else 
                  @endif
                  @endif
                  @endif
                
               @if (in_array('magazine.invoice',config('permission'))) 
                @if($magazine['special_id'])
                @if($magazine['approve'])
                 <a href="#" id="{{$magazine['id']}}" rel="" class="dropdown-item orgstructure"><i class="fas fa-file-invoice-dollar"></i>  Invoice</a>
                @endif                        
                @endif
                @endif

                 </div>
                  </td>
                  @endif
                  @endif
            
                </tr>
                @endforeach
              </tbody>
   
            </table>
          </div>
       </div>
      </div><!-- az-content-body -->
    </div>
  </div><!-- az-content -->

  <div id="modaldemo2" class="modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
        <span>Generate an invoice</span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
            <div class="modal-body" style="padding: 0px;">
                <div class="container">
                  
                  <div class="alert alert-success  invoice-success" style="width: 100%;margin: 12px 11px -16px 0px;display:none;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-check"></i> 
                    Successfully generated all invoices for agents
                </div>



                    <div class="timeline">
                      
                    </div>
                    <div class="agentspinner" style="text-align: center;display:none;">
                      <div class="spinner-grow text-primary spinner-grow-sm" role="status"></div>
                      <div class="spinner-grow text-secondary spinner-grow-sm" role="status"></div>
                      <div class="spinner-grow text-success spinner-grow-sm" role="status"></div>
                      <div class="spinner-grow text-danger spinner-grow-sm" role="status"></div>
                      <div class="spinner-grow text-warning spinner-grow-sm" role="status"></div>
                      <div class="spinner-grow text-info spinner-grow-sm" role="status"></div>
                      <div class="spinner-grow text-light spinner-grow-sm" role="status"></div>
                      <div class="spinner-grow text-dark spinner-grow-sm" role="status"></div>
                    </div>
                </div>

            </div>
            {{-- <div class="modal-footer justify-content-center"> --}}
        {{-- <button type="button" class="btn btn-indigo">Save changes</button>
        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button> --}}
      {{-- </div> --}}
        </div>
    </div><!-- modal-dialog -->
</div><!-- modal -->




  <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?=url('');?>/js/azia.js"></script>
  <script>
    $('[data-toggle="tooltip"]').tooltip();
  </script>

<script>
  $('.orgstructure').click(function(){
    $('#modaldemo2').modal('show');
    $('.invoice-success').hide();
    $('.agentspinner').show();
    $('.timeline').html('');
    $.ajax({
    url: "<?= url('') ?>/agent-invoice-view/<?=date('Y-m').'-01';?>/"+$(this).attr('id'),
    type: 'GET',
    success: function(data){ 
      $('.timeline').html(data);
      $('.agentspinner').hide();
    },
    error: function(data) {
      $('.agentspinner').hide();
    }
});



 });
 
 function invoice_generate(date,magazine_id){
  $('.invoice-success').hide();
    $.ajax({
    url: "<?= url('') ?>/generate-invoice/<?=date('Y-m').'-01';?>/"+magazine_id,
    type: 'GET',
    success: function(data){ 

      if(data.pending != 0 ){
         invoice_generate(date,magazine_id);
         $('.generate_inv').hide();
      }else{
        $.ajax({
          url: "<?= url('') ?>/agent-invoice-view/<?=date('Y-m').'-01';?>/"+magazine_id,
          type: 'GET',
          success: function(data){ 
            $('.timeline').html(data);
            $('.invoice_spinner').hide();
            $('.agentspinner').hide();
            $('.invoice-success').show();
          },
          error: function(data) {
            $('.invoice_spinner').hide();
            $('.agentspinner').hide();
          }
         });
      }
    },
    error: function(data) {
      $('.agentspinner').hide();
      $('.invoice_spinner').hide();
    }
});



 }
function generate_invoice(date,magazine_id){
  if(confirm("are you sure you want to generate this invoice ?")){
    $('.agentspinner').show();
    $('.invoice_spinner').show();
      invoice_generate(date,magazine_id);
  }

}
 </script>

@stop