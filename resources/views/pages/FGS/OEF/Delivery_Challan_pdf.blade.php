<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

</head>

<body>
    @inject('fn', 'App\Http\Controllers\Web\FGS\OEFController')
    <style>
        #table,
        td,
        th {
            border-collapse: separate;
            border: 1px solid black;
        }
    </style>

    <!-- <div class="row1" style="height:150px;border-bottom:solid 2px black;"> -->




    <div class="row" style="margin: 50px;">
        <table style="width: 100%;" id="table">
            <tr>
                <td colspan="7">
                    <img  src="{{asset('/img/logo.png')}}" style="width:80px;margin:10px ;">
                     <span style="font-weight: bold; font-size: 25px;margin-left: 100px;">Adler Healthcare Pvt. Ltd.</span> <span style="margin-left: 50px;">CIN : U33125PN2020PTC195161</span> <br>
                        <p style="margin-left: 100px;">Plot No. A-1, MIDC Sadavali, Tal. : Sangameshwar, Dist. Ratnagiri, PIN - 415 804. Contact No.: 8055136000 / 8055146000</p>
                    

                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <h2 style="text-align: center;">DELIVERY CHALLAN</h2>
                </td>
            </tr>
            <tr>
                <table id="table1" style="width: 100%;">
                    <tr>

                        <td colspan="3">To:
                            <span style="font-size: 20px; font-weight: bold;">SKY MARKETING</span>
                        </td>
                        <td colspan="2" rowspan="2">Ref. No.: <br>
                            Ref. Date:
                        </td>
                        <td>Doc No. :</td>
                        <td>DC-2324-xxx</td>
                    </tr>
                    <tr>
                        <td style="width: 100px;"></td>
                        <td colspan="2" rowspan="2">Auto fetche from customer master</td>

                        <td>Doc Date :</td>
                        <td>Login Date</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td>Trnsctn Type:</td>
                        <td>Consignment</td>
                    </tr>
                    <tr>
                        <td colspan="3">Zone: <span>Auto fetche from customer master</span></td>
                        <td colspan="2"></td>
                        <td>Trnsctn Cndtn:</td>
                        <td>Returnable</td>
                    </tr>
                    <tr>
                        <td colspan="3">Stock Location (Decrease): <span>AHPL Mktd.</span></td>
                        <td colspan="2"></td>
                        <td>Product Category:</td>
                        <td>OBM</td>
                    </tr>
                    <tr>
                        <td colspan="3">Stock Location (Increase): <span>As per selected transaction type</span></td>
                        <td colspan="4"></td>
                        
                    </tr>
                </table>
            </tr>

            <tr>
            <table id="table" style="width: 100%;">
                <td>Sr. No.</td>
                <td>min_id</td>
                <td>Item Description</td>
                <td>Batch No.</td>
                <td>Qty.</td>
                <td>Date of Mfg.</td>
                <td>Date of Expiry</td>
            </tr>
            <tr >
                <td style="height: 35px;"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3" style="height: 250px;" valign="top">
            Remarks:</td>
                <td colspan="4" ><span >For Adler Healthcare Pvt. Ltd. </span><br>
               <span>Authorised signatory</span> </td>
            </tr>
            </table>
        </table>

    </div>


</body>

</html>