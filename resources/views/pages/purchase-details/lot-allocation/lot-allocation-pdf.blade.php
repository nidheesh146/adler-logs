<!DOCTYPE html>
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
                    <td rowspan="3" style="text-align:center;font-weight:bold;">LOTCARD FOR RECEIVED MATERIAL</td>
                    <td style="font-size:12px;">DOC NO: ST/F-02</td>  
                </tr>
                <tr>
                    <td style="font-size:12px;">REV NO: 03</td>
                </tr>
                <tr>
                    <td style="font-size:12px;">REV DATE: 16-MAY-2017</td>
                </tr>
            </table>
        </div>
        <div class="" style="margin-top:20px;">
            <table  border='1' style="width:67%;float:left;">
                <tr>
                    <th>ITEM DESCRIPTION</th>
                </tr>
                <tr style="max-height:200px;">
                    <td><br/>{{$lot['discription']}}<br/></td>
                </tr>
            </table>
            <table  border='1' style="width:32%;float:left;margin-left:10px;">
                <tr>
                    <th>MATERIAL CODE</th>
                </tr>
                <tr style="height:200px;">
                    <td><br/>{{$lot['item_code']}}<br/><br/><br/></td>
                </tr>
            </table>
        </div>
        <div class="" style="margin-top:15%;">
            <table  border='1' style="width:40%;float:left;">
                <tr>
                    <th>MATERIAL SPECIFICATION</th>
                </tr>
                <tr style="height:200px;">
                    <td>{{$lot['specification']}}<br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:30%;float:right;">
                <tr>
                    <th>LOT NUMBER</th>
                </tr>
                <tr style="height:200px;">
                    <td>{{$lot['lot_number']}}<br/><br/></td>
                </tr>
            </table>
        </div>
        <div class="" style="margin-top:10%;">
            <table  border='1' style="width:30%;float:right;">
                <tr>
                    <th>INVOICE QTY</th>
                </tr>
                <tr style="height:200px;">
                    <td>{{$lot['invoice_qty']}}<br/><br/></td>
                </tr>
            </table>
            
        </div>
        <div class="" style="margin-top:12%;">
            <table  border='1' style="width:25%;float:left;">
                <tr>
                    <th>QUANTITY RECEIVED</th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:25%;float:left;margin-left:15px;">
                <tr>
                    <th>QUANTITY ACCEPTED</th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:25%;float:left;margin-left:15px;">
                <tr>
                    <th>QUANTITY REJECTED </th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:10%;float:left;margin-left:25px;">
                <tr>
                    <th>UNIT </th>
                </tr>
                <tr style="height:200px;">
                    <td><br/>{{$lot['unit_name']}}<br/></td>
                </tr>
            </table>
        </div>
        <div class="" style="margin-top:13%;">
            <table  border='1' style="width:25%;float:left;">
                <tr>
                    <th>RECEIVED DATE</th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:25%;float:left;margin-left:15px;">
                <tr>
                    <th>INVOICE NUMBER</th>
                </tr>
                <tr style="height:200px;">
                    <td>{{$lot['invoiceNumber']}}<br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:25%;float:left;margin-left:15px;">
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
                    <td>{{$lot['po_number']}}<br/><br/></td>
                </tr>
            </table>
            
        </div>
        <div class="" style="margin-top:14%;">
            <table  border='1' style="width:40%;float:left;">
                <tr>
                    <th>VEHICLE NO</th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br/><br/></td>
                </tr>
            </table>
            <table  border='1' style="width:60%;float:left;margin-left:15px;">
                <tr>
                    <th>TRANSPORTER NAME</th>
                </tr>
                <tr style="height:200px;">
                    <td><br/><br/><br/></td>
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