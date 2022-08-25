<!DOCTYPE html>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/jquery.min.js"></script>
                <link href="<?php echo base_url(); ?>Assets/admin/css/bootstrap.min.css" rel="stylesheet">
                        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/bootstrap-datetimepicker.min.css" />
        <script>
        function number2text(value) {
    var fraction = Math.round(frac(value)*100);
    var f_text  = "";

    if(fraction > 0) {
        f_text = "AND "+convert_number(fraction)+" PAISE";
    }

    return convert_number(value)+" RUPEE "+f_text+" ONLY";
}

function frac(f) {
    return f % 1;
}

function convert_number(number)
{
    if ((number < 0) || (number > 999999999)) 
    { 
        return "NUMBER OUT OF RANGE!";
    }
    var Gn = Math.floor(number / 10000000);  /* Crore */ 
    number -= Gn * 10000000; 
    var kn = Math.floor(number / 100000);     /* lakhs */ 
    number -= kn * 100000; 
    var Hn = Math.floor(number / 1000);      /* thousand */ 
    number -= Hn * 1000; 
    var Dn = Math.floor(number / 100);       /* Tens (deca) */ 
    number = number % 100;               /* Ones */ 
    var tn= Math.floor(number / 10); 
    var one=Math.floor(number % 10); 
    var res = ""; 

    if (Gn>0) 
    { 
        res += (convert_number(Gn) + " CRORE"); 
    } 
    if (kn>0) 
    { 
            res += (((res=="") ? "" : " ") + 
            convert_number(kn) + " LAKH"); 
    } 
    if (Hn>0) 
    { 
        res += (((res=="") ? "" : " ") +
            convert_number(Hn) + " THOUSAND"); 
    } 

    if (Dn) 
    { 
        res += (((res=="") ? "" : " ") + 
            convert_number(Dn) + " HUNDRED"); 
    } 


    var ones = Array("", "ONE", "TWO", "THREE", "FOUR", "FIVE", "SIX","SEVEN", "EIGHT", "NINE", "TEN", "ELEVEN", "TWELVE", "THIRTEEN","FOURTEEN", "FIFTEEN", "SIXTEEN", "SEVENTEEN", "EIGHTEEN","NINETEEN"); 
var tens = Array("", "", "TWENTY", "THIRTY", "FOURTY", "FIFTY", "SIXTY","SEVENTY", "EIGHTY", "NINETY"); 

    if (tn>0 || one>0) 
    { 
        if (!(res=="")) 
        { 
            res += " AND "; 
        } 
        if (tn < 2) 
        { 
            res += ones[tn * 10 + one]; 
        } 
        else 
        { 

            res += tens[tn];
            if (one>0) 
            { 
                res += ("-" + ones[one]); 
            } 
        } 
    }

    if (res=="")
    { 
        res = "zero"; 
    } 
    return res;
}
        </script>
        
        <script>
    function calculateAmount(price)
{
    var total = $('#amount').val();
    var service_tax =total*14/100
     var swatchh_bharat = total*0.5/100 
     var krishi_kalyan = total*0.5/100 
     
      $('#service_tax').val(service_tax)
       $('#swatchh_bharat').val(swatchh_bharat)
        $('#krishi_kalyan').val(krishi_kalyan)
        
  
     
    var total_amount = +total + +service_tax + +swatchh_bharat + +krishi_kalyan;
    $('#total_amount').val(total_amount);
   var inwords =  number2text(total_amount);
    $('#words').val(inwords);
}
</script>
  
<?php 

function convert_number_to_words($number) {

    $hyphen = '-';
    $conjunction = '  ';
    $separator = ' ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = array(
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'fourty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand',
        1000000 => 'million',
        1000000000 => 'billion',
        1000000000000 => 'trillion',
        1000000000000000 => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int) ($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
?>

<?php
$amount = $txn_data->txn_amount;
$service_tax = $amount * 14 / 100;
$swachh_bharat = $amount * 0.5 / 100;
$krishi_kalyan = $amount * 0.5 / 100;
$total_amount = $amount + $service_tax + $swachh_bharat + $krishi_kalyan;
?>
<html>
    <head>
        <title></title>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!--<link rel="stylesheet" type="text/css" href="css/custom.css">-->
        <style type="text/css">
            body{
                font-family: Helvetica;
                background: #ccc;

            }
            .wrapper{
                background: #fff;
                box-shadow: 5px 10px 5px 10px grey;
                padding: 60px 60px 60px 60px;
            }
            .heading{
                text-align: center;
               padding-bottom: 10px;
            }
            .date{
                float: right;
                font-size: 15px;
                margin: 5px 10px 0px 10px;

            }
            .side_to{
                width: 50%;
                line-height: 1.5;
                float: left;
            }
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                margin: 0px 15px 0px 8px;

            }
            th, td {
                padding: 5px;
            }
            th {
                text-align: left;
            }
            .totals{
                float: right;
                padding: 5px;
                margin: 10px 240px;
            }
            .total{
                float: right;
                padding: 5px;
                margin-right: 86px;
            }

            .amt{
                border-bottom: 1px solid #ccc;
                padding-bottom: 10px;
                padding-top: 10px;
                line-height: 2.5;
                font-size: 15px;
                margin: 0px 10px 0px 10px;
            }
            .mid_content{
                line-height: 1.5;
                font-size: 15px;
                padding-top: 5px;
                margin: 0px 10px 0px 10px;
            }
            .sign{
                float: right;
                font-size: 15px;
                margin-right: 10px;
            }
            .term_con{
                font-size: 15px;
                line-height: 1.5;
                padding: 10px 0px 0px 0px;
                margin: 0px 10px 0px 10px;
            }

        </style>
    </head>
    <body>
        
        <form method="POST" action="http://192.168.1.231/pdf_con/">
            <div class="container">
               
                <div class=" wrapper">
                    <img style="margin-left: 10px; width: 120px; height: 120px; margin-bottom: -100px;" src="<?php echo base_url(); ?>Assets/user/img/LOGOMAIN.png"/>
                    <h2 class="heading">Invoice</h2>
                    <div class="row">
                        <div class="date">
                            <?php 
                            $current_date = date("d-m-Y");
                            ?>
                            <p>Date:<input class="form-control" type="text" id="datepicker" name="date" value="<?php echo $current_date ; ?>" placeholder="DD-MM-YYYY"   /></p>
                            <p>Invoice #:<b><input class="form-control" type="text" placeholader="Invoice ID" name="invoice_id"  /></b></p>
                        </div>	
                    </div>

                    <div class="row">
                        <div class="col-md-6"><p><b>From:</b><br>
                            <p class="side_to"><textarea style="margin-top: -10px;" cols="59" rows="5">SHREERAM TECHNOLOGY SERVICES PVT. LTD.
 402, Navneet Plaza
