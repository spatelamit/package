<!DOCTYPE html>
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
                border-bottom: 1px solid #ccc;
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
            .divHidden{
                display: none;
            }

        </style>
    </head>
    <body>
         <!-- <form method="POST" action="<?php// echo base_url(); ?>admin/mail_invoice">-->
       <form method="post" action="<?php echo base_url()."admin/mail_invoice/".$txn_data->txn_user_to;?>">
        <div class="container">
            <div class=" wrapper">
              
                    <h2 class="heading">Invoice</h2>
                    <div class="row">
                        <div class="date">
                            <p>Date:<input class="form-control" name="view_date" type="input" id="datepicker" value="<?php echo $txn_data->new_date; ?>"  placeholder="Payment Date"  /></p>
                            <p>Invoice #:<b>bulk24sms/1402538</b></p>
                        </div>	
                    </div>

                    <div class="row">
                        <div class="col-md-6"><p>From:<br>
                                <b>SHREERAM TECHNOLOGY SERVICES PVT. LTD.</b><br>
                                402, Navneet Plaza<br>
                                10/2, Old Palasia, near Greater Kailash Hospital,<br>
                                Indore 452001, M.P.</p>
                        </div>
                        <div class="col-md-6">
                            <p class="side_to">To: <br><textarea cols="59" rows="5" name="to_address"></textarea>
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
                                <td align="center"><b>
                                    <?php
                                    if ($txn_data->txn_route == "A") {?>
                                        <input type="text" name="description" value="<?php echo "Promotional SMS";?>">    
                                   <?php } else {
                                        if ($txn_data->txn_route == "B") { ?>
                                      <input type="text" name="description" value="<?php   echo "Transactional SMS";?>">        
                                    <?php    } else {
                                            if ($txn_data->txn_route == "VB") { ?>
                                 <input type="text" name="description" value="<?php    echo "Dynamic Voice SMS";?>">                
                                       <?php     } else {
                                                if ($txn_data->txn_route == "VA") { ?>
                                   <input type="text" name="description" value="<?php   echo "Promotional Voice SMS";?>">                      
                                     <?php           }
                                            }
                                        }
                                    }
                                    ?>
                                    </b>
                                </td>
                                <?php
                                $amount = $txn_data->txn_amount;
                                if ($txn_data->tax_status == 1) {
                                    $get_amount = $txn_data->txn_amount;
                                    $calculate = $get_amount * 15 / 100;
                                    $amount = $get_amount - $calculate;
                                    $service_tax = $amount * 14 / 100;
                                    $swachh_bharat = $amount * 0.5 / 100;
                                    $krishi_kalyan = $amount * 0.5 / 100;
                                    $total_amount = $amount + $service_tax + $swachh_bharat + $krishi_kalyan;
                                }
                                ?>
                                <td align="center" ><b><input type="text" name="amount" value="<?php echo $amount; ?>"></b></td>
                            </tr>
                        </table>
                        <div class="totals">
                            <input type="hidden" name="status" value="1" id="checkData">
                            <b>Tax Calculate:</b><input type="checkbox" id="checkbox" onclick="ShowHideDiv(this)" style="width: 20px;height: 16px;" checked="">
                        </div>
                        
                     <!--   <div class="total" id="tax_status" style="display: none;">-->
                     <div class="total" id="tax_status" style="display: none;">
                            Output Service Tax (14.0%) :<input type="text" id="service_tax" name="service_tax" value="<?php echo $service_tax; ?>"><br>
                            Swachh Bharat Cess (0.5%) :	<input type="text" name="swachh_bharat" id="swachh_bharat" value="<?php echo $swachh_bharat; ?>"><br>
                            Krishi Kalyan Cess (0.5%) :	<input type="text" name="krishi_kalyan" id="krishi_kalyan" value="<?php echo $krishi_kalyan; ?>"><br>
                            <b>Total Amount :	<input type="text" name="total_amount" id="total_amount" value="<?php echo $total_amount; ?>"></b>
                        </div>
                    </div>

                    <div class="row">
                        <p class="amt"><b>Amount In Words:</b><br>

                            <?php
                            $in_words = convert_number_to_words($amount);
                            $in_words = ucwords($in_words);
                            echo "<b>".$in_words . " Only /-</b>";
                            ?><br></p>

                        <p class="mid_content"> SHREERAM TECHNOLOGY SERVICES PVT. LTD.<br>
                            CIN: U74900MP2016PTC035210 <br> 
                            STC:  AAWCS6188CSD001 <br>
                            PAN: AAWCS6188C<br></p>
                        <p class="sign">Digitally generated no signature required</p>
                        <div style="border-bottom: 1px solid #ccc;padding-bottom: 30px; margin: 0px 10px 0px 10px;"></div>
                    </div>

                    <div class="row">
                        <p class="term_con"><b>Terms and Conditions:</b><br>

                            Above amount is in Indian National Rupees (INR)<br>
                            We declare that this invoice shows the actual price of the services rendered and that all particulars are true and correct.<br>
                            Please wire transfers the amount to the account given below: Wire transfer Details: ICICI Bank LTD<br>
                            A/c #: 657305600391   IFSC: ICIC0000041</p>

                    </div>
                    <input style=" height: 40px; width: 150px; margin-left:400px;font-weight: bold; margin-top: 40px; background-color: royalblue ; color: white;" type="submit" name="submit" value="Send Invoice">
            </div>	
              
        </div><!--container-->
       
                </form>
    </body>
</html>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
    var total = document.getElementById("tax_status");
          total.style.display = checkbox.checked ? "block" : "none";  
    function ShowHideDiv(checkbox) {
        //var total = document.getElementById("tax_status");
      //  total.style.display = checkbox.checked ? "block" : "none";
    var selected1 = 1;
    var selected2 = 0;
           var total = document.getElementById("tax_status");
          total.style.display = checkbox.checked ? "block" : "none";  
        if (checkbox.checked)
        { 
              $('#checkData').val(selected1);
        }else{
             $('#tax_status').addClass();
             $('#checkData').val(selected2);
        }
    
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
<script>
function checkTaxAmount()
{
    var selected_contacts = "";
    var number = 1;
    var count = 0;
    $('.check_contact').each(function (index, value) {
        if ($(value).prop('checked'))
        {
            if (selected_contacts === "")
                selected_contacts = $(value).val();
            else
                selected_contacts += "," + $(value).val();
            count++;
        }
        number++;
    });
    if (selected_contacts === "")
    {
        $('#delete_option').addClass('hidden');
        $('#delete_button').val('Delete 0 Contacts');
        $('#selected_contacts').val(selected_contacts);
    } else
    {
        $('#delete_option').removeClass('hidden');
        $('#delete_button').val('Delete ' + count + ' Contacts');
        $('#selected_contacts').val(selected_contacts);
    }
}

</script>