<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    @if($type =='cancel')
    <title> @if(str_starts_with($final_purchase['po_number'] , 'PO') )Purchase Order @else Work Order @endif Cancellation _{{$final_purchase['vendor_name']}}_{{$final_purchase['po_number']}}</title>
    @else 
    <title>Final @if(str_starts_with($final_purchase['po_number'] , 'PO') )Purchase Order @else Work Order @endif _{{$final_purchase['vendor_name']}}_{{$final_purchase['po_number']}}</title>
    @endif
</head>
<body>
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\PurchaseController')
    <style>
        .col1,.col3{
            float:left;
            width:23%;
            font-size:11px;
        }
        .col2{
            width:45%;
            float:left;
        }
        .attn {
            margin-top:32px;
            font-weight:bold;
           font-size:10px; 
           color:#1434A4;
        }
        .main-head{
            margin-top:10px;
            font-size:24px;
            font-weight:bold;
            font-style:Italic;
        }
        .col21{
            margin-top:-25px;
            float:left; 
            width:70%;
        }
        .col22{
            margin-top:-25px;
            float:left;
            width:30%;
        }
         .row2{
            display:block;
            font-size:11px;
            height:35px;
            border-bottom:solid 0.5px black;
        }
        .row3, .row4{
            display:block;
        }
        .intro{
            font-size:11px;
            font-style:italic;
            padding:10px;
        }
        .row3 table{
            width:100%;
            font-size:10px;
        }
        .row4{
            font-size:10px;
        }
        .col41, .col42{
            width:35%;
            float:left;
        }
        .col43{
            font-size:11px;
            float:right;
        }
        .remarks, .adler {
            height:50px;
        }
        .row3 table th{
            background-color:#B6D0E2;
        }
        .col51, .col52{
            font-size:11px;
            width:33%;
            float:left;
        }
        .col52{
            text-align:center;
        }
        .col53{
            font-size:11px;
            text-align:right;
            float:right;
        }
    </style>
   
    <div class="row1" style="height:130px;border-bottom:solid 2px black;">
        <div class="col1">
            To<br/>
            <strong>{{$final_purchase['vendor_name']}}</strong>
            <p>{{$final_purchase['address']}}<br/>
            <?php  
            if (!function_exists('SplitPhone'))
            {
            function SplitPhone($data)
            {
                $a = "";
                $arr = explode(",",ltrim(rtrim($data,']'),'['));
                $len = count($arr);
                echo trim($arr[0],' " ');
                
            }
            }
            if (!function_exists('SplitMail'))
            {
            function SplitMail($data)
            {
                $a = "";
                $arr = explode(",",ltrim(rtrim($data,']'),'['));
                $len = count($arr);
                echo trim($arr[0],' " ');
            }
            }
            ?>
            Cell No : {{ SplitPhone($final_purchase['contact_number']) }}<br/>
            <span style="font-size:10px;  overflow-wrap: break-word;">Email:<?php SplitMail($final_purchase['email'])?><br/><span>
            GSTIN :</p>

        </div>
        <div class="col2" style="text-align:center;">
            <div class="attn">Kind Attn: {{$final_purchase['contact_person']}}</div>
            <div class="main-head">
                @if($type=='cancel')
                    @if(str_starts_with($final_purchase['po_number'] , 'PO') )
                    Purchase Order Cancellation
                    @else
                    Work Order
                    @endif
                @else
                @if(str_starts_with($final_purchase['po_number'] , 'PO') )
                    Purchase Order 
                    @else
                    Work Order
                    @endif
                @endif
            </div>
        </div>
        <div class="col3">
            From<br/>
            <span style="color:#1434A4;"><strong>ADLER HEALTHCARE PVT. LTD</strong></span>
            <p> Plot No-A1 MIDC, Sadavali(Devrukh),  Tal- Sangmeshwar, Dist -Ratnagiri ,  PIN-415804, Maharashtra, India<br/>
            CIN : <br/>
            Company GSTIN :</p>
        </div>
        <div class="col4" style="float:right;">
            <img src="{{asset('/img/logo.png')}}"  style="width:80px;">
        </div>
    </div><br/>
    <div class="row2">
        <div class="col21">
            <table>
                <tr>@if($type=='cancel')
                    <td>PO No</td>
                    <?php $poc_no = substr_replace($final_purchase['po_number'], 'I', 2, strlen('I')); ?>
                    <td>: {{$poc_no}}</td>
                    @else
                    <td>Supplier Quotation No</td>
                    <td>: {{$final_purchase['rq_no']}}</td>
                    @endif
                </tr>
                <tr>
                    @if($type=='cancel')
                    <td >PO Date</td>
                    <td>: {{date('d-m-Y',strtotime($final_purchase['po_date']))}}</td>
                    @else
                    <td >Supplier Quotation Date</td>
                    <td>: {{date('d-m-Y',strtotime($final_purchase['quotation_date']))}}</td>
                    @endif
                </tr>
                <tr>
                    <td >Currency</td>
                    <td>: {{$final_purchase['currency_code']}}</td>
                </tr>
            </table>
        </div>
        <div class="col22">
            <table style="float:right;">
                <tr>
                    <td>@if($type=='cancel')POC @else PO @endif No</td>
                    <td>: {{$final_purchase['po_number']}}</td>
                </tr>
                <tr>
                    <td>@if($type=='cancel')POC @else PO @endif Date</td>
                    <td>:@if($type=='cancel') {{date('d-m-Y',strtotime($final_purchase['updated_at']))}} @else {{date('d-m-Y',strtotime($final_purchase['po_date']))}} @endif</td>
                </tr>
                <tr>
                    <td>Department</td>
                    <td>: {{$final_purchase['dept_name']}}</td>
                </tr>
            </table>
        </div>
        
    </div>
    <style>
        th{
            text-align:center;
        }
    </style>
    <div class="row3">
        <div class="intro">
            @if($type!='cancel')
                We are pleased to place an order for the following items at the Terms and Conditions given herewith. Please sign a copy of the same and return it to us as an acceptance. 
            @endif
        </div>
        <table border="1">
            <tr>
                <th rowspan="2">S.NO</th>
                <th rowspan="2">
                    @if(str_starts_with($final_purchase['po_number'] , 'PO') ) HSN CODE 
                    @else
                    SAC CODE
                    @endif
                </th>
                <th rowspan="2">ITEM.NO</th>
                <th rowspan="2" width='40%'>ITEM DESCRIPTION</th>
                <th rowspan="2">QTY</th>
                <th rowspan="2">UNIT</th>
                <th rowspan="2">RATE</th>
                <th rowspan="2">VALUE</th>
                <th colspan="2">DISC</th>
                <th colspan="2">IGST</th>
                <th colspan="2">SGST/UTGST</th>
                <th colspan="2">CGST</th>
            </tr>
            <tr>
                <th>%</th>
                <th>Value</th>
                <th>%</th>
                <th>Value</th>
                <th>%</th>
                <th>Value</th>
                <th>%</th>
                <th>Value</th>
            </tr>
            <?php $i=1;
            $total = 0;
            $total_discount = 0;
            $total_igst = 0;
            $total_cgst = 0;
            $total_sgst = 0;
             ?>
            @foreach($items as $item)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$item['hsn_code']}}</td>
                <td>{{$item['item_code']}}</td>
                <td>{{$item['discription']}}</td>
                <td>{{$item['order_qty']}}</td>
                <td>{{$item['unit_name']}}</td>
                <td>{{number_format((float)$item['rate'], 2, '.', '')}}</td>
                <td>{{number_format((float)($item['rate']* $item['order_qty']), 2, '.', '') }}</td>
                <td>{{$item['discount']}}</td>
                <?php $discount_value = ($item['rate']* $item['order_qty'])-(($item['rate']* $item['order_qty']*$item['discount'])/100);?>
                <td>{{number_format((float)(($item['rate']* $item['order_qty']*$item['discount'])/100), 2, '.', '')}}</td>
                <td>{{$item['igst']}}</td>
                <td>{{number_format((float)(($discount_value*$item['igst'])/100), 2, '.', '')}}</td>
                <td>{{$item['sgst']}}</td>
                <td>{{number_format((float)(($discount_value*$item['sgst'])/100), 2, '.', '')}}</td>
                <td>{{$item['cgst']}}</td>
                <td>{{number_format((float)(($discount_value*$item['cgst'])/100), 2, '.', '')}}</td>
                <?php 
                $total =$total+ $item['rate']* $item['order_qty'];
                $total_discount = $total_discount+($item['rate']* $item['order_qty']*$item['discount'])/100;
                $total_igst = $total_igst+($discount_value*$item['igst'])/100;
                $total_sgst = $total_sgst+($discount_value*$item['sgst'])/100;
                $total_cgst = $total_cgst+($discount_value* $item['order_qty']*$item['cgst'])/100;
                ?>
            </tr>
            @endforeach
        
        </table>
    </div>
    <div class="row4" style="border-bottom:solid 1px black;height:170px;">
        <div class="col41">
            <div class="remarks">
                <strong>Remarks/Notes </strong>
            </div>
            <div class="adler">
                <i>For</i>
                <br/>
                <strong>ADLER HEALTHCARE PVT. LTD</strong>
            </div>
            <div class="sign">
                (Authorized Signatory)<br/>
                (Software Generated Document-No Signature Required)
            </div>
            
        </div>
        <div class="col42">
            <div class="" style="height:50px;">
            </div>
            <div class="supplier-accept" style="height:50px;">
                <b>Supplier Acceptance</b>
            </div>
            <div class="supplier-accept" style="height:50px;">
                <b>(Supplier Signature & Date)</b><br/>
                @if($type=='cancel')
                <span>I/We, hereby cancel this order.</span>
                @else
                <span>I/We, hereby accept this order with the term mentioned herein.</span>
                @endif
            </div>
        </div>
        <div class="col43">
            <table style="height:130px;">
                <tr>
                    <td style="width:160px">Sum of Line Value</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)$total, 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Total Discount</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)$total_discount, 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Transportation & Freight Charge</td>
                    <td style="width:30px;"><?php $freight_charge = $fn->find_freight_charge($final_purchase['rq_master_id'],$final_purchase['supplierId']); ?>{{--$final_purchase['rq_master_id']}}:{{$final_purchase['supplierId']--}}</td>
                    <td style="text-align:right;">{{number_format((float)($freight_charge), 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Total Net Amount</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total-$total_discount+$freight_charge), 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Total IGST</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total_igst), 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Total SGST/UTGST</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total_sgst), 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Total CGST</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total_sgst), 2, '.', '')}}</td>
                </tr>
                <!-- <tr>
                    <td style="width:130px">Transportation & Freight Charge</td>
                    <td style="width:30px;">:</td>
                    <td style="float:right;">{{$total_discount}}</td>
                </tr> -->
                
            </table>
            <table style="border-bottom:solid 1px black;width:100%;border-top:solid 1px black;width:100%;">
                <tr>
                    <th style="width:130px">GRAND TOTAL</th>
                    <th style="width:30px;">:</th>
                    <th style="text-align:right;">{{number_format((float)($total-$total_discount+$freight_charge+$total_igst+$total_sgst+$total_sgst), 2, '.', '')}}</th>
                </tr> 
            </table>
        </div>
    </div>
    <div class="row5">
        <div class="col51">
            @if($type!='cancel')
                @if(str_starts_with($final_purchase['po_number'] , 'PO') )
                Document Format - PUR/F-04-00
                @else
                Document Format - PUR/F-20-00
                @endif
            @endif
        </div>
        <div class="col52">
            :{{$final_purchase['po_number']}}
        </div>
        <div class="col53">
            Page: 1 of 2
        </div>
    </div><br/>
    @if($type!='cancel')
    <div class="row6" style="font-size:10px;display:block;">
        <strong>Terms and Conditions</strong>
        <br/>
     <?php /* @if($final_purchase['supplier_type']=="direct" && (str_starts_with($final_purchase['po_number'] , 'PO')))
        <p>
        1. Payment Term - 30 days credit from the date of receipt of material or date of receipt of Invoice whichever is later.<br/>
        2. Delivery - Goods are to be delivered within 30 days from the receipt of P.O. We will inform the transport details & delivery address after material get ready for dispatch.<br/>
        3. Please quote the Purchase Order (P.O.) number on your commercial invoice and on any other correspondence in connection with this order. Please also mention Adler Item Code
        Number & HSN Code line by line.<br/>
        4. The Company does not accept any responsibility for the material / service that is received without authorized purchase / Work order.<br/>
        5. Order Acknowledgment - Please confirm acceptance of this order in any form - Acknowledgment on Adler PO / by e-mail / your standard format for order acknowledgment etc. In the absence of
        such acceptance, the delivery of any material, equipment or services shall constitute full acceptance by the Supplier of the terms and conditions herein.<br/>
        6. Site Visit - If the Supplier/ Service Provider, by the terms of this order., is required to perform any work in the Company's premises, the Supplier / Service Provider shall be responsible for any
        damages or injuries to persons or property including Company's employees and property, caused as a result of fault or negligence or for any reasons whatsoever, including omission or commission
        in doing or complying with certain requisites, statutory or otherwise by the Supplier and/or his agents. Before commencing contract work the Supplier shall furnish to the Company policies of
        insurance showing that the Supplier has taken adequate cover for public liability, property damage and workmen's compensation and cover the risk to property and body of the Supplier's
        employees.<br/>
        7. Supplies received as per the P.O. prior to approval shall not constitute an acceptance of the goods even if payment is made in advance.<br/>        
        8. Goods should be accompanied by your Commercial invoice with Adler GST Number 27AAJCB3689C1ZJ mentioned on it.<br/>
        9. If advance payment received from the Adler should be mentioned on the bills and the give the payment details.<br/>
        10. Any increase / reduction in government levies / taxes will be to the Adler's account.<br/>
        11. The Company reserves the right to cancel the P.O. or amend the quantities indicated in the P.O. arising out of any change in Company's sales requirements / manufacturing program or from
        any cause or causes beyond the Company's control.<br/>
        # Goods not conforming to the Company's specifications / standards / pre-shipment samples are liable to be rejected and the Company's decision in this regard will be final.<br/>
        # The report shall be sent to you mentioning why it has been rejected. In case of dispute on this report you can send your Representative for on-site re-verification of the report.<br/>
        # Rejected goods other than printed/promotional materials should be collected back within four weeks on receipt of intimation by the Supplier at his own cost and expenses, failing which the
        rejected material will be booked to the Supplier on freight to pay basis at Supplier's risk and cost.<br/>
        # Upon rejection of material, Adler will raise a Debit Note & against the same you shall issue credit note. In case of advance payment the supplier shall repay advance, so received, before taking
        back the rejected goods.<br/>
        # If any defects or discrepancies are notified in the supply at a later date, which went undetected at the time of supply, they shall be freely replaced by the Supplier. Rejected printed material will
        be destroyed by the Supplier in the presence of Company's representatives at Company's premises.<br/>
        12. The Company reserves the right to reject/accept goods delivered in excess of quantities ordered. If rejected by the Company, the Supplier shall comply with the requirements of clause above
        pertaining to the manner in which the rejected goods are to be dealt with.<br/>
        13. For domestic supplier - Transit insurance will be covered by Supplier for all risks up to delivery center and insurance charges will be to the Supplier's account unless otherwise specified.<br/>
        14. The Supplier shall be responsible for transport worthy packaging. In case of any loss or extra expenses due to damages in the transit on account of improper packing, supplier shall be
        responsible to pay such expenses. Packaging material used for supplies are on non-returnable basis unless otherwise agreed to by Company in writing.<br/>
        15. The Supplier guarantees that the sale or use of his products will not infringe any legislation, Indian or foreign concerning patent, design copyright or trademark and undertakes to indemnify
        and keep the company indemnified against any action, judgement, decree, cost, claim, demand and expense resulting from any actual or alleged violation / infringement of statutes concerning
        patent, design or trademark, copyrights etc. The Supplier further undertakes at his own expense to defend or assist in the defense of any suit or action which may be brought in this connection
        against the Company by any third party.<br/>
        16. The Company reserves the right to cancel the P.O. or part thereof, without ANY CANCELATION AMOUNT, if a stoppage of Company's manufacturing, trading or distribution activities, in total or
        in part occurs due to fire, worker's agitation, strike, lockout, Government legislation, force majeure or from any cause or causes beyond the Company's control.<br/>
        17. Any litigation arising out of or in connection with this order shall be subject to Ratnagiri Jurisdiction only.<br/>
        18. Qty. measured in co premised shall be considered as a final Qty. of material received.<br/>
        19. The terms and conditions indicated above supersedes all terms and conditions mentioned in the Supplier's/agents quotation/indent/invoice or any other documents pertaining to the
        transactions covered by this PO.<br/>
        20. Compliance Clause:<br/>
        Supplier is aware of the Social responsibility and which include principles against Bribery and Corruption Supplier hereby certifies that he does not and will not use illegal practices such as giving
        money or gifts to Adler employees or members of their families in exchange for business from Adler. Supplier also agrees that, in the event that Adler determines that a violation of principles
        against Corruption have occurred, Adler shall notify supplier, and Adler may terminate this agreement/PO immediately, and such termination shall be with cause. Supplier shall indemnify Adler and
        hold Adler harmless with respect to any liability arising from the contravention of this provision by supplier.<br/>
        21. Termination of Purchase Order - Company may at any time terminate any or all purchase orders placed by Company. Unless otherwise specified in this Agreement, Company's liability to
        Supplier with respect to such terminated purchase order or orders shall be limited to: (1) Supplier's purchase price of all components for the PRODUCT, plus (2) the actual costs incurred by
        Supplier in procuring and manufacturing PRODUCT in process at the date of the notice of termination; less (3) any salvage value thereof. If requested, Supplier agrees to substantiate such costs
        with proof satisfactory to Company.<br/>
        </p>
        @elseif(($final_purchase['supplier_type']=="indirect" && (str_starts_with($final_purchase['po_number'] , 'PO'))))
        <p>
        1. Material Should be verified and Tested at your end before dispatch for following points:<br/>
        a) Straightness of Material 1:1000mm<br/>
        b) Diameter tolerance should be as mentioned in above description / specification.<br/>
        c) Wooden Box packaging to prevent damage during tansit.<br/>
        d) Required Test Certificate for correctness of Mechanical & Chemical Properties along with material.<br/>
        e) Bar Length: The cut length of the bar require between 2.8 to 3.1 meters. Pls. ensure that it should not be more than 3.1 meters.<br/>
        2. Delivery - Ex-Works. The material is ex-stock available hence arrange the delivery within 2 weeks after receipt of Purchase Order.
        Item Sr. No 4 for delivery within 5-6 weeks after receipt of purchase order.<br/>
        # To be handed over to our nominated forwarder. Please quote the Purchase Order (P.O.) number on your commercial invoice and on any other correspondence in connection with this order. Please also mention Adler
        Item Code Number & HSN Code line by line.<br/>
        3. Payment Term - Advance 30% against order acknowledgement /Proforma Invoice & balance 70% after material get ready for dispatch against final Commercial Invoice.<br/>
        4. Manufacturer's Test Certificate for correctness of Chemical Composition, Mechanical Properties,Micro Structure Report & grain size Report as per relevant standard shall be accompanied the ordered material.<br/>
        5. Heat number Identification on each piece is required. in case of smallest dia wire,Tag of Lot No on the bundle is required.<br/>
        6. Material should Centrless ground, polished.<br/>
        7. Invoicing - Please send us the invoice for our approval before you hand over the shipment to our forwarder. We will verify the invoice and let you know change if any required for our custom clearance here in India.As soon as we send you revised invoice as required by us, you cans hand over the shipment with that inovice to our forwarder.<br/>
        8. Packaging - Export worthly packaging.<br/>
        9. Mode of shipment - By Air<br/>
        10. Pls. mentioned Item description in your Invoice & packing list as mentioned given below as it is<br/>
            <?php $i=1;?>
            @foreach($items as $item)
            Item SN {{$i}} - &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$item['short_description']}}<br/>
            <?php $i++; ?>
            @endforeach

        11. The Company does not accept any responsibility for the material that is received without authorized purchase order.<br/>
        12. Order Acknowledgment - Please confirm acceptance of this order in any form - Acknowledgment on Adler PO / by e-mail / your standard format for order acknowledgment etc. In the absence of
        such acceptance, the delivery of any material, equipment or services shall constitute full acceptance by the Supplier of the terms and conditions herein.<br/>
        13. Site Visit - If the Supplier, by the terms of this order., is required to perform any work in the Company's premises, the Supplier shall be responsible for any damages or injuries to persons or property including
        Company's employees and property, caused as a result of fault or negligence or for any reasons whatsoever, including omission or commission in doing or complying with certain requisites, statutory or otherwise
        by the Supplier and/or his agents. Before commencing contract work the Supplier shall furnish to the Company policies of insurance showing that the Supplier has taken adequate cover for public liability,
        property damage and workmen's compensation and cover the risk to property and body of the Supplier's employees<br/>
        14. Supplies received as per the P.O. prior to approval shall not constitute an acceptance of the goods even if payment is made in advance.<br/>
        15. The Company reserves the right to cancel the P.O. or amend the quantities indicated in the P.O. arising out of any change in Company's sales requirements / manufacturing program or from
        any cause or causes beyond the Company's control.<br/>
        # Goods not conforming to the Company's specifications / standards / pre-shipment samples are liable to be rejected and the Company's decision in this regard will be final.<br/>
        # The report shall be sent to you mentioning why it has been rejected. In case of dispute on this report you can send your Representative for on-site re-verification of the report.<br/>
        # Rejected goods other than printed/promotional materials should be collected back within four weeks on receipt of intimation by the Supplier at his own cost and expenses, failing which the
        rejected material will be booked to the Supplier on freight to pay basis at Supplier's risk and cost<br/>
        # Upon rejection of material, Adler will raise a Debit Note & against the same you shall issue credit note. In case of advance payment the supplier shall repay advance, so received, before taking
        back the rejected goods.<br/>
        # If any defects or discrepancies are notified in the supply at a later date, which went undetected at the time of supply, they shall be freely replaced by the Supplier. Rejected printed material will
        be destroyed by the Supplier in the presence of Company's representatives at Company's premises<br/>
        16. The Company reserves the right to reject/accept goods delivered in excess of quantities ordered. If rejected by the Company, the Supplier shall comply with the requirements of clause above
        pertaining to the manner in which the rejected goods are to be dealt with.<br/>
        17. The Supplier guarantees that the sale or use of his products will not infringe any legislation, Indian or foreign concerning patent, design copyright or trademark and undertakes to indemnify
        and keep the company indemnified against any action, judgement, decree, cost, claim, demand and expense resulting from any actual or alleged violation / infringement of statutes concerning
        patent, design or trademark, copyrights etc. The Supplier further undertakes at his own expense to defend or assist in the defense of any suit or action which may be brought in this connection
        against the Company by any third party.<br/>
        18. The Company reserves the right to cancel the P.O. or part thereof, without ANY CANCELATION AMOUNT, if a stoppage of Company's manufacturing, trading or distribution activities, in total or
        in part occurs due to fire, worker's agitation, strike, lockout, Government legislation, force majeure or from any cause or causes beyond the Company's control.<br/>
        19. Any litigation arising out of or in connection with this order shall be subject to RATNAGIRI Jurisdiction only.<br/>
        20. Qty. measured in co premised shall be considered as a final Qty. of material received<br/>
        21. The terms and conditions indicated above supersedes all terms and conditions mentioned in the Supplier's/agents quotation/indent/invoice or any other documents pertaining to the
        transactions covered by this PO.<br/>
        22. Compliance Clause:<br/>
        Supplier is aware of the Social responsibility and which include principles against Bribery and Corruption Supplier hereby certifies that he does not and will not use illegal practices such as giving
        money or gifts to Adler employees or members of their families in exchange for business from Adler. Supplier also agrees that, in the event that Adler determines that a violation of principles
        against Corruption have occurred, Adler shall notify supplier, and Adler may terminate this agreement/PO immediately, and such termination shall be with cause. Supplier shall indemnify Adler and
        hold Adler harmless with respect to any liability arising from the contravention of this provision by supplier.<br/>
        23. Termination of Purchase Order - Company may at any time terminate any or all purchase orders placed by Company. Unless otherwise specified in this Agreement, Company's liability to
        Supplier with respect to such terminated purchase order or orders shall be limited to: (1) Supplier's purchase price of all components for the PRODUCT, plus (2) the actual costs incurred by
        Supplier in procuring and manufacturing PRODUCT in process at the date of the notice of termination; less (3) any salvage value thereof. If requested, Supplier agrees to substantiate such costs
        with proof satisfactory to Company.<br/>
        </p>

        @elseif(($final_purchase['supplier_type']=="direct" && (str_starts_with($final_purchase['po_number'] , 'WO'))))
       
        <p>
        1. Payment Term - 30 days credit from the date of receipt of service or date of receipt of Invoice whichever is later.<br/>
        2. Service - Please quote the Work Order (W.O.) number on your invoice and on any other correspondence in connection with this order. Please also mention Adler Item Code Number & SAC Code
        line by line.<br/>
        3. The Company does not accept any responsibility for the service that is received without authorized Work order.<br/>
        4. Order Acknowledgment - Please confirm acceptance of this order in any form - Acknowledgment on Adler WO / by e-mail / your standard format for order acknowledgment etc. In the absence
        of such acceptance, the services shall constitute full acceptance by the Service Provider of the terms and conditions herein.<br/>
        5. Site Visit - If the Service Provider, by the terms of this order., is required to perform any work in the Company's premises, the Service Provider shall be responsible for any damages or injuries to
        persons or property including Company's employees and property, caused as a result of fault or negligence or for any reasons whatsoever, including omission or commission in doing or complying
        with certain requisites, statutory or otherwise by the Service provider and/or his agents. Before commencing contract work the Service Provider shall furnish to the Company policies of insurance
        showing that the Service provider has taken adequate cover for public liability, property damage and workmen's compensation and cover the risk to property and body of the Supplier's employees.<br/>
        6. Service received as per the W.O. prior to approval shall not constitute an acceptance of the service even if payment is made in advance.<br/>
            I / We, hereby accept this service with the term mentioned herein<br/>
            Service Provider's Acceptance<br/>
            (Supplier Signature & Date)<br/>
            WORK ORDER<br/>
            Survey No.48/3 & 48/7 Pashan-Sus Road Pune-411021<br/>
        7. Service should be accompanied by your Commercial invoice with Adler GST Number 27AADCA0618C1ZB mentioned on it.<br/>
        8. If advance payment received from the Adler should be mentioned on the bills and the give the payment details.<br/>
        9. Service are to be delivered within 7 days from the receipt of W.O. failing which the Company shall be at liberty to cancel the order (in part or in full) and / or service the undelivered will take
        from other sources. The Service Provider shall reimburse the Company the additional cost incurred by way of increase in price and incidental expenses in connection with such services from other
        sources, if any.<br/>
        # Inspection: Will be done at our end as per our specification.<br/>
        # NABL Testing Reports required after completion of work within 7 days.<br/>
        10. Any increase / reduction in government levies / taxes will be to the Adler's account.<br/>
        11. The Company reserves the right to cancel the W.O. or amend the service's quantities indicated in the W.O. arising out of any change in Company's sales requirements / manufacturing program
        or from any cause or causes beyond the Company's control.<br/>
        # Service not conforming to the Company's specifications / standards are liable to be rejected and the Company's decision in this regard will be final.<br/>
        # The report shall be sent to you mentioning why it has been rejected. In case of dispute on this report you can send your Representative for on-site re-verification of the report.<br/>
        # Upon rejection of Service, Adler will raise a Debit Note & against the same you shall issue credit note. In case of advance payment the service provider shall repay advance,<br/>
        # If any defects or discrepancies are notified in the service at a later date, which went undetected at the time of service, they shall be freely re-serviced by the Service Provider.<br/>
        12. The Serice Provider guarantees that the service will not infringe any legislation, Indian or foreign concerning patent, design copyright or trademark and undertakes to indemnify and keep the
        company indemnified against any action, judgement, decree, cost, claim, demand and expense resulting from any actual or alleged violation / infringement of statutes concerning patent, design or
        trademark, copyrights etc. The Service provider further undertakes at his own expense to defend or assist in the defense of any suit or action which may be brought in this connection against the
        Company by any third party.<br/>
        13. The Company reserves the right to cancel the W.O. or part thereof, without ANY CANCELATION AMOUNT, if a stoppage of Company's manufacturing, trading or distribution activities, in total or
        in part occurs due to fire, worker's agitation, strike, lockout, Government legislation, force majeure or from any cause or causes beyond the Company's control.<br/>
        14. Any litigation arising out of or in connection with this order shall be subject to MUMBAI Jurisdiction only.
        15. The terms and conditions indicated above supersede all terms and conditions mentioned in the Service provider's /agents quotation/indent/invoice or any other documents pertaining to the
        transactions covered by this WO.<br/>
        16. Compliance Clause:<br/>
        A) Supplier hereby certifies that he does not and will not employ any person to manufacture or provide goods or services who is under eighteen (18) years of age (hereinafter "Child Labour").
        Supplier has used reasonable efforts to determine whether his suppliers use Child Labour in manufacturing or providing goods or services, and he certifies that he, after reasonable inquiry,
        is not aware of any of his suppliers of goods and services that use Child Labour. Supplier hereby certifies that the workers he uses and will use, to produce and supply the goods or provide
        the services are present voluntarily. Supplier certifies that he and his suppliers of goods and services do not and will notknowingly use forced labour.<br/>
        Supplier understands that these certifications and undertakings are essential to this contract. Supplier shall indemnify Adler and hold Adler harmless contravention of these provisions
        by supplier or any of his suppliers with respect to the goods or services used in the supply chain. Supplier also agrees that, in the event that Adler determines that a violation of this provision
        has occurred, Adler shall notify supplier and supplier shall immediately remedy the violation. In the event that Adler determines that supplier has not remedied the violation,
        then Adler may terminate this agreement/PO immediately, and such termination shall be with cause.<br/>
        B) Supplier is aware of the Social responsibility and which include principles against Bribery and Corruption Supplier hereby certifies that he does not and will not use illegal practices such as giving
        money or gifts to Adler employees or members of their families in exchange for business from Adler. Supplier also agrees that, in the event that Adler determines that a violation of principles
        against Corruption have occurred, Adler shall notify supplier, and Adler may terminate this agreement/PO immediately, and such termination shall be with cause. Supplier shall indemnify Adler and
        hold Adler harmless with respect to any liability arising from the contravention of this provision by supplier.<br/>
        C) Supplier is aware that Adler applies a high standard of care in connection with the protection of the environment. Supplier hereby certifies that he complies at least with the Environmental laws
        of the country where he operates and where the goods are manufactured or handled. Adler may at its sole discretion, during regular business hours and after reasonable notice conduct audits to
        verify whether the legal requirements of such country are met. Supplier also agrees that, in the event that Adler determines that a violation of such laws has occurred, Adler shall notify
        supplier and Adler may terminate this agreement/PO immediately, and such termination shall be with cause. Supplier shall indemnify Alder and hold Adler harmless with respect to any liability
        arising from the contravention of this provision by supplier.<br/>
        17. Termination of Purchase / Work Order - Company may at any time terminate any or all purchase orders placed by Company. Unless otherwise specified in this Agreement, Company's liability to
        Supplier with respect to such terminated purchase / Service order or orders shall be limited to: (1) Supplier's purchase price of all components for the PRODUCT, plus (2) the actual costs incurred by
        Supplier in procuring and manufacturing PRODUCT in process at the date of the notice of termination; less (3) any salvage value thereof. If requested, Supplier agrees to substantiate such costs
        with proof satisfactory to Company.<br/>
        </p>
        @elseif(($final_purchase['supplier_type']=="indirect" && (str_starts_with($final_purchase['po_number'] , 'WO'))))
        <p>
        1. Payment Term - 60 days credit from the date of receipt of service or date of receipt of Invoice whichever is later.<br/>
        # Toll Charges - At actual<br/>
        # Halt Charges - Extra Rs. 1000/- for one day<br/>
        # Diesel /surcharge - at acual (Diesel Base rate forumala - Disel rate@93.92. Fixed rate@16/-per kms. Vehicle avg. 12 per kms)
        Service are to be delivered within 07 days from the receipt of W.O. failing which the Company shall be at liberty to cancel the order (in part or in full) and / or service the undelivered will take
        from other sources. The Service Provider shall reimburse the Company the additional cost incurred by way of increase in price and incidental expenses in connection with such services from other
        sources, if any.<br/>
        2. Service - Please quote the Work Order (W.O.) number on your invoice and on any other correspondence in connection with this order. Please also mention Adler Item Code Number & SAC Code
        line by line.<br/>
        3. The Company does not accept any responsibility for the service that is received without authorized Work order.<br/>
        4. Order Acknowledgment - Please confirm acceptance of this order in any form - Acknowledgment on Adler WO / by e-mail / your standard format for order acknowledgment etc. In the absence
        of such acceptance, the services shall constitute full acceptance by the Service Provider of the terms and conditions herein.<br/>
        5. Site Visit - If the Service Provider, by the terms of this order., is required to perform any work in the Company's premises, the Service Provider shall be responsible for any damages or injuries to
        persons or property including Company's employees and property, caused as a result of fault or negligence or for any reasons whatsoever, including omission or commission in doing or complying
        with certain requisites, statutory or otherwise by the Service provider and/or his agents. Before commencing contract work the Service Provider shall furnish to the Company policies of insurance
        showing that the Service provider has taken adequate cover for public liability, property damage and workmen's compensation and cover the risk to property and body of the Supplier's employees.<br/>
        6. Service received as per the W.O. prior to approval shall not constitute an acceptance of the service even if payment is made in advance.
        I / We, hereby accept this service with the term mentioned herein<br/>
        Service Provider's Acceptance<br/>
        (Supplier Signature & Date)<br/>
        WORK ORDER<br/>
        At/PO. Sadavali, Tal. Sangameshwar<br/>
        7. Service should be accompanied by your Commercial invoice with Adler GST Number 27AADCA0618C1ZB mentioned on it.<br/>
        8. If advance payment received from the Adler should be mentioned on the bills and the give the payment details.<br/>
        9. Service are to be delivered within 7 days from the receipt of W.O. failing which the Company shall be at liberty to cancel the order (in part or in full) and / or service the undelivered will take
        from other sources. The Service Provider shall reimburse the Company the additional cost incurred by way of increase in price and incidental expenses in connection with such services from other
        sources, if any.<br/>
        # Inspection: Will be done at our end as per our specification.<br/>
        # NABL Testing Reports required after completion of work within 7 days.<br/>
        10. Any increase / reduction in government levies / taxes will be to the Adler's account.<br/>
        11. The Company reserves the right to cancel the W.O. or amend the service's quantities indicated in the W.O. arising out of any change in Company's sales requirements / manufacturing program
        or from any cause or causes beyond the Company's control.<br/>
        # Service not conforming to the Company's specifications / standards are liable to be rejected and the Company's decision in this regard will be final.<br/>
        # The report shall be sent to you mentioning why it has been rejected. In case of dispute on this report you can send your Representative for on-site re-verification of the report.<br/>
        # Upon rejection of Service, Adler will raise a Debit Note & against the same you shall issue credit note. In case of advance payment the service provider shall repay advance,<br/>
        # If any defects or discrepancies are notified in the service at a later date, which went undetected at the time of service, they shall be freely re-serviced by the Service Provider.<br/>
        12. The Serice Provider guarantees that the service will not infringe any legislation, Indian or foreign concerning patent, design copyright or trademark and undertakes to indemnify and keep the
        company indemnified against any action, judgement, decree, cost, claim, demand and expense resulting from any actual or alleged violation / infringement of statutes concerning patent, design or
        trademark, copyrights etc. The Service provider further undertakes at his own expense to defend or assist in the defense of any suit or action which may be brought in this connection against the
        Company by any third party.<br/>
        13. The Company reserves the right to cancel the W.O. or part thereof, without ANY CANCELATION AMOUNT, if a stoppage of Company's manufacturing, trading or distribution activities, in total or
        in part occurs due to fire, worker's agitation, strike, lockout, Government legislation, force majeure or from any cause or causes beyond the Company's control.<br/>
        14. Any litigation arising out of or in connection with this order shall be subject to MUMBAI Jurisdiction only.<br/>
        15. The terms and conditions indicated above supersede all terms and conditions mentioned in the Service provider's /agents quotation/indent/invoice or any other documents pertaining to the
        transactions covered by this WO.<br/>
        16. Compliance Clause:<br/>
        A) Supplier hereby certifies that he does not and will not employ any person to manufacture or provide goods or services who is under eighteen (18) years of age (hereinafter "Child Labour").
        Supplier has used reasonable efforts to determine whether his suppliers use Child Labour in manufacturing or providing goods or services, and he certifies that he, after reasonable inquiry,
        is not aware of any of his suppliers of goods and services that use Child Labour. Supplier hereby certifies that the workers he uses and will use, to produce and supply the goods or provide
        the services are present voluntarily. Supplier certifies that he and his suppliers of goods and services do not and will notknowingly use forced labour.
        Supplier understands that these certifications and undertakings are essential to this contract. Supplier shall indemnify Adler and hold Adler harmless contravention of these provisions
        by supplier or any of his suppliers with respect to the goods or services used in the supply chain. Supplier also agrees that, in the event that Adler determines that a violation of this provision
        has occurred, Adler shall notify supplier and supplier shall immediately remedy the violation. In the event that Adler determines that supplier has not remedied the violation,
        then Adler may terminate this agreement/PO immediately, and such termination shall be with cause.<br/>
        B) Supplier is aware of the Social responsibility and which include principles against Bribery and Corruption Supplier hereby certifies that he does not and will not use illegal practices such as giving
        money or gifts to Adler employees or members of their families in exchange for business from Adler. Supplier also agrees that, in the event that Adler determines that a violation of principles
        against Corruption have occurred, Adler shall notify supplier, and Adler may terminate this agreement/PO immediately, and such termination shall be with cause. Supplier shall indemnify Adler and
        hold Adler harmless with respect to any liability arising from the contravention of this provision by supplier.<br/>
        C) Supplier is aware that Adler applies a high standard of care in connection with the protection of the environment. Supplier hereby certifies that he complies at least with the Environmental laws
        of the country where he operates and where the goods are manufactured or handled. Adler may at its sole discretion, during regular business hours and after reasonable notice conduct audits to
        verify whether the legal requirements of such country are met. Supplier also agrees that, in the event that Adler determines that a violation of such laws has occurred, Adler shall notify
        supplier and Adler may terminate this agreement/PO immediately, and such termination shall be with cause. Supplier shall indemnify Alder and hold Adler harmless with respect to any liability
        arising from the contravention of this provision by supplier.<br/>
        17. Termination of Purchase / Work Order - Company may at any time terminate any or all purchase orders placed by Company. Unless otherwise specified in this Agreement, Company's liability to
        Supplier with respect to such terminated purchase / Service order or orders shall be limited to: (1) Supplier's purchase price of all components for the PRODUCT, plus (2) the actual costs incurred by
        Supplier in procuring and manufacturing PRODUCT in process at the date of the notice of termination; less (3) any salvage value thereof. If requested, Supplier agrees to substantiate such costs
        with proof satisfactory to Company.
        @endif */ ?>

        <?= nl2br($terms_condition->terms_and_conditions);?>
    </div>
    <div style="border-top:solid 1.5px black; margin-top:5px;font-size:10px;">
    Adler Ref No :
    </div>
    @endif
     
   
</body>
</html>