10/2, Old Palasia, near Greater Kailash Hospital,
Indore 452001, M.P.</textarea></p>
                        </div>
                        <div class="col-md-6">
                            <p class="side_to"><b>To:</b> <br><textarea cols="59" rows="5" name="to"></textarea>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <table width="98%">
                            <tr>
                                <th style="text-align: center">Serial #</th>
                                <th style="text-align: center">Description</th>
                                <th style="text-align: center">Amount</th>
                            </tr>
                            <tr>
                                <td align="center"><b>1</b></td>
                                <td align="center"><input type="text"  name="type" placeholder="SMS Type" ><b>

                                    </b>
                                </td>

                                <td align="center" ><b><input type="text"  name="amount" id="amount" placeholder="Amount" onkeyup="calculateAmount(this.value);"></b></td>
                            </tr>
                        </table>
                        <div class="totals">
                            <b>Tax Calculate:</b><input type="checkbox" id="checkbox" onclick="ShowHideDiv(this)" style="width: 20px;height: 16px;">
                        </div>
                        <div class="total" id="tax_status" style="display: none;">

                            Output Service Tax (14.0%) :<input type="text" id="service_tax" name="service_tax" value="<?php echo $service_tax; ?>"><br>
                            Swachh Bharat Cess (0.5%) :	<input type="text" name="swachh_bharat" id="swatchh_bharat" value="<?php echo $swachh_bharat; ?>"><br>
                            Krishi Kalyan Cess (0.5%) :	<input type="text" name="krishi_kalyan" id="krishi_kalyan" value="<?php echo $krishi_kalyan; ?>"><br>
                            <b>Total Amount :	<input type="text" name="total_amount" id="total_amount" value="<?php echo $total_amount; ?>"></b>
                        </div>
                    </div>

                    <div class="row">
                        <p class="amt"><b>Amount In Words(With Tax):</b><br>
                            <input style="height: 30px; width: 500px;;" type="input" name="words" id="words" />
				<br></p>
                       
                        <div class="col-md-6">
                            <p class="side_to"> <br><textarea cols="59" rows="5">SHREERAM TECHNOLOGY SERVICES PVT. LTD.
CIN: U74900MP2016PTC035210 
STC:  AAWCS6188CSD001 
PAN: AAWCS6188C</textarea>
                            </p>
                        </div>
                        <p class="sign">Digitally generated no signature required</p>
                        <div style="padding-bottom: 30px; margin: 0px 10px 0px 10px;"></div>
                    </div>

                    <div class="row">
                        <p class="term_con"><b>Terms and Conditions:</b><br>

                            Above amount is in Indian National Rupees (INR)<br>
                            We declare that this invoice shows the actual price of the services rendered and that all particulars are true and correct.<br>
                            Please wire transfers the amount to the account given below:
                        <div class="col-md-6">

                            <p class="side_to"> <br><textarea style="height: 175px;" cols="59" rows="5" name="bank" >ICICI Bank LTD
A/c #: 657305600391   
IFSC: ICIC0000041

INDUSIND Bank LTD
A/c #: 258982804000
IFSC CODE: INDB0000011 

                                
                                </textarea>
                            </p>
                        </div>


                    </div>
                    <div  style="width: 1000px; height: 50px; margin-top: 30px; border-bottom: 1px solid; border-top: 1px solid;">
                   
                    </div>
                    
                    <input style=" height: 40px; width: 150px; margin-left:400px;font-weight: bold; margin-top: 40px; background-color: royalblue ; color: white;" type="submit" name="submit" value="Download Invoice">
                </div>	

            </div><!--container-->

        </form>
    </body>
</html>
<script type="text/javascript">
    function ShowHideDiv(checkbox) {
        var total = document.getElementById("tax_status");
        total.style.display = checkbox.checked ? "block" : "none";
    }

</script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
        $('#datepicker').datetimepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });



    });
</script>