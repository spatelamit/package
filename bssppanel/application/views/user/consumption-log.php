<div class="table-responsive" id="show_report">
                        <table class="table table-bordered table-hover bgf">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Username</th>
                                    <th style="color:#4A9AB5;text-align: center;">Submit</th> 
                                    <th style="color:#4271A5;text-align: center;">Sent</th> 
                                    <th style="color:#8CAA52;text-align: center;">Delivered</th> 
                                    <th style="color:#AD4942;text-align: center;">Failed</th> 
                                    <th style="color:#DE8742;text-align: center;">Rejected</th> 
                                    <th style="color:#000;text-align: center;">Blocked</th> 
                                    <th style="color:#6515a7;text-align: center;">Landline</th> 
                                    <th style="text-align: center;">Total</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($daily_sms) && $daily_sms) {
                                    $i = 1;
                                    ?>
                                    <tr>
                                        <th style="text-align: center;"><?php
                                            $query = "SELECT `username` FROM `users` WHERE `user_id` = '" . $user_id . "'";
                                            $result = mysql_query($query);
                                            $row1 = mysql_fetch_array($result);
                                            $username = $row1["username"];
                                            echo $username;
                                            ?></th> 
                                        <th style="color:#4A9AB5;text-align: center;">
                                            <?php
                                            if ($daily_sms[31]) {
                                                echo $daily_sms[31];
                                            } else {
                                                echo '0';
                                            }
                                            ?>
                                        </th> 
                                        <th style="color:#4271A5;text-align: center;"> <?php
                                            if ($daily_sms[3]) {
                                                echo $daily_sms[3];
                                            } else {
                                                echo '0';
                                            }
                                            ?>
                                        </th> 
                                        <th style="color:#8CAA52;text-align: center;">
                                            <?php
                                            if ($daily_sms[1]) {
                                                echo $daily_sms[1];
                                            } else {
                                                echo '0';
                                            }
                                            ?> 
                                        </th> 
                                        <th style="color:#AD4942;text-align: center;">
                                            <?php
                                            if ($daily_sms[2]) {
                                                echo $daily_sms[2];
                                            } else {
                                                echo '0';
                                            }
                                            ?> 
                                        </th> 
                                        <th style="color:#DE8742;text-align: center;">
                                            <?php
                                            if ($daily_sms[16]) {
                                                echo $daily_sms[16];
                                            } else {
                                                echo '0';
                                            }
                                            ?> 
                                        </th> 
                                        <th style="color:#000;text-align: center;">
                                            <?php
                                            if ($daily_sms['Blocked']) {
                                                echo $daily_sms['Blocked'];
                                            } else {
                                                echo '0';
                                            }
                                            ?> 
                                        </th> 
                                        <th style="color:#6515a7;text-align: center;">
                                            <?php
                                            if ($daily_sms[48]) {
                                                echo $daily_sms[48];
                                            } else {
                                                echo '0';
                                            }
                                            ?> 
                                        </th> 
                                        <th style="text-align: center;">
                                            <?php
                                            $total_sms = $daily_sms[31] + $daily_sms[3] + $daily_sms[1] + $daily_sms[2] + $daily_sms[16] + $daily_sms[48];
                                            if ($total_sms) {
                                                echo $total_sms;
                                            } else {
                                                echo '0';
                                            }
                                            ?>

                                        </th> 
                                    </tr>
                                    <?php
                                    $i++;
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="9" align="center">
                                            <strong>No Record Found!</strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td align="center" colspan="9">
                                        <ul class="pagination margin0">
                                            <?php echo $pagination_helper; ?>
                                        </ul>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>