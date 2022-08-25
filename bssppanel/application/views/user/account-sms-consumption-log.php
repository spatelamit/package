<?php
error_reporting(0);
$hostname = "localhost";
$username = "bulksms_user";
$password = "BALAJI@sr#ts7828";
$database = "bulksms_system";


$conn = mysql_connect("$hostname", "$username", "$password") or die(mysql_error());
mysql_select_db("$database", $conn) or die(mysql_error());
?>
<div class="portlet-content">
                    <div class="table-responsive">
                        <table class="table table-bordered bgf" id="all_user_cunsumption">
                            <thead>
                                <tr style="background-color: darkgrey;">
                                    <th>Sr. No</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Total Promotional SMS</th>
                                    <th>Total Transactional SMS</th>
                                    <th>Total Stock SMS</th>
                                    <th>Total Premium SMS</th>


                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($users_sms) && $users_sms) {
                                    $i = 1;
                                    foreach ($users_sms as $row) {
                                        ?>
                                        <tr  <?php if ($i % 2 == 0) { ?>
                                                style="background-color: lightgray;"  
                                            <?php } else { ?>
                                                style="background-color: ghostwhite"  

                                            <?php } ?>>
                                            <td><?php echo $i; ?></td>
                                            <td><?php
                                                $user_id = $row['user_id'];
                                                $query = "SELECT `username`, `name` FROM `users` WHERE `user_id` = '" . $user_id . "'";
                                                $result = mysql_query($query);
                                                $row1 = mysql_fetch_array($result);
                                                $name = $row1["name"];
                                                echo $name;
                                                ?>
                                            </td>
                                            <td><?php echo $row1['username']; ?></td>
                                            <td><?php
                                                if ($row['promotional_sms'] != 0) {
                                                    echo $row['promotional_sms'];
                                                } else {
                                                    echo "0";
                                                }
                                                ?>

                                            </td>
                                            <td>
                                                <?php
                                                if ($row['transactional_sms'] != 0) {
                                                    echo $row['transactional_sms'];
                                                } else {
                                                    echo "0";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['stock_sms'] != 0) {
                                                    echo $row['stock_sms'];
                                                } else {
                                                    echo "0";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['premium_sms'] != 0) {
                                                    echo $row['premium_sms'];
                                                } else {
                                                    echo "0";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="20" align="center">
                                            <strong><b>No User Exist!</b></strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>

<!--                            <tfoot>
                                <tr>
                                    <td align="center" colspan="20">
                                        <ul class="pagination margin0">
                            <?php //echo $pagination_helper;    ?>
                                        </ul>
                                    </td>
                                </tr>
                            </tfoot>-->

                        </table>
                    </div>
                </div> 