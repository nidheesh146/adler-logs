<html lang="en">
    <head>       
       <title>Invoice</title>
    <link rel="stylesheet" href="{{url('css/bootstrap-pdf.css')}}">
     </head>

<style>
.container{
    max-width: 100%;
}
@page {
            margin: 0px 0px 0px 0px !important;
            padding: 0px 0px 0px 0px !important;
        }
.table-invoice{

}        
</style>





   <body>

      <!-- az-header -->
            <!-- az-content-left -->
            <div class="az-content-body az-content-body-invoice ps">
               <div class="card card-invoice">
                  <div class="card-body">
                     <div class="invoice-header">
                        <h1 class="invoice-title" style="float: right; color: #66696c5c;">Invoice</h1>
                        <div class="billed-from">
                        <h6>{{$invoice['org_name']}}</h6>
                           <p><span>{{$invoice['org_address']}}</span><br>
                              <span>Tel No: {{$invoice['org_phone']}}</span><br>
                              <span>Email: {{$invoice['org_email']}}</span>
                           </p>
                        </div>
                        <!-- billed-from -->
                     </div>
                     <!-- invoice-header -->
       
                     <div class="table-responsive mg-t-40">
                        <table class="table table-invoice">
                      
                            <tbody>
                         
                               <tr >
                                  <td  style="padding-left:0px;"colspan="2" >
                                      <span style="color: #66696c5c;">Billed To ,</span><br>
                                      <span> {{$invoice['subscriber_id']}} </span><br>
                                      <span> {{$invoice['f_name']}}  {{$invoice['l_name']}}</span><br>
                                      @if(!empty($invoice['care_of']))
                                      <span>  {{$invoice['care_of']}}</span><br>
                                      @endif
                                      <span>{{$invoice['shipping_address']}}</span><br>
                                      <span>Pin : {{$invoice['pincode']}}</span><br>
                                      <span>Tel No: {{$invoice['agent_phone']}}</span><br>
                                      <span>Email: {{$invoice['agent_email']}}</span><br>
                                  </td>
                                  <td style="padding-right:0px;">
                                    <table class="table table-bordered">
                                        <tbody>
                                           <tr>
                                              <td colspan="2" >Invoice Information</td>
                                              
                                           </tr>
                                           <tr>
                                            <td >Invoice NO:</td>
                                           <td>{{$invoice['invoice_id']}}</td>
                                         </tr>
                                         <tr>
                                            <td>Subscription ID:</td>
                                            <td>{{$invoice['subscription_id']}}</td>
                                         </tr>
                                         <tr>
                                            <td>Issue Date:</td>
                                            <td>{{$invoice['issued_date'] ? date('d-m-Y',strtotime($invoice['issued_date'])):''}}</td>
                                         </tr>
                                         <tr>
                                            <td>Due Date:</td>
                                            <td>{{$invoice['expire_date'] ?  date('d-m-Y',strtotime($invoice['expire_date'])) : ''}}</td>
                                         </tr>
                                  
                                        </tbody>
                                     </table>
                                 </td>
                               </tr>
                            </tbody>
                         </table>

                        <table class="table ">
                           <thead>
                              <tr>
                                 <th class="wd-20p">MAGAZINE</th>
                        
                                 <th class="tx-center">QNTY</th>
                                 <th class="tx-right">Unit Price <br>(INR)</th>
                                 <th class="tx-right">Amount<br>(INR)</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                              <td><?=$invoice['magazine_id'].'-'.$invoice['name'].' ,<br>Edition : '.$invoice['edition_name'].'';?></td>
                        
                              <td class="tx-center">{{$invoice['quantity']}}</td>
                                 <td class="tx-right">{{sprintf("%.3f",$invoice['unit_price'])}}</td>
                                 <td class="tx-right">{{sprintf("%.3f",$invoice['amount'])}}</td>
                              </tr>
                             
                              <tr>
                                 <td colspan="2" rowspan="4" class="valign-middle">
                   
                                 </td>
                                 <td class="tx-right">Sub-Total</td>
                                 <td colspan="1" class="tx-right">{{sprintf("%.3f",$invoice['amount'])}}</td>
                              </tr>
                              {{-- <tr>
                                 <td class="tx-right">Tax (5%)</td>
                                 <td colspan="1" class="tx-right">$287.50</td>
                              </tr> --}}
                              <tr>
                                 <td class="tx-right">Discount ({{$invoice['current_commission']}})%</td>
                                 <td colspan="1" class="tx-right">-{{sprintf("%.3f",$invoice['commission'])}}</td>
                              </tr>
                              <tr>
                                 <td class="tx-right tx-uppercase tx-bold tx-inverse">Total Due (INR)</td>
                                 <td colspan="1" class="tx-right">
                                 <h4 class="tx-primary tx-bold">{{(sprintf("%.3f",$invoice['amount'] - $invoice['commission']))}}</h4>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                     <!-- table-responsive -->
                     {{-- <hr class="mg-b-40">
                     <a href="#" class="btn btn-primary btn-block">Pay Now</a> --}}
                  </div>
                  <!-- card-body -->
               </div>
               <!-- card -->
    
            </div>
            <!-- az-content-body -->

      </div>


   </body>
</html>