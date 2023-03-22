<!DOCTYPE html>
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\MRRController')
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>
    <title>lotallocation</title>
    <body style="font-size:12px;">
        <div class="top-head">
            <table border='1' style="width:100%;">
                <tr>
                    <td rowspan="3" style="text-align:center;"><img src="{{asset('/img/logo.png')}}"  style="width:60px;"></td>
                    <td rowspan="3" style="text-align:center;font-weight:bold;">
                    Format for:<br/>
                    LOT CARD FOR RECEIVED MATERIAL</td>
                    <td style="font-size:12px;">DOC NO: ST/F-02</td>  
                </tr>
                <tr>
                    <td style="font-size:12px;">REV NO: 00</td>
                </tr>
                <tr>
                    <td style="font-size:12px;">REV DATE: 14-DEC-2021</td>
                </tr>
            </table>
        </div>
        <div class="" style="margin-top:20px;">
            <div style="width:50%;float:left;">
                <table  border='1' style="width:49%;float:left;">
                    <tr>
                        <th>LOT NUMBER</th>
                    </tr>
                    <tr style="height:200px;">
                        <td><b>{{$lot['lot_number']}}</b><br/><br/></td>
                    </tr>
                </table>
                <table  border='1' style="width:49%;float:right;margin-left:10px;">
                    <tr>
                        <th>ITEM CODE</th>
                    </tr>
                    <tr style="height:200px;">
                        <td>{{$lot['item_code']}}<br/><br/></td>
                    </tr>
                </table>
                <br/>
                <div style="margin-top:50px; position: relative;">
                    <table  border='1' style="width:49%;float:left;">
                        <tr>
                            <th>INVOICE QTY</th>
                        </tr>
                        <tr style="height:200px;">
                            <td>{{$lot['invoice_qty']}}<br/><br/></td>
                        </tr>
                    </table>
                    <table  border='1' style="width:49%;float:right;margin-left:10px;">
                        <tr>
                            <th>UNIT </th>
                        </tr>
                        <tr style="height:200px;">
                            <td>{{$lot['unit_name']}}<br/><br/></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="width:49%;float:right;">
                <table  border='1' style="width:100%;margin-left:185px;">
                    <tr>
                        <th>ITEM DESCRIPTION</th>
                    </tr>
                    <tr style="font-size:10px;">
                        <td>{{$lot['short_description']}}<br/></td>
                    </tr>
                </table>
            </div>
            <!-- <table  border='1' style="width:24%;float:left;">
              <tr>
                    <th>LOT NUMBER</th>
                </tr>
                <tr style="height:200px;">
                    <td><b>{{$lot['lot_number']}}</b><br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:24%;float:left;margin-left:10px;">
                <tr>
                    <th>ITEM CODE</th>
                </tr>
                <tr style="height:200px;">
                    <td>{{$lot['item_code']}}<br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:49%;float:left;margin-left:10px;">
                <tr>
                    <th>ITEM DESCRIPTION</th>
                </tr>
                <tr style="height:500px;font-size:13px;">
                    <td>{{$lot['short_description']}}<br/></td>
                </tr>
            </table> -->
            
        </div>
        <div class="" style="margin-top:12%;">
            
            
        </div>
        
        <div class="" style="margin-top:12%;">
            <table  border='1' style="width:33.33%;float:left;">
                <tr>
                    <th>QUANTITY RECEIVED</th>
                </tr>
                <tr style="height:200px;">
                    <td><br/>{{$lot['qty_received']}}<br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:33.33%;float:left;margin-left:15px;">
                <tr>
                    <th>QUANTITY ACCEPTED</th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:33.33%;float:left;margin-left:15px;">
                <tr>
                    <th>QUANTITY REJECTED </th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br/><br/></td>
                </tr>
            </table>
           
        </div>
        <div class="" style="margin-top:13%;">
            <table  border='1' style="width:33.33%;float:left;">
                <tr>
                    <th>RECEIVED DATE</th>
                </tr>
                <tr style="height:200px;">
                    <td>{{date('d-m-Y',strtotime($lot['invoice_created']))}}<br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:33.33%;float:left;margin-left:15px;">
                <tr>
                    <th>INVOICE NUMBER</th>
                </tr>
                <tr style="height:200px;">
                    <td>{{$lot['invoiceNumber']}}<br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:33.33%;float:left;margin-left:15px;">
                <tr>
                    <th>INVOICE DATE </th>
                </tr>
                <tr style="height:200px;">
                    <td>{{date('d-m-Y',strtotime($lot['invoice_date']))}}<br/><br/></td>
                </tr>
            </table>
        </div>
        <div class="" style="margin-top:14%;">
            <table  border='1' style="width:60%;float:left;">
                <tr>
                    <th>SUPPLIER CODE & NAME</th>
                </tr>
                <tr style="height:200px;">
                    <td>{{$lot['vendor_id']}}-{{$lot['vendor_name']}}<br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:40%;float:left;margin-left:15px;">
                <tr>
                    <th>PURCHASE ORDER NO</th>
                </tr>
                <tr style="height:200px;">
                    @if(!$lot['po_number'])
                        <?php $pos=$fn->getPO_for_merged_si_item($lot['si_invoice_item_id']); ?>
                        <td>
                        @foreach($pos as $po)
                            {{$po['po_number']}}, 
                        @endforeach
                        <br/><br/>
                        </td>
                    @else
                        <td>{{$lot['po_number']}}<br/><br/></td>
                    @endif
                    
                </tr>
            </table>
            
        </div>
        <div class="" style="margin-top:14%;">
            <table  border='1' style="width:40%;float:left;">
                <tr>
                    <th>VEHICLE NO</th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br/>{{$lot['vehicle_number']}}<br/></td>
                </tr>
            </table>
            <table  border='1' style="width:60%;float:left;margin-left:15px;">
                <tr>
                    <th>TRANSPORTER NAME</th>
                </tr>
                <tr style="height:200px;">
                    <td><br/>{{$lot['transporter_name']}}<br/><br/></td>
                </tr>
            </table>
            
        </div>
        <div class="" style="margin-top:14%;">
            <table  border='1' style="width:25%;float:left;">
                <tr>
                    <th>MRR NUMBER</th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:25%;float:left;margin-left:5px;">
                <tr>
                    <th>MRR DATE</th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:25%;float:left;margin-left:5px;">
                <tr>
                    <th>TEST REPORT NUMBER </th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:25%;float:left;margin-left:5px;">
                <tr>
                    <th>TEST REPORT DATE  </th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br/><br/></td>
                </tr>
            </table>
        </div>
        <div class="" style="margin-top:14%;">
            <table  border='1' style="width:102%;float:left;">
                <tr>
                    <th>PREPARED BY I/C ST</th>
                    <th>APPROVED BY I/C QC</th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br><br/></td>
                    <td><br/><br><br/></td>
                </tr>
            </table>        
        </div>
    </body>
</html>