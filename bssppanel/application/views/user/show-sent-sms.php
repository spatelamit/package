<table class="table table-hover bgf">
    <thead>
        <tr>
            <th>S.No.</th>
            <th>Receiver Number</th>
            <th>Status</th>
            <th>Submit Date</th>
            <th>Done Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        if ($sent_sms) {
            foreach ($sent_sms as $sms) {
                if ($sms['user_id'] == $user_id) {
                    $dlr_reciept = $sms['dlr_receipt'];
                    $decode_dlr_reciept = urldecode($dlr_reciept);
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $sms['mobile_no']; ?></td>
                        <td>
                            <?php
                            if ($sms['status'] == "1" || $sms['status'] == "ANSWERED") {
                                echo "Delivered";
                            } elseif ($sms['status'] == "2") {
                                echo "Failed";
                            } elseif ($sms['status'] == "31" || $sms['status'] == "23") {
                                echo "Pending";
                            } elseif ($sms['status'] == "8" || $sms['status'] == "SUBMITTED") {
                                echo "Submitted";
                            } elseif ($sms['status'] == "DND" || $sms['status'] == "9" || (strpos($decode_dlr_reciept, 'err:006') !== false && strpos($decode_dlr_reciept, 'stat:UNDELIV') !== false)) {
                                echo "NDNC";
                            } elseif ($sms['status'] == "16" || $sms['status'] == "Rejected" || $sms['status'] == "INVALID") {
                                echo "Rejected From Operator";
                            } elseif ($sms['status'] == "Blocked") {
                                echo "Blocked By Operator";
                            } elseif ($sms['status'] == "3" || $sms['status'] == "4") {
                                echo "Report Pending";
                            } elseif ($sms['status'] == "25") {
                                echo "Cancelled By User";
                            }
                            ?>
                        </td>
                        <td><?php echo $sms['submit_date']; ?></td>
                        <td>
                            <?php
                            if ($sms['status'] == "1" || $sms['status'] == "2" || $sms['status'] == "DND" || $sms['status'] == "9" || $sms['status'] == "16" || $sms['status'] == "Rejected" || $sms['status'] == "Blocked") {
                                echo $sms['done_date'];
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
            }
            if ($i == 1) {
                ?>
                <tr>
                    <td colspan="5">No Record Found!</td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="5">No Record Found!</td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>