<script src="<?php echo base_url(); ?>Assets/admin/js/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>Assets/admin/js/exporting.js" type="text/javascript"></script>

<?php
error_reporting(0);
$hostname = "localhost";
$username = "bulksms_user";
$password = "BALAJI@sr#ts7828";
$database = "bulksms_system";


$conn = mysql_connect("$hostname", "$username", "$password") or die(mysql_error());
mysql_select_db("$database", $conn) or die(mysql_error());
?>

<div class="col-md-6" style="margin-top: 70px;" >
    <div class="table-responsive" style="overflow-y: auto;height: 400px; width: 1160px;">
        <table class="table table-hover fade in bgf9" >
            <thead>
                <tr>
                    <th>Transection ID.</th>
                    <th>Virtual/Credits</th>
                    <th>Route</th>
                    <th>Text SMS</th> 
                    <th>Text Price</th>
                    <th>Text Amount</th>
                    <th>Text Type</th>
                    <th>Admin From</th>
                    <th>User to</th>
                    <th>Text Date</th>
                    <th>text Discription</th>
                    <th>Account Admin</th>
                    <th>Admin Discription</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                $j = 0;
                $data_amount = array();
                $data_text_sms = array();
                $received_amount = array();
                $pending_amount = array();
                $data_credit_sms = array();

                if (isset($seller_reports) && $seller_reports) {

                    foreach ($seller_reports as $seller_report) {

                        $verify_user_id = $seller_report['txn_user_to'];
                        $query = "SELECT `spacial_reseller_status` FROM `users` WHERE `user_id` = '" . $verify_user_id . "'";
                        $result = mysql_query($query);
                        $row = mysql_fetch_array($result);
                        $UserStatus = $row["spacial_reseller_status"];
                        if ($UserStatus != 1) {
                            ?>
                            <tr >
                                <td><?php echo $seller_report['txn_log_id']; ?></td>
                                <td>Virtual</td>
                                <td>
                                    <?php echo $seller_report['txn_route']; ?>
                                </td>
                                <td> 
                                    <?php
                                    $data_text_sms[$i] = $seller_report['txn_sms'];

                                    echo $seller_report['txn_sms'];
                                    ?>
                                </td>
                                <td>
                                    <?php echo $seller_report['txn_price']; ?>
                                </td>
                                <td>
                                    <?php
                                    $prize = $seller_report['txn_price'];
                                    if ($prize > 0.30) {
                                        $duplicate_amount = $seller_report['txn_amount'];
                                        $actual_amount = $duplicate_amount / 10;
                                        $data_amount[$i] = $actual_amount;
                                    } else {
                                        $data_amount[$i] = $seller_report['txn_amount'];
                                        $actual_amount = $seller_report['txn_amount'];
                                    }
                                    echo $actual_amount;
                                    if ($seller_report['txn_status']) {
                                        $received_amount[$i] = $actual_amount;
                                    } else {
                                        $pending_amount[$i] = $actual_amount;
                                    }
                                    ?>
                                </td>
                                <td> 
                                    <?php echo $seller_report['txn_type']; ?>
                                </td>
                                <td>
                                    <?php
                                    $admin_id = $seller_report['txn_admin_from'];
                                    $query = "SELECT `admin_name` FROM `administrators` WHERE `admin_id` = '" . $admin_id . "'";
                                    $result = mysql_query($query);
                                    $row = mysql_fetch_array($result);
                                    $adminName = $row["admin_name"];
                                    echo $adminName;
                                    ?>
                                </td>
                                <td>


                                    <?php
                                    $user_id = $seller_report['txn_user_to'];
                                    $query = "SELECT `username` FROM `users` WHERE `user_id` = '" . $user_id . "'";
                                    $result = mysql_query($query);


                                    $row = mysql_fetch_array($result);

                                    $username = $row["username"];
                                    echo $username;
                                    ?>

                                </td>
                                <td>
                                    <?php echo $seller_report['txn_date']; ?>
                                </td>
                                <td>
                                    <?php echo $seller_report['txn_description']; ?>
                                </td>
                                <td>
                                    <?php
                                    $admin_id = $seller_report['account_admin'];
                                    $query = "SELECT `admin_name` FROM `administrators` WHERE `admin_id` = '" . $admin_id . "'";
                                    $result = mysql_query($query);
                                    $row = mysql_fetch_array($result);
                                    $adminName = $row["admin_name"];
                                    echo $adminName;
                                    ?>
                                </td>

                                <td>
                                    <?php echo $seller_report['admin_discription']; ?>
                                </td>


                            </tr>
                            <?php
                            $i++;
                        } else {
                            
                        }

                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="6">No Record Found!</td>
                    </tr>
                    <?php
                }

                //credits
                if (isset($special_seller_reports) && $special_seller_reports) {
                    foreach ($special_seller_reports as $special_seller_report) {
                        ?>
                        <tr >
                            <td><?php echo $special_seller_report['special_tr_id']; ?></td>
                            <td>Credits</td>
                            <td>
                                <?php echo $special_seller_report['balance_type']; ?>
                            </td>
                            <td> 
                                <?php
                                $data_credit_sms[$j] = $special_seller_report['balance'];
                                echo $special_seller_report['balance'];
                                ?>
                            </td>
                            <td>
                                <?php echo $special_seller_report['sms_price']; ?>
                            </td>
                            <td>
                                <?php
                                // $prize = $special_seller_report['sms_price'];
                                /* if ($prize > 0.30) {
                                  $duplicate_amount = $special_seller_report['total_amount'];
                                  $actual_amount = $duplicate_amount / 10;
                                  $data_amount[$i] = $actual_amount;
                                  } else {
                                  $data_amount[$i] = $special_seller_report['total_amount'];
                                  $actual_amount = $special_seller_report['total_amount'];
                                  } */
                                echo $special_seller_report['total_amount'];
                                /* if($special_seller_report['tax_status']){
                                  $received_amount[$i] = $actual_amount;
                                  }else{
                                  $pending_amount[$i] = $actual_amount;
                                  } */
                                ?>
                            </td>
                            <td> 
                                <?php echo $special_seller_report['type']; ?>
                            </td>
                            <td>
                                <?php
                                $admin_id = $special_seller_report['admin_id'];
                                $query = "SELECT `admin_name` FROM `administrators` WHERE `admin_id` = '" . $admin_id . "'";
                                $result = mysql_query($query);
                                $row = mysql_fetch_array($result);
                                $adminName = $row["admin_name"];
                                echo $adminName;
                                ?>
                            </td>
                            <td>


                                <?php
                                $user_id = $special_seller_report['user_id'];
                                $query = "SELECT `username` FROM `users` WHERE `user_id` = '" . $user_id . "'";
                                $result = mysql_query($query);


                                $row = mysql_fetch_array($result);

                                $username = $row["username"];
                                echo $username;
                                ?>

                            </td>
                            <td>
                                <?php echo $special_seller_report['date_time']; ?>
                            </td>
                            <td>
                                <?php echo $special_seller_report['discription']; ?>
                              
                            </td>
                            <td>
                                <?php
                                $admin_id = $special_seller_report['account_admin'];
                                $query = "SELECT `admin_name` FROM `administrators` WHERE `admin_id` = '" . $admin_id . "'";
                                $result = mysql_query($query);
                                $row = mysql_fetch_array($result);
                                $adminName = $row["admin_name"];
                                echo $adminName;
                                ?>
                            </td>

                            <td>
                                <?php echo $special_seller_report['admin_discription']; ?>
                            </td>

                        </tr>
                        <?php
                        $j++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <br>
</div>

<div class="col-md-12" style="margin-top: -510px;">
    <div class="table-responsive">
        <table class="table table-bordered bgf9">
            <tbody>

                <tr>
                    <th>Total SMS</th>                          
                    <th  style="color: blue;">Total Amount</th>
                    <th  style="color: green;" >Received Amount</th>                           
                    <th  style="color: red;">Pending Amount</th>
                </tr>
                <tr>
                    <th><?php echo array_sum($data_text_sms); ?></th>
                    <th style="color: blue;" ><?php echo array_sum($data_amount) + array_sum($data_credit_sms); ?></th>
                    <th  style="color: green;" ><?php echo $x = array_sum($received_amount); ?></th>
                    <th  style="color: red;" ><?php echo $y = array_sum($pending_amount); ?></th>
                </tr>

            </tbody>
        </table>
    </div> 


</div>
<?php
$dataPoints = array(
    array("y" => $x, "name" => "Received Amount", "exploded" => true),
    array("y" => $y, "name" => "Pending Amount"),
);
?>






